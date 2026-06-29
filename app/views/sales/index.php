<?php
$title       = 'Sales';
$subtitle    = 'Track all sales transactions';
$headerAction = can('inventory.*') ? '
<button onclick="openModal(\'recordSaleModal\')" class="btn-gold text-xs flex items-center gap-1.5">
  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
  Record Sale
</button>' : '';
ob_start();
?>

<?php if (can('inventory.*')): ?>
<!-- ═══ RECORD SALE MODAL ═══ -->
<div class="modal-overlay" id="recordSaleModal">
  <div class="modal-box" style="max-width:560px;">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">Record Sale</h3>
      <button onclick="closeModal('recordSaleModal')" style="color:#888;" class="hover:text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" action="<?= url('sales/store') ?>" class="space-y-4" id="saleModalForm">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Item *</label>
        <select name="item_id" id="saleItemSel" class="form-input" required onchange="saleItemChanged(this)">
          <option value="">Choose item…</option>
          <?php foreach ($items as $item): ?>
            <?php if ((int)$item['available'] < 1) continue; ?>
            <option value="<?= (int)$item['id'] ?>"
              data-price="<?= (float)$item['selling_price'] ?>"
              data-cost="<?= (float)$item['cost'] ?>"
              data-available="<?= (int)$item['available'] ?>">
              <?= e($item['name']) ?> — <?= (int)$item['available'] ?> avail.
            </option>
          <?php endforeach; ?>
        </select>
        <p id="saleAvailHint" class="text-xs mt-1" style="color:#888;"></p>
      </div>
      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="form-label">Qty *</label>
          <input type="number" name="quantity_sold" id="saleQty" value="1" min="1" class="form-input" required oninput="saleCalc()">
        </div>
        <div>
          <label class="form-label">Sell Price (£) *</label>
          <input type="number" name="selling_price" id="saleSellP" min="0.01" step="0.01" class="form-input" required oninput="saleCalc()">
        </div>
        <div>
          <label class="form-label">Date *</label>
          <input type="date" name="sale_date" value="<?= date('Y-m-d') ?>" class="form-input" required>
        </div>
      </div>

      <!-- Live preview -->
      <div id="salePreview" class="hidden rounded-xl p-4 grid grid-cols-3 gap-3" style="background:rgba(212,168,83,0.06);border:1px solid rgba(212,168,83,0.15);">
        <div><p class="text-xs" style="color:#A0A0A0;">Revenue</p><p class="font-bold text-white text-sm" id="saleRevEl">—</p></div>
        <div><p class="text-xs" style="color:#A0A0A0;">Cost</p><p class="font-bold text-sm" style="color:#C0C0C0;" id="saleCostEl">—</p></div>
        <div><p class="text-xs" style="color:#A0A0A0;">Profit</p><p class="font-bold text-sm" id="saleProfEl">—</p></div>
      </div>

      <div>
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="2" class="form-input resize-none" placeholder="Optional…"></textarea>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Record Sale
        </button>
        <button type="button" onclick="closeModal('recordSaleModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- ═══ EDIT SALE MODAL ═══ -->
<?php if (can('inventory.*')): ?>
<div class="modal-overlay" id="editSaleModal">
  <div class="modal-box" style="max-width:500px;">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">Edit Sale</h3>
      <button onclick="closeModal('editSaleModal')" style="color:#888;" class="hover:text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <div id="editSaleItemName" class="mb-4 text-sm font-semibold" style="color:#D4A853;"></div>
    <form method="POST" id="editSaleForm" class="space-y-4">
      <?= csrf_field() ?>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="form-label">Quantity Sold *</label>
          <input type="number" name="quantity_sold" id="esSaleQty" min="1" class="form-input" required oninput="editSaleCalc()">
          <p id="esSaleQtyHint" class="text-xs mt-1" style="color:#888;"></p>
        </div>
        <div>
          <label class="form-label">Selling Price (£) *</label>
          <input type="number" name="selling_price" id="esSalePrice" min="0.01" step="0.01" class="form-input" required oninput="editSaleCalc()">
        </div>
      </div>
      <div>
        <label class="form-label">Sale Date *</label>
        <input type="date" name="sale_date" id="esSaleDate" class="form-input" required>
      </div>
      <!-- Live preview -->
      <div id="esSalePreview" class="hidden rounded-xl p-4 grid grid-cols-3 gap-3" style="background:rgba(212,168,83,0.06);border:1px solid rgba(212,168,83,0.15);">
        <div><p class="text-xs" style="color:#A0A0A0;">Revenue</p><p class="font-bold text-white text-sm" id="esRevEl">—</p></div>
        <div><p class="text-xs" style="color:#A0A0A0;">Cost</p><p class="font-bold text-sm" style="color:#C0C0C0;" id="esCostEl">—</p></div>
        <div><p class="text-xs" style="color:#A0A0A0;">Profit</p><p class="font-bold text-sm" id="esProfEl">—</p></div>
      </div>
      <div>
        <label class="form-label">Notes</label>
        <textarea name="notes" id="esSaleNotes" rows="2" class="form-input resize-none"></textarea>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Changes
        </button>
        <button type="button" onclick="closeModal('editSaleModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Summary strip -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
  <div class="stat-card p-4 flex items-center gap-4">
    <div class="icon-box" style="background:rgba(212,168,83,0.15);">
      <svg class="w-5 h-5" style="color:#D4A853;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
      <p class="text-xs" style="color:#A0A0A0;">Revenue (filtered)</p>
      <p class="text-xl font-bold text-white"><?= format_currency($totalRevenue) ?></p>
    </div>
  </div>
  <div class="stat-card p-4 flex items-center gap-4">
    <div class="icon-box" style="background:rgba(34,197,94,0.12);">
      <svg class="w-5 h-5" style="color:#22C55E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
    </div>
    <div>
      <p class="text-xs" style="color:#A0A0A0;">Profit (filtered)</p>
      <p class="text-xl font-bold <?= $totalProfit >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= format_currency($totalProfit) ?></p>
    </div>
  </div>
  <div class="stat-card p-4 flex items-center gap-4">
    <div class="icon-box" style="background:rgba(59,130,246,0.12);">
      <svg class="w-5 h-5" style="color:#3B82F6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <div>
      <p class="text-xs" style="color:#A0A0A0;">Units Sold (filtered)</p>
      <p class="text-xl font-bold text-white"><?= number_format($totalUnits) ?></p>
    </div>
  </div>
</div>

<!-- Filters + actions -->
<div class="card p-4 mb-5">
  <form method="GET" action="<?= url('sales') ?>" class="flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-36">
      <label class="form-label">Search Item</label>
      <input type="text" name="search" value="<?= e($filters['search']) ?>" class="form-input" placeholder="Item name…">
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
      <a href="<?= url('sales') ?>" class="btn-outline text-xs">Clear</a>
    </div>
  </form>
</div>

<div class="flex items-center justify-between mb-3 flex-wrap gap-2">
  <p class="text-sm" style="color:#C0C0C0;"><?= number_format($pagination['total']) ?> transaction<?= $pagination['total'] != 1 ? 's' : '' ?></p>
  <div class="flex gap-2">
    <a href="<?= url('sales/report') ?>" class="btn-outline text-xs flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Report
    </a>
    <a href="<?= url('sales/export?' . http_build_query(['date_from' => $filters['date_from'], 'date_to' => $filters['date_to']])) ?>" class="btn-outline text-xs flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Export CSV
    </a>
  </div>
</div>

<!-- Table -->
<div class="card">
  <div class="table-wrap">
    <table class="w-full text-sm">
      <thead>
        <tr style="border-bottom:1px solid rgba(212,168,83,0.12);">
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Date</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Item</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Qty</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Cost</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Sell</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Revenue</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Profit</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#D4A853;">Sold By</th>
          <th class="p-4"></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($sales)): ?>
        <tr><td colspan="9" class="text-center py-12 text-sm" style="color:#888;">
          No sales yet.
          <?php if (can('inventory.*')): ?>
            <button onclick="openModal('recordSaleModal')" style="color:#D4A853;background:none;border:none;cursor:pointer;">Record one →</button>
          <?php endif; ?>
        </td></tr>
        <?php else: ?>
        <?php foreach ($sales as $s):
          $sJson = htmlspecialchars(json_encode([
            'id'            => $s['id'],
            'item_name'     => $s['item_name'],
            'quantity_sold' => $s['quantity_sold'],
            'cost_price'    => $s['cost_price'],
            'selling_price' => $s['selling_price'],
            'sale_date'     => $s['sale_date'],
            'notes'         => $s['notes'],
          ]), ENT_QUOTES, 'UTF-8');
        ?>
        <tr class="table-row">
          <td class="p-4 whitespace-nowrap" style="color:#C8C8C8;"><?= e(date('d M Y', strtotime($s['sale_date']))) ?></td>
          <td class="p-4">
            <p class="font-medium text-white"><?= e($s['item_name']) ?></p>
            <?php if ($s['notes']): ?><p class="text-xs mt-0.5 truncate max-w-xs" style="color:#888;"><?= e($s['notes']) ?></p><?php endif; ?>
          </td>
          <td class="p-4 text-right font-semibold text-white"><?= number_format((int)$s['quantity_sold']) ?></td>
          <td class="p-4 text-right" style="color:#C0C0C0;"><?= format_currency((float)$s['cost_price']) ?></td>
          <td class="p-4 text-right" style="color:#C0C0C0;"><?= format_currency((float)$s['selling_price']) ?></td>
          <td class="p-4 text-right font-semibold" style="color:#D4A853;"><?= format_currency((float)$s['total_revenue']) ?></td>
          <td class="p-4 text-right font-semibold <?= (float)$s['profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= format_currency((float)$s['profit']) ?></td>
          <td class="p-4 hidden md:table-cell text-xs" style="color:#A0A0A0;"><?= e($s['sold_by_name']) ?></td>
          <td class="p-4">
            <div class="flex items-center justify-end gap-1">
              <?php if (can('inventory.*')): ?>
              <button onclick='openEditSale(<?= $sJson ?>)' class="p-1.5 rounded" title="Edit" style="color:#555;" onmouseover="this.style.color='#3B82F6'" onmouseout="this.style.color='#555'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </button>
              <?php endif; ?>
              <?php if (has_role('Admin')): ?>
              <form method="POST" action="<?= url('sales/' . $s['id'] . '/delete') ?>" onsubmit="return confirm('Delete this sale and restore stock?')">
                <?= csrf_field() ?>
                <button type="submit" class="p-1.5 rounded" title="Delete" style="color:#555;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#555'">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
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
let saleCostPerUnit = 0;
function saleItemChanged(sel) {
  const opt   = sel.options[sel.selectedIndex];
  saleCostPerUnit = parseFloat(opt.dataset.cost) || 0;
  const avail = opt.dataset.available || 0;
  document.getElementById('saleSellP').value = parseFloat(opt.dataset.price || 0).toFixed(2);
  document.getElementById('saleQty').max = avail;
  document.getElementById('saleAvailHint').textContent = sel.value ? avail + ' unit(s) available' : '';
  saleCalc();
}
function saleCalc() {
  const qty  = parseInt(document.getElementById('saleQty').value) || 0;
  const sell = parseFloat(document.getElementById('saleSellP').value) || 0;
  const prev = document.getElementById('salePreview');
  if (!qty || !sell) { prev.classList.add('hidden'); return; }
  const rev    = qty * sell;
  const cost   = qty * saleCostPerUnit;
  const profit = rev - cost;
  document.getElementById('saleRevEl').textContent  = '£' + rev.toFixed(2);
  document.getElementById('saleCostEl').textContent  = '£' + cost.toFixed(2);
  const profEl = document.getElementById('saleProfEl');
  profEl.textContent = (profit >= 0 ? '+' : '') + '£' + profit.toFixed(2);
  profEl.style.color = profit >= 0 ? '#4ADE80' : '#F87171';
  prev.classList.remove('hidden');
}

// Edit sale modal
let esCostPerUnit = 0;
function openEditSale(s) {
  document.getElementById('editSaleForm').action = '<?= url('sales/') ?>' + s.id + '/update';
  document.getElementById('editSaleItemName').textContent = s.item_name;
  document.getElementById('esSaleQty').value   = s.quantity_sold;
  document.getElementById('esSalePrice').value = parseFloat(s.selling_price).toFixed(2);
  document.getElementById('esSaleDate').value  = s.sale_date;
  document.getElementById('esSaleNotes').value = s.notes || '';
  esCostPerUnit = parseFloat(s.cost_price) || 0;
  document.getElementById('esSaleQtyHint').textContent = 'Current: ' + s.quantity_sold + ' unit(s)';
  editSaleCalc();
  openModal('editSaleModal');
}
function editSaleCalc() {
  const qty  = parseInt(document.getElementById('esSaleQty').value) || 0;
  const sell = parseFloat(document.getElementById('esSalePrice').value) || 0;
  const prev = document.getElementById('esSalePreview');
  if (!qty || !sell) { prev.classList.add('hidden'); return; }
  const rev    = qty * sell;
  const cost   = qty * esCostPerUnit;
  const profit = rev - cost;
  document.getElementById('esRevEl').textContent  = '£' + rev.toFixed(2);
  document.getElementById('esCostEl').textContent = '£' + cost.toFixed(2);
  const profEl = document.getElementById('esProfEl');
  profEl.textContent = (profit >= 0 ? '+' : '') + '£' + profit.toFixed(2);
  profEl.style.color = profit >= 0 ? '#4ADE80' : '#F87171';
  prev.classList.remove('hidden');
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
