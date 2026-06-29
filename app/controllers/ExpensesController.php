<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Expense;
use App\Models\ActivityLog;
use App\Config\Config;

class ExpensesController extends BaseController
{
    private Expense $expenses;

    public function __construct()
    {
        $this->expenses = new Expense();
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
        $total      = $this->expenses->countFiltered($filters);
        $pagination = paginate($total, $page, $perPage);
        $expenses   = $this->expenses->paginated($pagination['offset'], $perPage, $filters);
        $categories = $this->expenses->categories();
        $totalSpend = array_sum(array_column($expenses, 'amount'));

        $this->render('expenses.index', compact('expenses', 'filters', 'pagination', 'categories', 'totalSpend'));
    }

    public function store(): void
    {
        if (!can('inventory.*')) $this->redirect('expenses', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $title       = $this->sanitize('title');
        $category    = $this->sanitize('category') ?: 'General';
        $amount      = (float)$this->input('amount', 0);
        $date        = $this->sanitize('expense_date') ?: date('Y-m-d');
        $description = $this->sanitize('description');

        $errors = [];
        if (empty($title))   $errors[] = 'Title is required.';
        if ($amount <= 0)    $errors[] = 'Amount must be greater than 0.';

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('expenses');
        }

        $id = $this->expenses->create([
            'title'        => $title,
            'category'     => $category,
            'amount'       => $amount,
            'expense_date' => $date,
            'description'  => $description,
            'recorded_by'  => auth()['id'],
        ]);

        ActivityLog::log('created', 'expense', $id, $title,
            "Recorded expense: {$title} — £" . number_format($amount, 2));

        $this->redirect('expenses', 'success', "Expense \"{$title}\" recorded.");
    }

    public function update(string $id): void
    {
        if (!can('inventory.*')) $this->redirect('expenses', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $title       = $this->sanitize('title');
        $category    = $this->sanitize('category') ?: 'General';
        $amount      = (float)$this->input('amount', 0);
        $date        = $this->sanitize('expense_date') ?: date('Y-m-d');
        $description = $this->sanitize('description');

        $errors = [];
        if (empty($title)) $errors[] = 'Title is required.';
        if ($amount <= 0)  $errors[] = 'Amount must be greater than 0.';

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('expenses');
        }

        $this->expenses->update((int)$id, [
            'title'        => $title,
            'category'     => $category,
            'amount'       => $amount,
            'expense_date' => $date,
            'description'  => $description,
        ]);

        ActivityLog::log('updated', 'expense', (int)$id, $title, 'Expense updated');
        $this->redirect('expenses', 'success', "Expense \"{$title}\" updated.");
    }

    public function delete(string $id): void
    {
        if (!has_role('Admin')) $this->redirect('expenses', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $this->expenses->delete((int)$id);
        $this->redirect('expenses', 'success', 'Expense deleted.');
    }
}
