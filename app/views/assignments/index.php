<?php
$title    = 'Assignments';
$subtitle = 'Track item assignments and returns';
ob_start();
?>

<?php if (can('assignments.*')): ?>
<!-- ═══ NEW ASSIGNMENT MODAL ═══ -->
<div class="modal-overlay" id="assignModal">
  <div class="modal-box" style="max-width:560px;">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">New Assignment</h3>
      <button onclick="closeModal('assignModal')" style="color:#888;" class="hover:text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" action="<?= url('assignments/store') ?>" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Item *</label>
        <select name="item_id" id="assignItemSelect" class="form-input" required onchange="updateAssignAvail(this)">
          <option value="">Select an item…</option>
          <?php foreach ($items as $item): ?>
            <?php $avail = (int)$item['available']; ?>
            <?php if ($avail > 0): ?>
            <option value="<?= $item['id'] ?>" data-available="<?= $avail ?>" data-unit="<?= e($item['unit'] ?: 'pcs') ?>">
              <?= e($item['name']) ?> (<?= $avail ?> available)
            </option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <p id="assignAvailMsg" class="text-xs mt-1" style="color:#888;"></p>
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
          <input type="number" name="quantity_assigned" id="assignQtyInput" value="1" min="1" class="form-input" required>
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
          <label class="form-label">Expected Return</label>
          <input type="date" name="expected_return_date" class="form-input">
        </div>
      </div>
      <div>
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="2" class="form-input resize-none" placeholder="Optional notes…"></textarea>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/></svg>
          Create Assignment
        </button>
        <button type="button" onclick="closeModal('assignModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Stats Strip -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <?php
  $stripStats = [
    ['label' => 'Total',    'value' => $stats['total']    ?? 0, 'color' => '#D4A853'],
    ['label' => 'Active',   'value' => $stats['active']   ?? 0, 'color' => '#22C55E'],
    ['label' => 'Returned', 'value' => $stats['returned'] ?? 0, 'color' => '#3B82F6'],
    ['label' => 'Overdue',  'value' => $stats['overdue']  ?? 0, 'color' => '#EF4444'],
  ];
  foreach ($stripStats as $s): ?>
  <div class="card px-4 py-3 flex items-center gap-3">
    <div class="w-2 h-8 rounded-full flex-shrink-0" style="background:<?= $s['color'] ?>;"></div>
    <div>
      <p class="text-lg font-bold text-white"><?= number_format((int)$s['value']) ?></p>
      <p class="text-xs" style="color:#A0A0A0;"><?= $s['label'] ?></p>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Toolbar -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
  <form method="GET" action="<?= url('assignments') ?>" class="flex flex-wrap items-center gap-3">
    <div class="relative">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:#A0A0A0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Search…" class="form-input pl-9 py-2 w-48 text-sm">
    </div>
    <select name="status" class="form-input py-2 w-36 text-sm">
      <option value="">All Status</option>
      <?php foreach (['active','returned','overdue'] as $s): ?>
        <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-outline py-2 px-4 text-sm">Filter</button>
    <?php if (!empty($filters['status']) || !empty($filters['search'])): ?>
      <a href="<?= url('assignments') ?>" class="text-xs" style="color:#EF4444;">Clear ×</a>
    <?php endif; ?>
  </form>
  <?php if (can('assignments.*')): ?>
    <button onclick="openModal('assignModal')" class="btn-gold flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      New Assignment
    </button>
  <?php endif; ?>
</div>

<!-- Table -->
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#0D0D0D;">
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Item</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Assigned To</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Qty</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Date</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Due Return</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Status</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#A0A0A0;">By</th>
          <?php if (can('assignments.*')): ?>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Actions</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($assignments)): ?>
        <tr>
          <td colspan="8" class="px-5 py-16 text-center">
            <svg class="w-10 h-10 mx-auto mb-3" style="color:#2A2A2A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            <p class="text-sm" style="color:#B0B0B0;">No assignments found</p>
          </td>
        </tr>
        <?php else: ?>
        <?php foreach ($assignments as $a): ?>
        <tr class="table-row">
          <td class="px-5 py-4">
            <p class="font-medium text-white"><?= e($a['item_name']) ?></p>
          </td>
          <td class="px-5 py-4">
            <p class="text-white font-medium"><?= e($a['assigned_to_name']) ?></p>
            <p class="text-xs mt-0.5" style="color:#A0A0A0;"><?= e(ucfirst($a['assigned_to_type'])) ?></p>
          </td>
          <td class="px-5 py-4 text-right font-bold text-white"><?= (int)$a['quantity_assigned'] ?></td>
          <td class="px-5 py-4 text-xs" style="color:#C8C8C8;"><?= format_date($a['assignment_date']) ?></td>
          <td class="px-5 py-4 text-xs <?= $a['status'] === 'overdue' ? 'text-red-400 font-medium' : '' ?>" style="<?= $a['status'] !== 'overdue' ? 'color:#888;' : '' ?>">
            <?= $a['expected_return_date'] ? format_date($a['expected_return_date']) : '—' ?>
          </td>
          <td class="px-5 py-4"><?= status_badge($a['status']) ?></td>
          <td class="px-5 py-4 text-xs hidden md:table-cell" style="color:#C0C0C0;"><?= e($a['assigned_by_name']) ?></td>
          <?php if (can('assignments.*')): ?>
          <td class="px-5 py-4 text-right">
            <?php if ($a['status'] !== 'returned'): ?>
            <form method="POST" action="<?= url('assignments/' . $a['id'] . '/return') ?>" onsubmit="return confirm('Mark this item as returned?')">
              <?= csrf_field() ?>
              <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors" style="background:rgba(34,197,94,0.12);color:#22C55E;border:1px solid rgba(34,197,94,0.25);" onmouseover="this.style.background='rgba(34,197,94,0.2)'" onmouseout="this.style.background='rgba(34,197,94,0.12)'">
                Mark Returned
              </button>
            </form>
            <?php else: ?>
              <span class="text-xs" style="color:#909090;">Completed</span>
            <?php endif; ?>
          </td>
          <?php endif; ?>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pagination['total_pages'] > 1): ?>
  <div class="px-5 py-4 flex items-center justify-between border-t" style="border-color:rgba(212,168,83,0.08);">
    <p class="text-xs" style="color:#A0A0A0;">Page <?= $pagination['current'] ?> of <?= $pagination['total_pages'] ?></p>
    <div class="flex gap-2">
      <?php if ($pagination['has_prev']): ?>
        <a href="?page=<?= $pagination['current'] - 1 ?>&<?= http_build_query(array_filter($filters)) ?>" class="btn-outline py-1.5 px-3 text-xs">← Prev</a>
      <?php endif; ?>
      <?php if ($pagination['has_next']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?>&<?= http_build_query(array_filter($filters)) ?>" class="btn-outline py-1.5 px-3 text-xs">Next →</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
function updateAssignAvail(sel) {
  const opt   = sel.options[sel.selectedIndex];
  const avail = opt.dataset.available;
  const unit  = opt.dataset.unit || 'pcs';
  const msg   = document.getElementById('assignAvailMsg');
  const qty   = document.getElementById('assignQtyInput');
  if (avail) {
    msg.textContent = avail + ' ' + unit + ' available';
    qty.max = avail;
  } else {
    msg.textContent = '';
    qty.removeAttribute('max');
  }
}

// Auto-open modal and pre-select item if ?open=assign&item_id=X
document.addEventListener('DOMContentLoaded', function() {
  const params = new URLSearchParams(window.location.search);
  if (params.get('open') === 'assign') {
    const itemId = params.get('item_id');
    const sel = document.getElementById('assignItemSelect');
    if (sel && itemId) {
      sel.value = itemId;
      updateAssignAvail(sel);
    }
    openModal('assignModal');
    // Clean the URL without reloading
    history.replaceState(null, '', window.location.pathname);
  }
});
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
