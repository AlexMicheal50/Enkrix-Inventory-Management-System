<?php
$title    = 'Edit Item';
$subtitle = e($item['name']);
ob_start();
?>

<div class="max-w-3xl">
  <div class="card p-7">
    <form method="POST" action="<?= url('inventory/' . $item['id'] . '/update') ?>" enctype="multipart/form-data" class="space-y-6">
      <?= csrf_field() ?>

      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="form-label">Item Name *</label>
            <input type="text" name="name" value="<?= old('name', $item['name']) ?>" class="form-input" required>
          </div>
          <div>
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-input" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $item['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label">Condition *</label>
            <select name="condition_status" class="form-input" required>
              <?php foreach (['New','Good','Fair','Damaged'] as $c): ?>
                <option value="<?= $c ?>" <?= $item['condition_status'] === $c ? 'selected' : '' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input resize-none"><?= old('description', $item['description']) ?></textarea>
          </div>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Stock Details</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" value="<?= old('quantity', $item['quantity']) ?>" min="<?= (int)$item['quantity_assigned'] ?>" class="form-input" required>
            <?php if ($item['quantity_assigned'] > 0): ?>
              <p class="text-xs mt-1" style="color:#C8C8C8;"><?= (int)$item['quantity_assigned'] ?> currently assigned</p>
            <?php endif; ?>
          </div>
          <div>
            <label class="form-label">Unit</label>
            <input type="text" name="unit" value="<?= old('unit', $item['unit']) ?>" class="form-input" placeholder="pcs">
          </div>
          <div>
            <label class="form-label">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" value="<?= old('low_stock_threshold', $item['low_stock_threshold']) ?>" min="0" class="form-input">
          </div>
          <div>
            <label class="form-label">Location</label>
            <input type="text" name="location" value="<?= old('location', $item['location']) ?>" class="form-input">
          </div>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Financial & Tracking</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <label class="form-label">Unit Cost (£)</label>
            <input type="number" name="cost" value="<?= old('cost', $item['cost']) ?>" min="0" step="0.01" class="form-input">
          </div>
          <div>
            <label class="form-label">Selling Price (£)</label>
            <input type="number" name="selling_price" value="<?= old('selling_price', $item['selling_price'] ?? '0') ?>" min="0" step="0.01" class="form-input">
          </div>
          <div>
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" value="<?= old('purchase_date', $item['purchase_date']) ?>" class="form-input">
          </div>
          <div>
            <label class="form-label">Barcode / Serial</label>
            <input type="text" name="barcode" value="<?= old('barcode', $item['barcode']) ?>" class="form-input">
          </div>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Item Image</h3>
        <input type="hidden" name="remove_image" id="removeFlag" value="0">
        <?php if ($item['image']): ?>
          <div id="currentImgWrap" class="flex items-center gap-4 mb-3 p-3 rounded-lg" style="background:rgba(255,255,255,0.03);border:1px solid rgba(212,168,83,0.1);">
            <img src="<?= url('uploads/' . $item['image']) ?>" class="h-16 w-16 rounded-lg object-cover flex-shrink-0" alt="Current image">
            <div class="flex-1">
              <p class="text-xs font-medium text-white">Current image</p>
              <p class="text-xs mt-0.5" style="color:#A0A0A0;">Upload below to replace, or remove entirely</p>
            </div>
            <button type="button" onclick="removeImg()" class="text-xs px-3 py-1.5 rounded-lg font-medium" style="background:rgba(239,68,68,0.12);color:#F87171;border:1px solid rgba(239,68,68,0.25);">
              Remove
            </button>
          </div>
        <?php endif; ?>
        <div id="uploadZone" class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer" style="border-color:rgba(212,168,83,0.2);" onclick="document.getElementById('imgInput').click()">
          <p class="text-xs" style="color:#A0A0A0;">Click to upload a new image</p>
          <input type="file" id="imgInput" name="image" accept="image/*" class="hidden" onchange="previewImg(this)">
        </div>
        <img id="imgPreview" src="" class="mt-3 h-20 rounded-lg object-cover hidden">
      </div>

      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          Save Changes
        </button>
        <a href="<?= url('inventory/' . $item['id']) ?>" class="btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
function previewImg(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('imgPreview');
      img.src = e.target.result; img.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
    document.getElementById('removeFlag').value = '0';
  }
}
function removeImg() {
  document.getElementById('removeFlag').value = '1';
  const wrap = document.getElementById('currentImgWrap');
  if (wrap) wrap.style.display = 'none';
  document.getElementById('imgInput').value = '';
  document.getElementById('imgPreview').classList.add('hidden');
  document.getElementById('uploadZone').innerHTML =
    '<p class="text-xs" style="color:#F87171;">Image will be removed on save. Upload a new one to use instead.</p>' +
    '<input type="file" id="imgInput" name="image" accept="image/*" class="hidden" onchange="previewImg(this)">';
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
