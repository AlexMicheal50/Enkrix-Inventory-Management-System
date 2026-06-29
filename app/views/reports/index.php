<?php
$title    = 'Reports';
$subtitle = 'Inventory insights and asset reporting';
ob_start();
?>

<!-- Report Tabs -->
<div class="flex flex-wrap items-center gap-2 mb-6">
  <?php
  $tabs = [
    'stock'      => 'Stock Report',
    'low_stock'  => 'Low Stock',
    'assignment' => 'Assignments',
  ];
  foreach ($tabs as $key => $label): ?>
    <a href="?type=<?= $key ?>"
      class="px-4 py-2 rounded-lg text-sm font-medium transition-all <?= $type === $key ? 'text-black' : 'text-gray-400' ?>"
      style="<?= $type === $key ? 'background:linear-gradient(135deg,#D4A853,#B8922A);' : 'background:#1A1A1A;color:#666;border:1px solid rgba(255,255,255,0.06);' ?>">
      <?= $label ?>
    </a>
  <?php endforeach; ?>
  <div class="ml-auto">
    <a href="<?= url('reports/export?type=' . $type) ?>" class="btn-outline flex items-center gap-2 text-sm">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
      Export CSV
    </a>
  </div>
</div>

<!-- Summary Cards (always shown) -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <?php $summaries = [
    ['label' => 'Total Items',    'value' => number_format((int)($stats['total_items']    ?? 0)),  'gold' => true],
    ['label' => 'Total Quantity', 'value' => number_format((int)($stats['total_quantity'] ?? 0)),  'gold' => false],
    ['label' => 'Assigned',       'value' => number_format((int)($stats['total_assigned'] ?? 0)),  'gold' => false],
    ['label' => 'Total Value',    'value' => '£' . number_format((float)($stats['total_value'] ?? 0), 0), 'gold' => true],
  ]; ?>
  <?php foreach ($summaries as $s): ?>
  <div class="card p-4">
    <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color:#A0A0A0;"><?= $s['label'] ?></p>
    <p class="text-xl font-bold <?= $s['gold'] ? '' : 'text-white' ?>" <?= $s['gold'] ? 'style="color:#D4A853;"' : '' ?>><?= $s['value'] ?></p>
  </div>
  <?php endforeach; ?>
</div>

<!-- Report Table -->
<div class="card overflow-hidden">
  <div class="px-5 py-4 flex items-center justify-between border-b" style="border-color:rgba(212,168,83,0.1);">
    <h3 class="font-semibold text-sm text-white"><?= $tabs[$type] ?></h3>
    <p class="text-xs" style="color:#A0A0A0;"><?= date('d M Y') ?></p>
  </div>
  <div class="overflow-x-auto">

    <?php if ($type === 'stock'): ?>
    <table class="w-full text-sm">
      <thead><tr style="background:#0D0D0D;">
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">#</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Item</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Category</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Total</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Assigned</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Available</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Condition</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Location</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Unit Cost</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Total Value</th>
      </tr></thead>
      <tbody>
        <?php foreach ($allItems as $i => $item): ?>
        <tr class="table-row">
          <td class="px-5 py-3 text-xs" style="color:#B0B0B0;"><?= $i + 1 ?></td>
          <td class="px-5 py-3 font-medium text-white"><?= e($item['name']) ?></td>
          <td class="px-5 py-3"><span class="badge" style="background:rgba(212,168,83,0.1);color:#D4A853;"><?= e($item['category_name']) ?></span></td>
          <td class="px-5 py-3 text-right text-white"><?= (int)$item['quantity'] ?></td>
          <td class="px-5 py-3 text-right" style="color:#3B82F6;"><?= (int)$item['quantity_assigned'] ?></td>
          <td class="px-5 py-3 text-right <?= (int)$item['available'] <= (int)$item['low_stock_threshold'] ? 'text-amber-400' : 'text-emerald-400' ?> font-semibold"><?= (int)$item['available'] ?></td>
          <td class="px-5 py-3"><?= condition_badge($item['condition_status']) ?></td>
          <td class="px-5 py-3 text-xs" style="color:#C0C0C0;"><?= e($item['location'] ?? '—') ?></td>
          <td class="px-5 py-3 text-right text-xs" style="color:#C8C8C8;"><?= format_currency((float)$item['cost']) ?></td>
          <td class="px-5 py-3 text-right text-sm font-semibold" style="color:#D4A853;"><?= format_currency((float)$item['cost'] * (int)$item['quantity']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php elseif ($type === 'low_stock'): ?>
    <table class="w-full text-sm">
      <thead><tr style="background:#0D0D0D;">
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Item</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Category</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Total</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Available</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Threshold</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Location</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Condition</th>
      </tr></thead>
      <tbody>
        <?php if (empty($lowStock)): ?>
        <tr><td colspan="7" class="px-5 py-12 text-center text-sm" style="color:#B0B0B0;">All items are well stocked.</td></tr>
        <?php endif; ?>
        <?php foreach ($lowStock as $item): ?>
        <tr class="table-row">
          <td class="px-5 py-3 font-medium text-white"><?= e($item['name']) ?></td>
          <td class="px-5 py-3"><span class="badge" style="background:rgba(212,168,83,0.1);color:#D4A853;"><?= e($item['category_name']) ?></span></td>
          <td class="px-5 py-3 text-right text-white"><?= (int)$item['quantity'] ?></td>
          <td class="px-5 py-3 text-right font-bold text-amber-400"><?= (int)$item['available'] ?></td>
          <td class="px-5 py-3 text-right text-xs" style="color:#C0C0C0;"><?= (int)$item['low_stock_threshold'] ?></td>
          <td class="px-5 py-3 text-xs" style="color:#C0C0C0;"><?= e($item['location'] ?? '—') ?></td>
          <td class="px-5 py-3"><?= condition_badge($item['condition_status']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php elseif ($type === 'assignment'): ?>
    <table class="w-full text-sm">
      <thead><tr style="background:#0D0D0D;">
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Item</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Category</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Assigned To</th>
        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Qty</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Date</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">Status</th>
        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#A0A0A0;">By</th>
      </tr></thead>
      <tbody>
        <?php foreach ($allAssign as $a): ?>
        <tr class="table-row">
          <td class="px-5 py-3 font-medium text-white"><?= e($a['item_name']) ?></td>
          <td class="px-5 py-3"><span class="badge" style="background:rgba(212,168,83,0.1);color:#D4A853;"><?= e($a['category_name']) ?></span></td>
          <td class="px-5 py-3 text-white"><?= e($a['assigned_to_name']) ?><span class="ml-1 text-xs" style="color:#A0A0A0;">(<?= ucfirst($a['assigned_to_type']) ?>)</span></td>
          <td class="px-5 py-3 text-right font-bold text-white"><?= (int)$a['quantity_assigned'] ?></td>
          <td class="px-5 py-3 text-xs" style="color:#C8C8C8;"><?= format_date($a['assignment_date']) ?></td>
          <td class="px-5 py-3"><?= status_badge($a['status']) ?></td>
          <td class="px-5 py-3 text-xs" style="color:#C0C0C0;"><?= e($a['assigned_by_name']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
