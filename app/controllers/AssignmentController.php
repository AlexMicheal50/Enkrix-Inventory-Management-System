<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Assignment;
use App\Models\InventoryItem;
use App\Models\ActivityLog;
use App\Config\Config;

class AssignmentController extends BaseController
{
    private Assignment $assignments;
    private InventoryItem $items;

    public function __construct()
    {
        $this->assignments = new Assignment();
        $this->items       = new InventoryItem();
    }

    public function index(): void
    {
        $this->assignments->markOverdue();

        $page    = $this->currentPage();
        $perPage = Config::get('pagination.per_page');
        $filters = [
            'status' => $this->sanitize('status'),
            'search' => $this->sanitize('search'),
        ];
        $total      = $this->assignments->countFiltered($filters);
        $pagination = paginate($total, $page, $perPage);
        $assignments = $this->assignments->paginated($pagination['offset'], $perPage, $filters);
        $stats       = $this->assignments->stats();
        $items       = $this->items->paginated(0, 1000, []);

        $this->render('assignments.index', compact('assignments', 'filters', 'pagination', 'stats', 'items'));
    }

    public function create(): void
    {
        if (!can('assignments.*')) $this->redirect('assignments', 'error', 'Permission denied.');
        $items = $this->items->paginated(0, 1000, []);
        $this->render('assignments.create', compact('items'));
    }

    public function store(): void
    {
        if (!can('assignments.*')) $this->redirect('assignments', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $itemId   = (int)$this->input('item_id');
        $qtyReq   = (int)$this->input('quantity_assigned', 1);
        $type     = $this->sanitize('assigned_to_type');
        $toName   = $this->sanitize('assigned_to_name');
        $date     = $this->sanitize('assignment_date') ?: date('Y-m-d');
        $retDate  = $this->sanitize('expected_return_date');
        $notes    = $this->sanitize('notes');

        $errors = [];
        if (!$itemId)    $errors[] = 'Item is required.';
        if ($qtyReq < 1) $errors[] = 'Quantity must be at least 1.';
        if (empty($toName)) $errors[] = 'Assignee name is required.';
        if (!in_array($type, ['department','individual'])) $errors[] = 'Invalid assignee type.';

        if (!$errors) {
            $item = $this->items->findWithCategory($itemId);
            if (!$item) {
                $errors[] = 'Item not found.';
            } elseif ($qtyReq > (int)$item['available']) {
                $errors[] = "Only {$item['available']} units available. Cannot assign {$qtyReq}.";
            }
        }

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect('assignments');
        }

        $id = $this->assignments->create([
            'item_id'              => $itemId,
            'assigned_to_type'     => $type,
            'assigned_to_name'     => $toName,
            'quantity_assigned'    => $qtyReq,
            'assigned_by'          => auth()['id'],
            'assignment_date'      => $date,
            'expected_return_date' => $retDate,
            'notes'                => $notes,
        ]);

        $this->items->updateAssignedQty($itemId, $qtyReq);
        ActivityLog::log('assigned', 'assignment', $id, $item['name'],
            "Assigned {$qtyReq} unit(s) to {$toName}");
        $this->redirect('assignments', 'success', "Assignment created successfully.");
    }

    public function return(string $id): void
    {
        if (!can('assignments.*')) $this->redirect('assignments', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $assignment = $this->assignments->findById((int)$id);
        if (!$assignment || $assignment['status'] === 'returned') {
            $this->redirect('assignments', 'error', 'Assignment not found or already returned.');
        }

        $this->assignments->returnItem((int)$id);
        $this->items->updateAssignedQty((int)$assignment['item_id'], -(int)$assignment['quantity_assigned']);

        $item = $this->items->findById((int)$assignment['item_id']);
        ActivityLog::log('returned', 'assignment', (int)$id, $item['name'] ?? '',
            "Returned {$assignment['quantity_assigned']} unit(s) from {$assignment['assigned_to_name']}");
        $this->redirect('assignments', 'success', 'Item returned successfully.');
    }
}
