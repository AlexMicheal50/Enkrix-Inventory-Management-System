<?php
$title    = 'Inventory';
$subtitle = 'Manage and track all physical assets';
ob_start();
?>

<?php if (can('inventory.*')): ?>
<!-- ═══ ADD ITEM MODAL ═══ -->
<div class="modal-overlay" id="addItemModal">
  <div class="modal-box" style="max-width:680px;">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">Add Inventory Item</h3>
      <button onclick="closeModal('addItemModal')" style="color:#888;" class="hover:text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" action="<?= url('inventory/store') ?>" enctype="multipart/form-data" class="space-y-5">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
          <label class="form-label">Item Name *</label>
          <input type="text" name="name" class="form-input" placeholder="e.g. MacBook Pro 14-inch" required>
        </div>
        <div>
          <label class="form-label">Category *</label>
          <select name="category_id" class="form-input" required>
            <option value="">Select category…</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label">Condition *</label>
          <select name="condition_status" class="form-input" required>
            <?php foreach (['New','Good','Fair','Damaged'] as $c): ?>
              <option value="<?= $c ?>" <?= $c === 'Good' ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="sm:col-span-2">
          <label class="form-label">Description</label>
          <textarea name="description" rows="2" class="form-input resize-none" placeholder="Optional…"></textarea>
        </div>
        <div>
          <label class="form-label">Quantity *</label>
          <input type="number" name="quantity" value="0" min="0" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Unit</label>
          <input type="text" name="unit" value="pcs" class="form-input" placeholder="pcs">
        </div>
        <div>
          <label class="form-label">Unit Cost (£)</label>
          <input type="number" name="cost" value="0" min="0" step="0.01" class="form-input">
        </div>
        <div>
          <label class="form-label">Selling Price (£)</label>
          <input type="number" name="selling_price" value="0" min="0" step="0.01" class="form-input">
        </div>
        <div>
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-input" placeholder="Warehouse, Office…">
        </div>
        <div>
          <label class="form-label">Low Stock Alert ≤</label>
          <input type="number" name="low_stock_threshold" value="5" min="0" class="form-input">
        </div>
        <div>
          <label class="form-label">Purchase Date</label>
          <input type="date" name="purchase_date" class="form-input">
        </div>
        <div>
          <label class="form-label">Barcode / Serial</label>
          <input type="text" name="barcode" class="form-input" placeholder="Optional">
        </div>
        <div class="sm:col-span-2">
          <label class="form-label">Image</label>
          <div class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer" style="border-color:rgba(212,168,83,0.2);" onclick="document.getElementById('addImgInput').click()">
            <p class="text-xs" style="color:#A0A0A0;">Click to upload <span style="color:#D4A853;">(JPG, PNG, WebP · max 5MB)</span></p>
            <input type="file" id="addImgInput" name="image" accept="image/*" class="hidden" onchange="previewAddImg(this)">
          </div>
          <img id="addImgPreview" src="" class="mt-2 h-16 rounded-lg object-cover hidden">
        </div>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Add Item
        </button>
        <button type="button" onclick="closeModal('addItemModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- ═══ EDIT ITEM MODAL ═══ -->
<div class="modal-overlay" id="editItemModal">
  <div class="modal-box" style="max-width:680px;">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-semibold text-white">Edit Item</h3>
      <button onclick="closeModal('editItemModal')" style="color:#888;" class="hover:text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" id="editItemForm" enctype="multipart/form-data" class="space-y-5">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
          <label class="form-label">Item Name *</label>
          <input type="text" name="name" id="eiName" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Category *</label>
          <select name="category_id" id="eiCategory" class="form-input" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label">Condition *</label>
          <select name="condition_status" id="eiCondition" class="form-input" required>
            <?php foreach (['New','Good','Fair','Damaged'] as $c): ?>
              <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="sm:col-span-2">
          <label class="form-label">Description</label>
          <textarea name="description" id="eiDesc" rows="2" class="form-input resize-none"></textarea>
        </div>
        <div>
          <label class="form-label">Quantity *</label>
          <input type="number" name="quantity" id="eiQty" min="0" class="form-input" required>
        </div>
        <div>
          <label class="form-label">Unit</label>
          <input type="text" name="unit" id="eiUnit" class="form-input">
        </div>
        <div>
          <label class="form-label">Unit Cost (£)</label>
          <input type="number" name="cost" id="eiCost" min="0" step="0.01" class="form-input">
        </div>
        <div>
          <label class="form-label">Selling Price (£)</label>
          <input type="number" name="selling_price" id="eiSellPrice" min="0" step="0.01" class="form-input">
        </div>
        <div>
          <label class="form-label">Location</label>
          <input type="text" name="location" id="eiLocation" class="form-input">
        </div>
        <div>
          <label class="form-label">Low Stock Alert ≤</label>
          <input type="number" name="low_stock_threshold" id="eiThreshold" min="0" class="form-input">
        </div>
        <div>
          <label class="form-label">Purchase Date</label>
          <input type="date" name="purchase_date" id="eiPurchaseDate" class="form-input">
        </div>
        <div>
          <label class="form-label">Barcode / Serial</label>
          <input type="text" name="barcode" id="eiBarcode" class="form-input">
        </div>
        <div class="sm:col-span-2">
          <label class="form-label">Image</label>
          <input type="hidden" name="remove_image" id="eiRemoveFlag" value="0">
          <div id="eiCurrentImgWrap" class="hidden items-center gap-3 mb-2 p-3 rounded-lg" style="background:rgba(255,255,255,0.03);border:1px solid rgba(212,168,83,0.1);">
            <img id="eiCurrentImg" src="" class="h-14 w-14 rounded-lg object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-white">Current image</p>
              <p class="text-xs mt-0.5" style="color:#A0A0A0;">Upload below to replace, or remove entirely</p>
            </div>
            <button type="button" onclick="removeEditImg()" class="flex-shrink-0 text-xs px-3 py-1.5 rounded-lg font-medium" style="background:rgba(239,68,68,0.12);color:#F87171;border:1px solid rgba(239,68,68,0.25);">
              Remove
            </button>
          </div>
          <div id="eiUploadZone" class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer" style="border-color:rgba(212,168,83,0.2);" onclick="document.getElementById('editImgInput').click()">
            <p class="text-xs" style="color:#A0A0A0;">Click to upload new image</p>
            <input type="file" id="editImgInput" name="image" accept="image/*" class="hidden" onchange="previewEditImg(this)">
          </div>
          <img id="editImgPreview" src="" class="mt-2 h-14 rounded-lg object-cover hidden">
        </div>
      </div>
      <div class="flex gap-3 pt-1">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Changes
        </button>
        <button type="button" onclick="closeModal('editItemModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Toolbar -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
  <form method="GET" action="<?= url('inventory') ?>" class="flex flex-wrap items-center gap-3">
    <div class="relative">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:#A0A0A0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Search items…" class="form-input pl-9 py-2 w-56 text-sm">
    </div>
    <select name="category_id" class="form-input py-2 w-44 text-sm">
      <option value="">All Categories</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="condition" class="form-input py-2 w-36 text-sm">
      <option value="">All Conditions</option>
      <?php foreach (['New','Good','Fair','Damaged'] as $c): ?>
        <option value="<?= $c ?>" <?= ($filters['condition'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-outline py-2 px-4 text-sm">Filter</button>
    <?php if (!empty($filters['search']) || !empty($filters['category_id']) || !empty($filters['condition'])): ?>
      <a href="<?= url('inventory') ?>" class="text-xs" style="color:#EF4444;">Clear ×</a>
    <?php endif; ?>
  </form>
  <?php if (can('inventory.*')): ?>
    <button onclick="openModal('addItemModal')" class="btn-gold flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Item
    </button>
  <?php endif; ?>
</div>

<!-- Summary bar -->
<p class="text-xs mb-4" style="color:#A0A0A0;"><?= number_format($pagination['total']) ?> item<?= $pagination['total'] != 1 ? 's' : '' ?> found</p>

<!-- Table -->
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#0D0D0D;">
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Item</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Category</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Total</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Assigned</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Available</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Condition</th>
          <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Location</th>
          <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($items)): ?>
        <tr>
          <td colspan="8" class="px-5 py-16 text-center">
            <svg class="w-10 h-10 mx-auto mb-3" style="color:#2A2A2A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <p class="text-sm" style="color:#B0B0B0;">No items found</p>
            <?php if (can('inventory.*')): ?>
              <button onclick="openModal('addItemModal')" class="inline-block mt-3 btn-gold text-xs">Add First Item</button>
            <?php endif; ?>
          </td>
        </tr>
        <?php else: ?>
        <?php foreach ($items as $item):
          $available = (int)$item['available'];
          $isLow     = $available <= (int)$item['low_stock_threshold'];
          $itemJson  = htmlspecialchars(json_encode([
            'id'                  => $item['id'],
            'name'                => $item['name'],
            'category_id'         => $item['category_id'],
            'condition_status'    => $item['condition_status'],
            'description'         => $item['description'],
            'quantity'            => $item['quantity'],
            'unit'                => $item['unit'],
            'cost'                => $item['cost'],
            'selling_price'       => $item['selling_price'] ?? 0,
            'location'            => $item['location'],
            'low_stock_threshold' => $item['low_stock_threshold'],
            'purchase_date'       => $item['purchase_date'],
            'barcode'             => $item['barcode'],
            'image'               => $item['image'],
          ]), ENT_QUOTES, 'UTF-8');
        ?>
        <tr class="table-row">
          <td class="px-5 py-4">
            <div class="flex items-center gap-3">
              <?php if ($item['image']): ?>
                <img src="<?= url('uploads/' . $item['image']) ?>" class="w-9 h-9 rounded-lg object-cover flex-shrink-0">
              <?php else: ?>
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:rgba(212,168,83,0.1);color:#D4A853;"><?= strtoupper(substr($item['name'],0,1)) ?></div>
              <?php endif; ?>
              <div>
                <a href="<?= url('inventory/' . $item['id']) ?>" class="font-medium text-white hover:underline" style="text-underline-offset:3px;" title="<?= e($item['name']) ?>"><?= e(strlen($item['name']) > 30 ? substr($item['name'],0,30).'…' : $item['name']) ?></a>
                <?php if ($item['barcode']): ?>
                  <p class="text-xs font-mono mt-0.5" style="color:#B0B0B0;"><?= e($item['barcode']) ?></p>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td class="px-5 py-4">
            <span class="badge text-xs" style="background:<?= e($item['category_color']) ?>20;color:<?= e($item['category_color']) ?>;border:1px solid <?= e($item['category_color']) ?>30;"><?= e($item['category_name']) ?></span>
          </td>
          <td class="px-5 py-4 text-right font-medium text-white"><?= number_format((int)$item['quantity']) ?></td>
          <td class="px-5 py-4 text-right" style="color:#3B82F6;"><?= number_format((int)$item['quantity_assigned']) ?></td>
          <td class="px-5 py-4 text-right">
            <span class="font-bold <?= $isLow ? 'text-amber-400' : 'text-emerald-400' ?>"><?= number_format($available) ?></span>
            <?php if ($isLow): ?><span class="ml-1 text-xs">⚠</span><?php endif; ?>
          </td>
          <td class="px-5 py-4"><?= condition_badge($item['condition_status']) ?></td>
          <td class="px-5 py-4 text-xs" style="color:#C0C0C0;"><?= e($item['location'] ?? '—') ?></td>
          <td class="px-5 py-4">
            <div class="flex items-center justify-end gap-2">
              <a href="<?= url('inventory/' . $item['id']) ?>" class="p-1.5 rounded" title="View" style="color:#555;" onmouseover="this.style.color='#D4A853'" onmouseout="this.style.color='#555'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </a>
              <?php if (can('inventory.*')): ?>
              <button onclick='openEditItem(<?= $itemJson ?>)' class="p-1.5 rounded" title="Edit" style="color:#555;" onmouseover="this.style.color='#3B82F6'" onmouseout="this.style.color='#555'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </button>
              <?php if (has_role('Admin')): ?>
              <form method="POST" action="<?= url('inventory/' . $item['id'] . '/delete') ?>" onsubmit="return confirm('Delete this item permanently?')">
                <?= csrf_field() ?>
                <button type="submit" class="p-1.5 rounded" title="Delete" style="color:#555;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#555'">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </form>
              <?php endif; ?>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pagination['total_pages'] > 1): ?>
  <div class="px-5 py-4 flex items-center justify-between border-t" style="border-color:rgba(212,168,83,0.08);">
    <p class="text-xs" style="color:#A0A0A0;">Showing <?= ($pagination['offset'] + 1) ?>–<?= min($pagination['offset'] + $pagination['per_page'], $pagination['total']) ?> of <?= number_format($pagination['total']) ?></p>
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
function previewAddImg(input) {
  if (input.files?.[0]) {
    const r = new FileReader();
    r.onload = e => { const img = document.getElementById('addImgPreview'); img.src = e.target.result; img.classList.remove('hidden'); };
    r.readAsDataURL(input.files[0]);
  }
}
function previewEditImg(input) {
  if (input.files?.[0]) {
    const r = new FileReader();
    r.onload = e => { const img = document.getElementById('editImgPreview'); img.src = e.target.result; img.classList.remove('hidden'); };
    r.readAsDataURL(input.files[0]);
    // uploading a new image cancels any pending removal
    document.getElementById('eiRemoveFlag').value = '0';
  }
}
function removeEditImg() {
  document.getElementById('eiRemoveFlag').value = '1';
  document.getElementById('eiCurrentImgWrap').style.display = 'none';
  document.getElementById('editImgInput').value = '';
  document.getElementById('editImgPreview').classList.add('hidden');
  // show a "removal pending" notice in the upload zone
  document.getElementById('eiUploadZone').innerHTML =
    '<p class="text-xs" style="color:#F87171;">Image will be removed on save. Upload a new one to use instead.</p>' +
    '<input type="file" id="editImgInput" name="image" accept="image/*" class="hidden" onchange="previewEditImg(this)">';
}
function openEditItem(item) {
  document.getElementById('editItemForm').action = '<?= url('inventory/') ?>' + item.id + '/update';
  document.getElementById('eiName').value        = item.name        || '';
  document.getElementById('eiCategory').value    = item.category_id || '';
  document.getElementById('eiCondition').value   = item.condition_status || 'Good';
  document.getElementById('eiDesc').value        = item.description  || '';
  document.getElementById('eiQty').value         = item.quantity     || 0;
  document.getElementById('eiUnit').value        = item.unit         || 'pcs';
  document.getElementById('eiCost').value        = parseFloat(item.cost || 0).toFixed(2);
  document.getElementById('eiSellPrice').value   = parseFloat(item.selling_price || 0).toFixed(2);
  document.getElementById('eiLocation').value    = item.location     || '';
  document.getElementById('eiThreshold').value   = item.low_stock_threshold || 5;
  document.getElementById('eiPurchaseDate').value= item.purchase_date || '';
  document.getElementById('eiBarcode').value     = item.barcode      || '';
  // min quantity = already assigned
  document.getElementById('eiQty').min = 0;

  const imgWrap = document.getElementById('eiCurrentImgWrap');
  const imgEl   = document.getElementById('eiCurrentImg');
  document.getElementById('eiRemoveFlag').value = '0';
  if (item.image) {
    imgEl.src = '<?= url('uploads/') ?>' + item.image;
    imgWrap.classList.remove('hidden');
    imgWrap.style.display = 'flex';
  } else {
    imgWrap.style.display = 'none';
  }
  // reset new upload preview
  document.getElementById('editImgInput').value = '';
  document.getElementById('editImgPreview').classList.add('hidden');
  document.getElementById('eiUploadZone').style.display = '';

  openModal('editItemModal');
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
