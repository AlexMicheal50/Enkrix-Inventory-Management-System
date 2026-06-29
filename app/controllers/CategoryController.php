<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;
use App\Models\ActivityLog;

class CategoryController extends BaseController
{
    private Category $categories;

    public function __construct()
    {
        $this->categories = new Category();
    }

    public function index(): void
    {
        $categories = $this->categories->allWithCount();
        $this->render('categories.index', compact('categories'));
    }

    public function store(): void
    {
        if (!can('categories.*')) $this->redirect('categories', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $name = $this->sanitize('name');
        $desc = $this->sanitize('description');
        $color = $this->sanitize('color') ?: '#D4A853';

        if (empty($name)) {
            flash('error', 'Category name is required.');
            redirect('categories');
        }

        $id = $this->categories->create([
            'name'        => $name,
            'description' => $desc,
            'color'       => $color,
            'created_by'  => auth()['id'],
        ]);

        ActivityLog::log('created', 'category', $id, $name, 'Category created');
        $this->redirect('categories', 'success', "Category \"{$name}\" created.");
    }

    public function update(string $id): void
    {
        if (!can('categories.*')) $this->redirect('categories', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $name  = $this->sanitize('name');
        $desc  = $this->sanitize('description');
        $color = $this->sanitize('color') ?: '#D4A853';

        if (empty($name)) {
            flash('error', 'Category name is required.');
            redirect('categories');
        }

        $this->categories->update((int)$id, [
            'name' => $name, 'description' => $desc, 'color' => $color
        ]);

        ActivityLog::log('updated', 'category', (int)$id, $name, 'Category updated');
        $this->redirect('categories', 'success', "Category \"{$name}\" updated.");
    }

    public function delete(string $id): void
    {
        if (!has_role('Admin')) $this->redirect('categories', 'error', 'Permission denied.');
        $this->verifyCsrf();

        $cat = $this->categories->findById((int)$id);
        if (!$cat) { http_response_code(404); die('Category not found.'); }

        if (!$this->categories->canDelete((int)$id)) {
            $this->redirect('categories', 'error', 'Cannot delete category with assigned items.');
        }

        $this->categories->delete((int)$id);
        ActivityLog::log('deleted', 'category', (int)$id, $cat['name'], 'Category deleted');
        $this->redirect('categories', 'success', "Category \"{$cat['name']}\" deleted.");
    }
}
