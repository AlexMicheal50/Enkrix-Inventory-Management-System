<?php
$title    = 'Audit Log';
$subtitle = 'Complete record of all system actions';
ob_start();
?>

<div class="card overflow-hidden">
  <div class="px-6 py-4 border-b" style="border-color:rgba(212,168,83,0.1);">
    <h2 class="text-sm font-semibold text-white">Activity Log</h2>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr style="background:#0D0D0D;">
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">User</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Action</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Entity</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Details</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">IP</th>
          <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Time</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $log): ?>
        <tr class="table-row">
          <td class="px-6 py-3 text-white font-medium"><?= e($log['user_name']) ?></td>
          <td class="px-6 py-3">
            <span class="badge" style="background:rgba(212,168,83,0.1);color:#D4A853;"><?= e(ucfirst(str_replace('_', ' ', $log['action']))) ?></span>
          </td>
          <td class="px-6 py-3 text-white"><?= e($log['entity_name'] ?? '—') ?></td>
          <td class="px-6 py-3 max-w-xs truncate" style="color:#C0C0C0;"><?= e($log['details'] ?? '—') ?></td>
          <td class="px-6 py-3 font-mono text-xs" style="color:#B0B0B0;"><?= e($log['ip_address'] ?? '—') ?></td>
          <td class="px-6 py-3 whitespace-nowrap" style="color:#A0A0A0;"><?= format_date($log['created_at'], 'd M Y g:ia') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($pagination['total_pages'] > 1): ?>
  <div class="px-6 py-4 border-t flex items-center justify-between" style="border-color:rgba(212,168,83,0.1);">
    <p class="text-xs" style="color:#A0A0A0;">Page <?= $pagination['current'] ?> of <?= $pagination['total_pages'] ?></p>
    <div class="flex gap-2">
      <?php if ($pagination['has_prev']): ?>
        <a href="?page=<?= $pagination['current'] - 1 ?>" class="btn-outline text-xs py-1.5 px-3">← Prev</a>
      <?php endif; ?>
      <?php if ($pagination['has_next']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?>" class="btn-outline text-xs py-1.5 px-3">Next →</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
