<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Sale;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Models\ActivityLog;
use App\Config\Config;

class SalesController extends BaseController
{
    private Sale $sales;
    private InventoryItem $items;

    public function __construct()
    {
        $this->sales = new Sale();
        $this->items = new InventoryItem();
    }

    public function index(): void
    {
        $page    = $this->currentPage();
        $perPage = Config::get('pagination.per_page');
        $filters = [
            'search'    => $this->sanitize('search'),
            'date_from' => $this->sanitize('date_from'),
            'date_to'   => $this->sanitize('date_to'),
        ];
        $total      = $this->sales->countFiltered($filters);
        $pagination = paginate($total, $page, $perPage);
        $sales      = $this->sales->paginated($pagination['offset'], $perPage, $filters);

        $totalRevenue = array_sum(array_column($sales, 'total_revenue'));
        $totalProfit  = array_sum(array_column($sales, 'profit'));
        $totalUnits   = array_sum(array_column($sales, 'quantity_sold'));
        $items        = $this->items->paginated(0, 1000, []);

        $this->render('sales.index', compact('sales','filters','pagination','totalRevenue','totalProfit','totalUnits','items'));
    }

    public function create(): void
    {
        if (!can('inventory.*')) $this->redirect('sales', 'error', 'Permission denied.');
        $items = $this->items->paginated(0, 1000, []);
        $this->render('sales.create', compact('items'));
    }

    public function store(): void
    {
        if (!can('inventory.*')) $this->redirect('sales', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $itemId   = (int)$this->input('item_id');
        $qty      = (int)$this->input('quantity_sold', 1);
        $sellP    = (float)$this->input('selling_price', 0);
        $date     = $this->sanitize('sale_date') ?: date('Y-m-d');
        $notes    = $this->sanitize('notes');

        $errors = [];
        if (!$itemId)    $errors[] = 'Item is required.';
        if ($qty < 1)    $errors[] = 'Quantity must be at least 1.';
        if ($sellP <= 0) $errors[] = 'Selling price must be greater than 0.';

        $item = null;
        if (!$errors) {
            $item = $this->items->findWithCategory($itemId);
            if (!$item)                        $errors[] = 'Item not found.';
            elseif ($qty > (int)$item['available']) $errors[] = "Only {$item['available']} units available.";
        }

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('sales');
        }

        $before = (int)$item['quantity'];

        $id = $this->sales->create([
            'item_id'       => $itemId,
            'item_name'     => $item['name'],
            'quantity_sold' => $qty,
            'cost_price'    => (float)$item['cost'],
            'selling_price' => $sellP,
            'sold_by'       => auth()['id'],
            'sale_date'     => $date,
            'notes'         => $notes,
        ]);

        // Deduct from stock
        $this->items->updateAssignedQty($itemId, $qty);

        // Log stock movement
        StockMovement::record($itemId, $item['name'], 'sale', -$qty, $before, 'sale', $id,
            "Sold {$qty} unit(s) at £" . number_format($sellP, 2));

        ActivityLog::log('sold', 'sale', $id, $item['name'],
            "Sold {$qty} × {$item['name']} @ £" . number_format($sellP, 2));

        $this->redirect('sales', 'success', "Sale recorded — {$qty} × {$item['name']}.");
    }

    public function update(string $id): void
    {
        if (!can('inventory.*')) $this->redirect('sales', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $sale = $this->sales->findById((int)$id);
        if (!$sale) $this->redirect('sales', 'error', 'Sale not found.');

        $newQty  = (int)$this->input('quantity_sold', 1);
        $sellP   = (float)$this->input('selling_price', 0);
        $date    = $this->sanitize('sale_date') ?: date('Y-m-d');
        $notes   = $this->sanitize('notes');

        $errors = [];
        if ($newQty < 1)    $errors[] = 'Quantity must be at least 1.';
        if ($sellP <= 0)    $errors[] = 'Selling price must be greater than 0.';

        $oldQty = (int)$sale['quantity_sold'];
        $diff   = $newQty - $oldQty;

        if (!$errors && $diff > 0) {
            $item = $this->items->findWithCategory((int)$sale['item_id']);
            if (!$item || $diff > (int)$item['available']) {
                $errors[] = 'Not enough stock available for this quantity change.';
            }
        }

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('sales');
        }

        // Adjust stock if quantity changed
        if ($diff !== 0) {
            $item = $item ?? $this->items->findWithCategory((int)$sale['item_id']);
            $before = (int)($item['quantity'] ?? 0);
            $this->items->updateAssignedQty((int)$sale['item_id'], $diff);
            StockMovement::record(
                (int)$sale['item_id'], $sale['item_name'],
                'adjustment', -$diff, $before,
                'sale', (int)$id,
                "Sale #{$id} edited: qty {$oldQty} → {$newQty}"
            );
        }

        $this->sales->update((int)$id, [
            'quantity_sold'  => $newQty,
            'cost_price'     => (float)$sale['cost_price'],
            'selling_price'  => $sellP,
            'sale_date'      => $date,
            'notes'          => $notes,
        ]);

        ActivityLog::log('updated', 'sale', (int)$id, $sale['item_name'],
            "Edited sale — qty: {$newQty}, price: £" . number_format($sellP, 2));

        $this->redirect('sales', 'success', "Sale updated.");
    }

    public function delete(string $id): void
    {
        if (!has_role('Admin')) $this->redirect('sales', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $sale = $this->sales->findById((int)$id);
        if (!$sale) $this->redirect('sales', 'error', 'Sale not found.');

        // Restore stock
        $qty  = (int)$sale['quantity_sold'];
        $item = $this->items->findWithCategory((int)$sale['item_id']);
        if ($item) {
            $before = (int)$item['quantity'];
            $this->items->updateAssignedQty((int)$sale['item_id'], -$qty);
            StockMovement::record(
                (int)$sale['item_id'], $sale['item_name'],
                'adjustment', $qty, $before,
                'sale', (int)$id,
                "Sale #{$id} deleted — {$qty} unit(s) restored"
            );
        }

        $this->sales->delete((int)$id);
        ActivityLog::log('deleted', 'sale', (int)$id, $sale['item_name'],
            "Deleted sale of {$qty} × {$sale['item_name']}");

        $this->redirect('sales', 'success', "Sale deleted and stock restored.");
    }

    public function report(): void
    {
        $period = $this->sanitize('period') ?: 'today';

        $today = date('Y-m-d');
        $dates = match($period) {
            'today'     => [$today, $today],
            'week'      => [date('Y-m-d', strtotime('-7 days')),  $today],
            '2weeks'    => [date('Y-m-d', strtotime('-14 days')), $today],
            'month'     => [date('Y-m-d', strtotime('-30 days')), $today],
            'quarterly' => [date('Y-m-d', strtotime('-90 days')), $today],
            'yearly'    => [date('Y-m-01', strtotime('-11 months')), $today],
            default     => [$today, $today],
        };
        [$from, $to] = $dates;

        $summary   = $this->sales->summaryForPeriod($from, $to);
        $daily     = $this->sales->dailyBreakdown($from, $to);
        $topItems  = $this->sales->topItems($from, $to, 5);

        $expModel  = new \App\Models\Expense();
        $expenses  = $expModel->totalForPeriod($from, $to);
        $netProfit = (float)($summary['total_profit'] ?? 0) - $expenses;

        $this->render('sales.report', compact(
            'period', 'from', 'to', 'summary', 'daily', 'topItems', 'expenses', 'netProfit'
        ));
    }

    public function export(): void
    {
        $period = $this->sanitize('period') ?: 'month';
        $today  = date('Y-m-d');
        $dates  = match($period) {
            'today'     => [$today, $today],
            'week'      => [date('Y-m-d', strtotime('-7 days')),  $today],
            '2weeks'    => [date('Y-m-d', strtotime('-14 days')), $today],
            'month'     => [date('Y-m-d', strtotime('-30 days')), $today],
            'quarterly' => [date('Y-m-d', strtotime('-90 days')), $today],
            'yearly'    => [date('Y-m-01', strtotime('-11 months')), $today],
            default     => [date('Y-m-d', strtotime('-30 days')), $today],
        };
        [$from, $to] = $dates;

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="sales-report-' . $period . '-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Date','Item','Qty Sold','Cost Price (£)','Selling Price (£)','Total Cost (£)','Total Revenue (£)','Profit (£)','Sold By']);
        foreach ($this->sales->paginated(0, 99999, ['date_from' => $from, 'date_to' => $to]) as $s) {
            fputcsv($out, [
                $s['sale_date'], $s['item_name'], $s['quantity_sold'],
                number_format((float)$s['cost_price'], 2),
                number_format((float)$s['selling_price'], 2),
                number_format((float)$s['total_cost'], 2),
                number_format((float)$s['total_revenue'], 2),
                number_format((float)$s['profit'], 2),
                $s['sold_by_name'],
            ]);
        }
        fclose($out);
        exit;
    }
}
