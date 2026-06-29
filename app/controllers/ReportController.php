<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\InventoryItem;
use App\Models\Assignment;
use App\Models\Category;

class ReportController extends BaseController
{
    public function index(): void
    {
        $items       = new InventoryItem();
        $assignments = new Assignment();
        $categories  = new Category();

        $type        = $this->sanitize('type') ?: 'stock';
        $allItems    = $items->allForReport();
        $lowStock    = $items->lowStockItems();
        $allAssign   = $assignments->allForReport();
        $stats       = $items->dashboardStats();
        $cats        = $categories->allWithCount();

        $this->render('reports.index', compact(
            'type', 'allItems', 'lowStock', 'allAssign', 'stats', 'cats'
        ));
    }

    public function export(): void
    {
        $type        = $this->sanitize('type') ?: 'stock';
        $items       = new InventoryItem();
        $assignments = new Assignment();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="enkrix-report-' . $type . '-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');

        if ($type === 'assignment') {
            fputcsv($out, ['#', 'Item', 'Category', 'Type', 'Assigned To', 'Qty', 'Assigned By', 'Date', 'Expected Return', 'Status']);
            foreach ($assignments->allForReport() as $i => $a) {
                fputcsv($out, [
                    $i + 1,
                    $a['item_name'],
                    $a['category_name'],
                    ucfirst($a['assigned_to_type']),
                    $a['assigned_to_name'],
                    $a['quantity_assigned'],
                    $a['assigned_by_name'],
                    format_date($a['assignment_date']),
                    format_date($a['expected_return_date']),
                    ucfirst($a['status']),
                ]);
            }
        } elseif ($type === 'low_stock') {
            fputcsv($out, ['#', 'Item', 'Category', 'Qty Total', 'Assigned', 'Available', 'Threshold', 'Location', 'Condition']);
            foreach ($items->lowStockItems() as $i => $item) {
                fputcsv($out, [
                    $i + 1,
                    $item['name'],
                    $item['category_name'],
                    $item['quantity'],
                    $item['quantity_assigned'],
                    $item['available'],
                    $item['low_stock_threshold'],
                    $item['location'],
                    $item['condition_status'],
                ]);
            }
        } else {
            fputcsv($out, ['#', 'Item', 'Category', 'Qty', 'Assigned', 'Available', 'Condition', 'Location', 'Unit Cost (£)', 'Total Value (£)', 'Purchase Date']);
            foreach ($items->allForReport() as $i => $item) {
                fputcsv($out, [
                    $i + 1,
                    $item['name'],
                    $item['category_name'],
                    $item['quantity'],
                    $item['quantity_assigned'],
                    $item['available'],
                    $item['condition_status'],
                    $item['location'],
                    number_format((float)$item['cost'], 2),
                    number_format((float)$item['cost'] * (int)$item['quantity'], 2),
                    format_date($item['purchase_date']),
                ]);
            }
        }

        fclose($out);
        exit;
    }
}
