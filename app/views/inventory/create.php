<?php
$title    = 'Add Inventory Item';
$subtitle = 'Fill in the details below to add a new asset';
ob_start();
?>

<div class="max-w-3xl">
  <div class="card p-7">
    <form method="POST" action="<?= url('inventory/store') ?>" enctype="multipart/form-data" class="space-y-6">
      <?= csrf_field() ?>

      <!-- Basic Info -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="form-label">Item Name *</label>
            <input type="text" name="name" value="<?= old('name') ?>" class="form-input" placeholder="e.g. Shure SM58 Microphone" required>
          </div>
          <div>
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-input" required>
              <option value="">Select category…</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="form-label">Condition *</label>
            <select name="condition_status" class="form-input" required>
              <?php foreach (['New','Good','Fair','Damaged'] as $c): ?>
                <option value="<?= $c ?>" <?= old('condition_status', 'Good') === $c ? 'selected' : '' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-input resize-none" placeholder="Optional description…"><?= old('description') ?></textarea>
          </div>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <!-- Stock Details -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Stock Details</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <label class="form-label">Quantity *</label>
            <input type="number" name="quantity" value="<?= old('quantity', '0') ?>" min="0" class="form-input" required>
          </div>
          <div>
            <label class="form-label">Unit</label>
            <input type="text" name="unit" value="<?= old('unit', 'pcs') ?>" class="form-input" placeholder="pcs">
          </div>
          <div>
            <label class="form-label">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" value="<?= old('low_stock_threshold', '5') ?>" min="0" class="form-input">
          </div>
          <div>
            <label class="form-label">Location</label>
            <input type="text" name="location" value="<?= old('location') ?>" class="form-input" placeholder="Store, Hall…">
          </div>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <!-- Financial & Tracking -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Financial & Tracking</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div>
            <label class="form-label">Unit Cost (£)</label>
            <input type="number" name="cost" value="<?= old('cost', '0') ?>" min="0" step="0.01" class="form-input">
          </div>
          <div>
            <label class="form-label">Selling Price (£)</label>
            <input type="number" name="selling_price" value="<?= old('selling_price', '0') ?>" min="0" step="0.01" class="form-input">
          </div>
          <div>
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" value="<?= old('purchase_date') ?>" class="form-input">
          </div>
          <div>
            <label class="form-label">Barcode / Serial</label>
            <input type="text" name="barcode" value="<?= old('barcode') ?>" class="form-input" placeholder="Optional">
          </div>
        </div>
      </div>

      <div class="border-t" style="border-color:rgba(212,168,83,0.08);"></div>

      <!-- Image Upload -->
      <div>
        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Item Image</h3>
        <div class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-colors" style="border-color:rgba(212,168,83,0.2);" id="dropzone" onclick="document.getElementById('imgInput').click()">
          <svg class="w-8 h-8 mx-auto mb-2" style="color:#909090;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <p class="text-xs" style="color:#A0A0A0;">Click to upload image <span style="color:#D4A853;">(JPG, PNG, WebP · max 5MB)</span></p>
          <input type="file" id="imgInput" name="image" accept="image/*" class="hidden" onchange="previewImg(this)">
        </div>
        <img id="imgPreview" src="" class="mt-3 h-24 rounded-lg object-cover hidden">
      </div>

      <!-- Actions -->
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Add Item
        </button>
        <a href="<?= url('inventory') ?>" class="btn-outline">Cancel</a>
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
      img.src = e.target.result;
      img.classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
