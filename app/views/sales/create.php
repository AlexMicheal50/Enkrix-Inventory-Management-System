<?php
$title    = 'Record Sale';
$subtitle = 'Select an item and enter sale details';
ob_start();
?>

<div class="max-w-2xl">
  <div class="card p-7">
    <form method="POST" action="<?= url('sales/store') ?>" class="space-y-6" id="saleForm">
      <?= csrf_field() ?>

      <!-- Item selection -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Item</h3>
        <div>
          <label class="form-label">Select Inventory Item *</label>
          <select name="item_id" id="itemSelect" class="form-input" required onchange="fillPrice(this)">
            <option value="">Choose item…</option>
            <?php foreach ($items as $item): ?>
              <?php if ((int)$item['available'] < 1) continue; ?>
              <option value="<?= (int)$item['id'] ?>"
                data-price="<?= (float)$item['selling_price'] ?>"
                data-cost="<?= (float)$item['cost'] ?>"
                data-available="<?= (int)$item['available'] ?>">
                <?= e($item['name']) ?> — <?= (int)$item['available'] ?> available @ <?= format_currency((float)$item['selling_price']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <p id="availableHint" class="text-xs mt-1" style="color:#888;"></p>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <!-- Sale details -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Sale Details</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div>
            <label class="form-label">Quantity Sold *</label>
            <input type="number" name="quantity_sold" id="qtySold" value="1" min="1" class="form-input" required oninput="calcTotal()">
          </div>
          <div>
            <label class="form-label">Selling Price (£) *</label>
            <input type="number" name="selling_price" id="sellPrice" value="" min="0.01" step="0.01" class="form-input" required oninput="calcTotal()">
          </div>
          <div>
            <label class="form-label">Sale Date *</label>
            <input type="date" name="sale_date" value="<?= date('Y-m-d') ?>" class="form-input" required>
          </div>
        </div>
      </div>

      <!-- Live preview -->
      <div id="preview" class="hidden rounded-xl p-4 space-y-2" style="background:rgba(212,168,83,0.06);border:1px solid rgba(212,168,83,0.15);">
        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Sale Preview</p>
        <div class="grid grid-cols-3 gap-3 text-sm">
          <div><p class="text-xs" style="color:#A0A0A0;">Total Revenue</p><p class="font-bold text-white" id="previewRevenue">—</p></div>
          <div><p class="text-xs" style="color:#A0A0A0;">Total Cost</p><p class="font-bold" style="color:#C0C0C0;" id="previewCost">—</p></div>
          <div><p class="text-xs" style="color:#A0A0A0;">Profit</p><p class="font-bold" id="previewProfit">—</p></div>
        </div>
      </div>

      <div>
        <label class="form-label">Notes (optional)</label>
        <textarea name="notes" rows="2" class="form-input resize-none" placeholder="Any additional notes…"></textarea>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Record Sale
        </button>
        <a href="<?= url('sales') ?>" class="btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
let currentCost = 0;

function fillPrice(sel) {
  const opt = sel.options[sel.selectedIndex];
  const price    = parseFloat(opt.dataset.price) || 0;
  const cost     = parseFloat(opt.dataset.cost)  || 0;
  const avail    = parseInt(opt.dataset.available) || 0;
  currentCost = cost;
  document.getElementById('sellPrice').value = price > 0 ? price.toFixed(2) : '';
  document.getElementById('qtySold').max = avail;
  const hint = document.getElementById('availableHint');
  hint.textContent = sel.value ? avail + ' unit(s) available' : '';
  calcTotal();
}

function calcTotal() {
  const qty   = parseInt(document.getElementById('qtySold').value) || 0;
  const sell  = parseFloat(document.getElementById('sellPrice').value) || 0;
  if (!qty || !sell) { document.getElementById('preview').classList.add('hidden'); return; }
  const revenue = qty * sell;
  const cost    = qty * currentCost;
  const profit  = revenue - cost;
  document.getElementById('previewRevenue').textContent = '£' + revenue.toFixed(2);
  document.getElementById('previewCost').textContent    = '£' + cost.toFixed(2);
  const profitEl = document.getElementById('previewProfit');
  profitEl.textContent = (profit >= 0 ? '+' : '') + '£' + profit.toFixed(2);
  profitEl.style.color = profit >= 0 ? '#4ADE80' : '#F87171';
  document.getElementById('preview').classList.remove('hidden');
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
