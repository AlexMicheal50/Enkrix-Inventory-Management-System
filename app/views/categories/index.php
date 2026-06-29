<?php
$title    = 'Categories';
$subtitle = 'Organise your inventory into categories';
ob_start();
?>

<div class="flex justify-end mb-6">
  <?php if (can('categories.*')): ?>
    <button onclick="openModal('addCatModal')" class="btn-gold flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      New Category
    </button>
  <?php endif; ?>
</div>

<!-- Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
  <?php if (empty($categories)): ?>
    <div class="col-span-full text-center py-16">
      <svg class="w-10 h-10 mx-auto mb-3" style="color:#2A2A2A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
      <p class="text-sm" style="color:#B0B0B0;">No categories yet. Create one to get started.</p>
    </div>
  <?php endif; ?>

  <?php foreach ($categories as $cat): ?>
  <div class="card card-hover p-5 transition-colors">
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex-shrink-0" style="background:<?= e($cat['color']) ?>20;border:1px solid <?= e($cat['color']) ?>30;">
          <div class="w-full h-full rounded-xl flex items-center justify-center">
            <div class="w-3 h-3 rounded-full" style="background:<?= e($cat['color']) ?>;"></div>
          </div>
        </div>
        <div>
          <h3 class="font-semibold text-white text-sm"><?= e($cat['name']) ?></h3>
          <p class="text-xs mt-0.5" style="color:#A0A0A0;"><?= (int)$cat['item_count'] ?> item<?= $cat['item_count'] != 1 ? 's' : '' ?></p>
        </div>
      </div>
      <?php if (can('categories.*')): ?>
      <div class="flex gap-1">
        <button onclick="openEditModal(<?= $cat['id'] ?>, '<?= addslashes($cat['name']) ?>', '<?= addslashes($cat['description'] ?? '') ?>', '<?= $cat['color'] ?>')" class="p-1.5 rounded" style="color:#A0A0A0;" onmouseover="this.style.color='#3B82F6'" onmouseout="this.style.color='#555'" title="Edit">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </button>
        <?php if (has_role('Admin') && (int)$cat['item_count'] === 0): ?>
        <form method="POST" action="<?= url('categories/' . $cat['id'] . '/delete') ?>" onsubmit="return confirm('Delete this category?')">
          <?= csrf_field() ?>
          <button type="submit" class="p-1.5 rounded" style="color:#A0A0A0;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#555'" title="Delete">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </form>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php if ($cat['description']): ?>
      <p class="text-xs" style="color:#A0A0A0;"><?= e($cat['description']) ?></p>
    <?php endif; ?>
    <div class="mt-4 pt-3 border-t flex items-center justify-between" style="border-color:rgba(255,255,255,0.05);">
      <a href="<?= url('inventory?category_id=' . $cat['id']) ?>" class="text-xs" style="color:#D4A853;">Browse items →</a>
      <div class="w-16 h-1 rounded-full" style="background:<?= e($cat['color']) ?>40;"></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Add Modal -->
<?php if (can('categories.*')): ?>
<div class="modal-overlay" id="addCatModal">
  <div class="modal-box">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-base font-semibold text-white">New Category</h2>
      <button onclick="closeModal('addCatModal')" style="color:#A0A0A0;"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" action="<?= url('categories/store') ?>" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Name *</label>
        <input type="text" name="name" class="form-input" placeholder="e.g. Audio Equipment" required>
      </div>
      <div>
        <label class="form-label">Description</label>
        <textarea name="description" rows="2" class="form-input resize-none" placeholder="Optional…"></textarea>
      </div>
      <div>
        <label class="form-label">Colour</label>
        <div class="flex items-center gap-3">
          <input type="color" name="color" value="#D4A853" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0.5" style="background:#1A1A1A;">
          <span class="text-xs" style="color:#A0A0A0;">Choose a colour for this category</span>
        </div>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold">Create Category</button>
        <button type="button" onclick="closeModal('addCatModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editCatModal">
  <div class="modal-box">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-base font-semibold text-white">Edit Category</h2>
      <button onclick="closeModal('editCatModal')" style="color:#A0A0A0;"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" id="editCatForm" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Name *</label>
        <input type="text" name="name" id="editCatName" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Description</label>
        <textarea name="description" id="editCatDesc" rows="2" class="form-input resize-none"></textarea>
      </div>
      <div>
        <label class="form-label">Colour</label>
        <input type="color" name="color" id="editCatColor" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0.5" style="background:#1A1A1A;">
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold">Save Changes</button>
        <button type="button" onclick="closeModal('editCatModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditModal(id, name, desc, color) {
  document.getElementById('editCatForm').action = `<?= url('categories/') ?>${id}/update`;
  document.getElementById('editCatName').value  = name;
  document.getElementById('editCatDesc').value  = desc;
  document.getElementById('editCatColor').value = color;
  openModal('editCatModal');
}
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
