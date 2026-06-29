<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\InventoryItem;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Models\StockMovement;
use App\Config\Config;

class InventoryController extends BaseController
{
    private InventoryItem $items;
    private Category $categories;

    public function __construct()
    {
        $this->items      = new InventoryItem();
        $this->categories = new Category();
    }

    public function index(): void
    {
        $page    = $this->currentPage();
        $perPage = Config::get('pagination.per_page');
        $filters = [
            'search'      => $this->sanitize('search'),
            'category_id' => (int)$this->input('category_id'),
            'condition'   => $this->sanitize('condition'),
        ];
        $total      = $this->items->countFiltered($filters);
        $pagination = paginate($total, $page, $perPage);
        $items      = $this->items->paginated($pagination['offset'], $perPage, $filters);
        $categories = $this->categories->all('name');

        $this->render('inventory.index', compact('items', 'categories', 'filters', 'pagination'));
    }

    public function create(): void
    {
        if (!can('inventory.*')) $this->redirect('inventory', 'error', 'Permission denied.');
        $categories = $this->categories->all('name');
        $this->render('inventory.create', compact('categories'));
    }

    public function store(): void
    {
        if (!can('inventory.*')) $this->redirect('inventory', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $data   = $this->collectFormData();
        $errors = $this->validate($data);

        if ($errors) {
            $_SESSION['_old'] = $data;
            flash('error', implode('<br>', $errors));
            redirect('inventory/create');
        }

        $data['image']      = $this->handleUpload();
        $data['created_by'] = auth()['id'];

        $id = $this->items->create($data);
        ActivityLog::log('created', 'inventory_item', $id, $data['name'], 'Item created');
        $this->redirect('inventory', 'success', "Item \"{$data['name']}\" added successfully.");
    }

    public function show(string $id): void
    {
        $item = $this->items->findWithCategory((int)$id);
        if (!$item) { http_response_code(404); die('Item not found.'); }
        $movements = (new StockMovement())->forItem((int)$id, 20);
        $this->render('inventory.show', compact('item', 'movements'));
    }

    public function edit(string $id): void
    {
        if (!can('inventory.*')) $this->redirect('inventory', 'error', 'Permission denied.');
        $item = $this->items->findWithCategory((int)$id);
        if (!$item) { http_response_code(404); die('Item not found.'); }
        $categories = $this->categories->all('name');
        $this->render('inventory.edit', compact('item', 'categories'));
    }

    public function update(string $id): void
    {
        if (!can('inventory.*')) $this->redirect('inventory', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $item = $this->items->findWithCategory((int)$id);
        if (!$item) { http_response_code(404); die('Item not found.'); }

        $data   = $this->collectFormData();
        $errors = $this->validate($data);

        if ($errors) {
            flash('error', implode('<br>', $errors));
            redirect("inventory/{$id}/edit");
        }

        $upload = $this->handleUpload();
        if ($upload) {
            if ($item['image'] && file_exists(BASE_PATH . '/public/uploads/' . $item['image'])) {
                unlink(BASE_PATH . '/public/uploads/' . $item['image']);
            }
            $data['image'] = $upload;
        } elseif ($this->input('remove_image')) {
            if ($item['image'] && file_exists(BASE_PATH . '/public/uploads/' . $item['image'])) {
                unlink(BASE_PATH . '/public/uploads/' . $item['image']);
            }
            $data['image'] = null;
        }

        $this->items->update((int)$id, $data);
        ActivityLog::log('updated', 'inventory_item', (int)$id, $data['name'], 'Item updated');
        $this->redirect('inventory', 'success', "Item \"{$data['name']}\" updated.");
    }

    public function delete(string $id): void
    {
        if (!has_role('Admin')) $this->redirect('inventory', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $item = $this->items->findById((int)$id);
        if (!$item) { http_response_code(404); die('Item not found.'); }

        if ($item['quantity_assigned'] > 0) {
            $this->redirect('inventory', 'error', 'Cannot delete item with active assignments.');
        }

        if ($item['image'] && file_exists(BASE_PATH . '/public/uploads/' . $item['image'])) {
            unlink(BASE_PATH . '/public/uploads/' . $item['image']);
        }

        $this->items->delete((int)$id);
        ActivityLog::log('deleted', 'inventory_item', (int)$id, $item['name'], 'Item deleted');
        $this->redirect('inventory', 'success', "Item \"{$item['name']}\" deleted.");
    }

    private function collectFormData(): array
    {
        return [
            'name'                => $this->sanitize('name'),
            'category_id'         => (int)$this->input('category_id'),
            'description'         => $this->sanitize('description'),
            'quantity'            => (int)$this->input('quantity', 0),
            'unit'                => $this->sanitize('unit'),
            'condition_status'    => $this->sanitize('condition_status'),
            'location'            => $this->sanitize('location'),
            'purchase_date'       => $this->sanitize('purchase_date'),
            'cost'                => (float)$this->input('cost', 0),
            'selling_price'       => (float)$this->input('selling_price', 0),
            'low_stock_threshold' => (int)$this->input('low_stock_threshold', 5),
            'barcode'             => $this->sanitize('barcode'),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (empty($data['name']))           $errors[] = 'Item name is required.';
        if (empty($data['category_id']))    $errors[] = 'Category is required.';
        if ($data['quantity'] < 0)          $errors[] = 'Quantity must be 0 or more.';
        if (!in_array($data['condition_status'], ['New','Good','Fair','Damaged'])) {
            $errors[] = 'Invalid condition.';
        }
        return $errors;
    }

    private function handleUpload(): ?string
    {
        if (empty($_FILES['image']['tmp_name'])) return null;

        $file    = $_FILES['image'];
        $maxSize = Config::get('upload.max_size');
        $allowed = Config::get('upload.allowed');

        if ($file['error'] !== UPLOAD_ERR_OK)           return null;
        if ($file['size'] > $maxSize)                   return null;
        if (!in_array(mime_content_type($file['tmp_name']), $allowed)) return null;

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . strtolower($ext);
        $dest     = Config::get('upload.path') . $filename;

        if (!is_dir(Config::get('upload.path'))) {
            mkdir(Config::get('upload.path'), 0755, true);
        }

        move_uploaded_file($file['tmp_name'], $dest);
        return $filename;
    }
}
