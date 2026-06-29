<?php
$title    = 'New Assignment';
$subtitle = 'Assign inventory items to a department or individual';
ob_start();
?>

<div class="max-w-2xl">
  <div class="card p-7">
    <form method="POST" action="<?= url('assignments/store') ?>" class="space-y-5">
      <?= csrf_field() ?>

      <div>
        <label class="form-label">Item *</label>
        <select name="item_id" id="itemSelect" class="form-input" required onchange="updateAvailable(this)">
          <option value="">Select an item…</option>
          <?php foreach ($items as $item): ?>
            <?php $avail = (int)$item['quantity'] - (int)$item['quantity_assigned']; ?>
            <?php if ($avail > 0): ?>
            <option value="<?= $item['id'] ?>"
              data-available="<?= $avail ?>"
              data-unit="<?= e($item['unit'] ?: 'pcs') ?>"
              <?= ($_GET['item_id'] ?? '') == $item['id'] ? 'selected' : '' ?>>
              <?= e($item['name']) ?> (<?= $avail ?> available)
            </option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <p id="availMsg" class="text-xs mt-1 hidden" style="color:#C8C8C8;"></p>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="form-label">Assignee Type *</label>
          <select name="assigned_to_type" class="form-input" required>
            <option value="department">Department</option>
            <option value="individual">Individual</option>
          </select>
        </div>
        <div>
          <label class="form-label">Quantity *</label>
          <input type="number" name="quantity_assigned" id="qtyInput" value="1" min="1" class="form-input" required>
        </div>
      </div>

      <div>
        <label class="form-label">Assigned To (Name) *</label>
        <input type="text" name="assigned_to_name" class="form-input" placeholder="e.g. Operations Team / Jane Smith" required>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="form-label">Assignment Date *</label>
          <input type="date" name="assignment_date" value="<?= date('Y-m-d') ?>" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Expected Return Date</label>
          <input type="date" name="expected_return_date" class="form-input">
        </div>
      </div>

      <div>
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="3" class="form-input resize-none" placeholder="Optional notes about this assignment…"></textarea>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/></svg>
          Create Assignment
        </button>
        <a href="<?= url('assignments') ?>" class="btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
function updateAvailable(sel) {
  const opt   = sel.options[sel.selectedIndex];
  const avail = opt.dataset.available;
  const unit  = opt.dataset.unit || 'pcs';
  const msg   = document.getElementById('availMsg');
  const qty   = document.getElementById('qtyInput');
  if (avail) {
    msg.textContent = `${avail} ${unit} available`;
    msg.classList.remove('hidden');
    qty.max = avail;
  } else {
    msg.classList.add('hidden');
    qty.removeAttribute('max');
  }
}
document.addEventListener('DOMContentLoaded', () => {
  const sel = document.getElementById('itemSelect');
  if (sel.value) updateAvailable(sel);
});
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
