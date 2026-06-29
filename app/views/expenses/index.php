<?php
$title    = 'Expenses';
$subtitle = 'Track business expenditure';
ob_start();
?>

<!-- Add Expense Modal trigger -->
<?php if (can('inventory.*')): ?>
<div id="addModal" class="modal-overlay">
  <div class="modal-box">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">Record Expense</h3>
      <button onclick="closeModal('addModal')" style="color:#888;" class="hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" action="<?= url('expenses/store') ?>" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Title *</label>
        <input type="text" name="title" class="form-input" placeholder="e.g. Office Supplies" required>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="form-label">Category</label>
          <input type="text" name="category" class="form-input" list="catList" placeholder="General">
          <datalist id="catList">
            <?php foreach ($categories as $c): ?>
              <option value="<?= e($c) ?>">
            <?php endforeach; ?>
            <option value="Utilities"><option value="Rent"><option value="Staff"><option value="Marketing"><option value="Equipment"><option value="Transport">
          </datalist>
        </div>
        <div>
          <label class="form-label">Amount (£) *</label>
          <input type="number" name="amount" min="0.01" step="0.01" class="form-input" placeholder="0.00" required>
        </div>
      </div>
      <div>
        <label class="form-label">Date *</label>
        <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Description (optional)</label>
        <textarea name="description" rows="2" class="form-input resize-none" placeholder="Details…"></textarea>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Save Expense
        </button>
        <button type="button" onclick="closeModal('addModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Edit Modal (shared, populated by JS) -->
<div id="editModal" class="modal-overlay">
  <div class="modal-box">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">Edit Expense</h3>
      <button onclick="closeModal('editModal')" style="color:#888;" class="hover:text-white transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form method="POST" id="editForm" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Title *</label>
        <input type="text" name="title" id="editTitle" class="form-input" required>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="form-label">Category</label>
          <input type="text" name="category" id="editCategory" class="form-input" list="catList">
        </div>
        <div>
          <label class="form-label">Amount (£) *</label>
          <input type="number" name="amount" id="editAmount" min="0.01" step="0.01" class="form-input" required>
        </div>
      </div>
      <div>
        <label class="form-label">Date *</label>
        <input type="date" name="expense_date" id="editDate" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Description</label>
        <textarea name="description" id="editDesc" rows="2" class="form-input resize-none"></textarea>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold">Save Changes</button>
        <button type="button" onclick="closeModal('editModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Top bar -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
  <div class="stat-card p-4 flex items-center gap-4 flex-1 min-w-48">
    <div class="icon-box" style="background:rgba(239,68,68,0.12);">
      <svg class="w-5 h-5" style="color:#F87171;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </div>
    <div>
      <p class="text-xs" style="color:#A0A0A0;">Total (filtered page)</p>
      <p class="text-xl font-bold text-red-400"><?= format_currency($totalSpend) ?></p>
    </div>
  </div>
  <?php if (can('inventory.*')): ?>
  <button onclick="openModal('addModal')" class="btn-gold flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Expense
  </button>
  <?php endif; ?>
</div>

<!-- Filters -->
<div class="card p-4 mb-5">
  <form method="GET" action="<?= url('expenses') ?>" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-36">
      <label class="form-label">Search</label>
      <input type="text" name="search" value="<?= e($filters['search']) ?>" class="form-input" placeholder="Title or category…">
    </div>
    <div>
      <label class="form-label">From</label>
      <input type="date" name="date_from" value="<?= e($filters['date_from']) ?>" class="form-input">
    </div>
    <div>
      <label class="form-label">To</label>
      <input type="date" name="date_to" value="<?= e($filters['date_to']) ?>" class="form-input">
    </div>
    <div class="flex gap-2">
      <button type="submit" class="btn-gold text-xs">Filter</button>
      <a href="<?= url('expenses') ?>" class="btn-outline text-xs">Clear</a>
    </div>
  </form>
</div>

<!-- Table -->
<div class="card">
  <div class="table-wrap">
    <table class="w-full text-sm">
      <thead>
        <tr style="border-bottom:1px solid rgba(212,168,83,0.12);">
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Date</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Title</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#D4A853;">Category</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Amount</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#D4A853;">Recorded By</th>
          <th class="p-4"></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($expenses)): ?>
        <tr><td colspan="6" class="text-center py-12 text-sm" style="color:#888;">No expenses yet. <button onclick="openModal('addModal')" style="color:#D4A853;background:none;border:none;cursor:pointer;">Add one →</button></td></tr>
        <?php else: ?>
        <?php foreach ($expenses as $exp): ?>
        <tr class="table-row">
          <td class="p-4 whitespace-nowrap" style="color:#C8C8C8;"><?= e(date('d M Y', strtotime($exp['expense_date']))) ?></td>
          <td class="p-4">
            <p class="font-medium text-white"><?= e($exp['title']) ?></p>
            <?php if ($exp['description']): ?><p class="text-xs mt-0.5 truncate max-w-xs" style="color:#888;"><?= e($exp['description']) ?></p><?php endif; ?>
          </td>
          <td class="p-4 hidden sm:table-cell">
            <span class="badge" style="background:rgba(212,168,83,0.1);color:#D4A853;border:1px solid rgba(212,168,83,0.2);"><?= e($exp['category']) ?></span>
          </td>
          <td class="p-4 text-right font-semibold text-red-400"><?= format_currency((float)$exp['amount']) ?></td>
          <td class="p-4 hidden md:table-cell text-xs" style="color:#A0A0A0;"><?= e($exp['recorded_by_name']) ?></td>
          <td class="p-4 text-right">
            <div class="flex items-center justify-end gap-2">
              <?php if (can('inventory.*')): ?>
              <button onclick='openEdit(<?= json_encode($exp) ?>)' class="btn-outline text-xs px-3 py-1.5">Edit</button>
              <?php endif; ?>
              <?php if (has_role('Admin')): ?>
              <form method="POST" action="<?= url('expenses/' . $exp['id'] . '/delete') ?>" onsubmit="return confirm('Delete this expense?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn-danger text-xs">Delete</button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pagination['total_pages'] > 1): ?>
  <div class="flex items-center justify-between p-4 border-t" style="border-color:rgba(212,168,83,0.08);">
    <p class="text-xs" style="color:#A0A0A0;">Page <?= $pagination['current'] ?> of <?= $pagination['total_pages'] ?></p>
    <div class="flex gap-2">
      <?php if ($pagination['current'] > 1): ?>
        <a href="?page=<?= $pagination['current'] - 1 ?>&search=<?= urlencode($filters['search']) ?>&date_from=<?= urlencode($filters['date_from']) ?>&date_to=<?= urlencode($filters['date_to']) ?>" class="btn-outline text-xs">← Prev</a>
      <?php endif; ?>
      <?php if ($pagination['current'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?>&search=<?= urlencode($filters['search']) ?>&date_from=<?= urlencode($filters['date_from']) ?>&date_to=<?= urlencode($filters['date_to']) ?>" class="btn-outline text-xs">Next →</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
function openEdit(exp) {
  document.getElementById('editForm').action = '<?= url('expenses/') ?>' + exp.id + '/update';
  document.getElementById('editTitle').value    = exp.title;
  document.getElementById('editCategory').value = exp.category;
  document.getElementById('editAmount').value   = parseFloat(exp.amount).toFixed(2);
  document.getElementById('editDate').value     = exp.expense_date;
  document.getElementById('editDesc').value     = exp.description || '';
  openModal('editModal');
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
