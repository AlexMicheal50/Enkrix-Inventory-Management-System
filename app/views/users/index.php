<?php
$title    = 'User Management';
$subtitle = 'Manage system users and roles';
ob_start();
?>

<div class="flex justify-end mb-6">
  <button onclick="openModal('addUserModal')" class="btn-gold flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
    Add User
  </button>
</div>

<!-- Users Table -->
<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#0D0D0D;">
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">User</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Role</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Status</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Last Login</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Joined</th>
          <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr class="table-row">
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0" style="background:rgba(212,168,83,0.12);color:#D4A853;">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
              </div>
              <div>
                <p class="font-medium text-white"><?= e($u['name']) ?></p>
                <p class="text-xs" style="color:#A0A0A0;"><?= e($u['email']) ?></p>
              </div>
            </div>
          </td>
          <td class="px-6 py-4">
            <?php
            $roleColors = ['Admin' => '#D4A853', 'Inventory Manager' => '#3B82F6', 'Viewer' => '#6B7280'];
            $rc = $roleColors[$u['role']] ?? '#6B7280';
            ?>
            <span class="badge" style="background:<?= $rc ?>20;color:<?= $rc ?>;border:1px solid <?= $rc ?>30;"><?= e($u['role']) ?></span>
          </td>
          <td class="px-6 py-4">
            <?php if ($u['is_active']): ?>
              <span class="badge" style="background:rgba(34,197,94,0.12);color:#22C55E;border:1px solid rgba(34,197,94,0.25);">Active</span>
            <?php else: ?>
              <span class="badge" style="background:rgba(239,68,68,0.12);color:#EF4444;border:1px solid rgba(239,68,68,0.25);">Inactive</span>
            <?php endif; ?>
          </td>
          <td class="px-6 py-4 text-xs" style="color:#C0C0C0;"><?= $u['last_login'] ? format_date($u['last_login'], 'd M Y g:ia') : 'Never' ?></td>
          <td class="px-6 py-4 text-xs" style="color:#C0C0C0;"><?= format_date($u['created_at']) ?></td>
          <td class="px-6 py-4">
            <div class="flex items-center justify-end gap-2">
              <?php if ($u['id'] !== auth()['id']): ?>
              <button onclick="openEditUserModal(<?= $u['id'] ?>, '<?= addslashes($u['name']) ?>', '<?= addslashes($u['email']) ?>', <?= $u['role_id'] ?>)"
                class="p-1.5 rounded" title="Edit" style="color:#A0A0A0;" onmouseover="this.style.color='#3B82F6'" onmouseout="this.style.color='#555'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </button>
              <form method="POST" action="<?= url('users/' . $u['id'] . '/toggle') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="p-1.5 rounded" title="<?= $u['is_active'] ? 'Deactivate' : 'Activate' ?>" style="color:#A0A0A0;" onmouseover="this.style.color='#F59E0B'" onmouseout="this.style.color='#555'">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <?php if ($u['is_active']): ?>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    <?php else: ?>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    <?php endif; ?>
                  </svg>
                </button>
              </form>
              <form method="POST" action="<?= url('users/' . $u['id'] . '/delete') ?>" onsubmit="return confirm('Delete user permanently?')">
                <?= csrf_field() ?>
                <button type="submit" class="p-1.5 rounded" title="Delete" style="color:#A0A0A0;" onmouseover="this.style.color='#EF4444'" onmouseout="this.style.color='#555'">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </form>
              <?php else: ?>
                <span class="text-xs" style="color:#909090;">You</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="addUserModal">
  <div class="modal-box">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-base font-semibold text-white">Add New User</h2>
      <button onclick="closeModal('addUserModal')" style="color:#A0A0A0;"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" action="<?= url('users/store') ?>" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Full Name *</label>
        <input type="text" name="name" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Email *</label>
        <input type="email" name="email" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Password *</label>
        <input type="password" name="password" class="form-input" minlength="8" required placeholder="Min 8 characters">
      </div>
      <div>
        <label class="form-label">Role *</label>
        <select name="role_id" class="form-input" required>
          <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?>"><?= e($role['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold">Create User</button>
        <button type="button" onclick="closeModal('addUserModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal-overlay" id="editUserModal">
  <div class="modal-box">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-base font-semibold text-white">Edit User</h2>
      <button onclick="closeModal('editUserModal')" style="color:#A0A0A0;"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
    </div>
    <form method="POST" id="editUserForm" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="form-label">Full Name *</label>
        <input type="text" name="name" id="editUserName" class="form-input" required>
      </div>
      <div>
        <label class="form-label">Email *</label>
        <input type="email" name="email" id="editUserEmail" class="form-input" required>
      </div>
      <div>
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-input" minlength="8" placeholder="Leave blank to keep current">
      </div>
      <div>
        <label class="form-label">Role *</label>
        <select name="role_id" id="editUserRole" class="form-input" required>
          <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?>"><?= e($role['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="submit" class="btn-gold">Save Changes</button>
        <button type="button" onclick="closeModal('editUserModal')" class="btn-outline">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditUserModal(id, name, email, roleId) {
  document.getElementById('editUserForm').action = `<?= url('users/') ?>${id}/update`;
  document.getElementById('editUserName').value  = name;
  document.getElementById('editUserEmail').value = email;
  document.getElementById('editUserRole').value  = roleId;
  openModal('editUserModal');
}
</script>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
