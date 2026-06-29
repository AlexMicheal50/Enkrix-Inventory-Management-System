<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\InventoryItem;
use App\Models\Assignment;
use App\Models\ActivityLog;
use App\Models\Sale;
use App\Models\Expense;
use App\Config\Config;

class DashboardController extends BaseController
{
    public function index(): void
    {
        $items       = new InventoryItem();
        $assignments = new Assignment();
        $logs        = new ActivityLog();
        $saleModel   = new Sale();
        $expModel    = new Expense();

        $assignments->markOverdue();

        $stats        = $items->dashboardStats();
        $assignStats  = $assignments->stats();
        $lowStock     = $items->lowStockItems();
        $recentItems  = $items->recentItems(5);
        $recentActive = $assignments->recentActive(5);
        $activity     = $logs->recent(8);

        $totalRevenue  = $saleModel->totalRevenue();
        $totalProfit   = $saleModel->totalProfit();
        $totalExpenses = $expModel->totalAll();
        $netProfit     = $totalProfit - $totalExpenses;

        $this->render('dashboard.index', compact(
            'stats', 'assignStats', 'lowStock', 'recentItems', 'recentActive', 'activity',
            'totalRevenue', 'totalProfit', 'totalExpenses', 'netProfit'
        ));
    }

    public function activity(): void
    {
        $logModel = new ActivityLog();
        $page     = $this->currentPage();
        $perPage  = Config::get('pagination.per_page');
        $total    = $logModel->count();
        $pagination = paginate($total, $page, $perPage);
        $logs     = $logModel->paginated($pagination['offset'], $perPage);

        $this->render('dashboard.activity', compact('logs', 'pagination'));
    }
}
