<?php
$title = 'Dashboard';
ob_start();
?>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:rgba(212,168,83,0.15);">
        <svg class="w-5 h-5" style="color:#D4A853;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(212,168,83,0.1);color:#D4A853;">Items</span>
    </div>
    <p class="text-3xl font-bold text-white"><?= number_format((int)($stats['total_items'] ?? 0)) ?></p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Total inventory items</p>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:rgba(34,197,94,0.12);">
        <svg class="w-5 h-5" style="color:#22C55E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(34,197,94,0.1);color:#22C55E;">Stock</span>
    </div>
    <p class="text-3xl font-bold text-white"><?= number_format((int)($stats['total_quantity'] ?? 0) - (int)($stats['total_assigned'] ?? 0)) ?></p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Available units</p>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:rgba(59,130,246,0.12);">
        <svg class="w-5 h-5" style="color:#3B82F6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
      </div>
      <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(59,130,246,0.1);color:#3B82F6;">Out</span>
    </div>
    <p class="text-3xl font-bold text-white"><?= number_format((int)($assignStats['active'] ?? 0)) ?></p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Active assignments</p>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:rgba(212,168,83,0.12);">
        <svg class="w-5 h-5" style="color:#D4A853;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(212,168,83,0.1);color:#D4A853;">Value</span>
    </div>
    <p class="text-3xl font-bold text-white"><?= '£' . number_format((float)($stats['total_value'] ?? 0) / 1000, 0) ?>K</p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Total asset value</p>
  </div>
</div>

<!-- Financial Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:rgba(212,168,83,0.15);">
        <svg class="w-5 h-5" style="color:#D4A853;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      <a href="<?= url('sales/report') ?>" class="text-xs" style="color:#D4A853;">Report →</a>
    </div>
    <p class="text-2xl font-bold" style="color:#D4A853;"><?= format_currency($totalRevenue) ?></p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Total Sales Revenue</p>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:rgba(239,68,68,0.12);">
        <svg class="w-5 h-5" style="color:#F87171;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      </div>
      <a href="<?= url('expenses') ?>" class="text-xs" style="color:#D4A853;">View →</a>
    </div>
    <p class="text-2xl font-bold text-red-400"><?= format_currency($totalExpenses) ?></p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Total Expenses</p>
  </div>

  <div class="stat-card p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="icon-box" style="background:<?= $netProfit >= 0 ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)' ?>;">
        <svg class="w-5 h-5" style="color:<?= $netProfit >= 0 ? '#22C55E' : '#F87171' ?>;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
      </div>
      <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:<?= $netProfit >= 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' ?>;color:<?= $netProfit >= 0 ? '#22C55E' : '#F87171' ?>;"><?= $netProfit >= 0 ? 'Profit' : 'Loss' ?></span>
    </div>
    <p class="text-2xl font-bold <?= $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= format_currency(abs($netProfit)) ?></p>
    <p class="text-xs mt-1" style="color:#A0A0A0;">Net <?= $netProfit >= 0 ? 'Profit' : 'Loss' ?> (All Time)</p>
  </div>
</div>

<!-- Alerts + Activity Row -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

  <!-- Low Stock Alerts -->
  <div class="xl:col-span-1 card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-sm text-white flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
        Low Stock Alerts
      </h3>
      <span class="badge" style="background:rgba(245,158,11,0.15);color:#F59E0B;"><?= count($lowStock) ?></span>
    </div>

    <?php if (empty($lowStock)): ?>
      <div class="text-center py-8">
        <svg class="w-8 h-8 mx-auto mb-2" style="color:#333;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs" style="color:#B0B0B0;">All stock levels are healthy</p>
      </div>
    <?php else: ?>
      <div class="space-y-2">
        <?php foreach (array_slice($lowStock, 0, 6) as $item): ?>
        <a href="<?= url('inventory/' . $item['id']) ?>" class="flex items-center justify-between p-3 rounded-lg transition-colors" style="background:rgba(245,158,11,0.05);border:1px solid rgba(245,158,11,0.12);" onmouseover="this.style.background='rgba(245,158,11,0.1)'" onmouseout="this.style.background='rgba(245,158,11,0.05)'">
          <div class="min-w-0">
            <p class="text-xs font-medium text-white truncate"><?= e($item['name']) ?></p>
            <p class="text-xs mt-0.5" style="color:#A0A0A0;"><?= e($item['location'] ?? 'Unspecified') ?></p>
          </div>
          <div class="text-right flex-shrink-0 ml-2">
            <p class="text-sm font-bold" style="color:#F59E0B;"><?= (int)$item['available'] ?></p>
            <p class="text-xs" style="color:#A0A0A0;">/ <?= (int)$item['quantity'] ?></p>
          </div>
        </a>
        <?php endforeach; ?>
        <?php if (count($lowStock) > 6): ?>
          <a href="<?= url('reports?type=low_stock') ?>" class="block text-center text-xs py-2" style="color:#D4A853;">View all <?= count($lowStock) ?> alerts →</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Recent Activity -->
  <div class="xl:col-span-2 card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-sm text-white">Recent Activity</h3>
      <a href="<?= url('activity') ?>" class="text-xs" style="color:#D4A853;">View all →</a>
    </div>
    <?php if (empty($activity)): ?>
      <p class="text-xs text-center py-8" style="color:#B0B0B0;">No activity yet.</p>
    <?php else: ?>
      <div class="space-y-1">
        <?php
        $actionColors = [
          'created'     => '#22C55E', 'updated' => '#3B82F6', 'deleted' => '#EF4444',
          'assigned'    => '#D4A853', 'returned' => '#A855F7', 'login'   => '#06B6D4',
          'logout'      => '#6B7280', 'activated'=> '#22C55E', 'deactivated' => '#EF4444',
          'system_init' => '#D4A853',
        ];
        foreach ($activity as $log):
          $color = $actionColors[$log['action']] ?? '#6B7280';
        ?>
        <div class="flex items-start gap-3 py-2.5 border-b" style="border-color:rgba(255,255,255,0.04);">
          <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background:<?= $color ?>;"></div>
          <div class="flex-1 min-w-0">
            <p class="text-xs text-white">
              <span class="font-medium"><?= e($log['user_name']) ?></span>
              <span style="color:#A0A0A0;"> <?= e(str_replace('_', ' ', $log['action'])) ?></span>
              <?php if ($log['entity_name']): ?>
                <span class="font-medium" style="color:<?= $color ?>"> <?= e($log['entity_name']) ?></span>
              <?php endif; ?>
            </p>
            <p class="text-xs mt-0.5" style="color:#909090;"><?= e(date('d M Y, g:ia', strtotime($log['created_at']))) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Recent Items + Assignments Row -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

  <!-- Recently Added Items -->
  <div class="card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-sm text-white">Recently Added</h3>
      <a href="<?= url('inventory') ?>" class="text-xs" style="color:#D4A853;">View all →</a>
    </div>
    <div class="space-y-2">
      <?php foreach ($recentItems as $item): ?>
      <a href="<?= url('inventory/' . $item['id']) ?>" class="flex items-center gap-3 p-3 rounded-lg table-row">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-bold" style="background:rgba(212,168,83,0.12);color:#D4A853;">
          <?= strtoupper(substr($item['name'], 0, 1)) ?>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-white truncate"><?= e($item['name']) ?></p>
          <p class="text-xs" style="color:#A0A0A0;"><?= e($item['category_name']) ?></p>
        </div>
        <div class="text-right flex-shrink-0">
          <p class="text-sm font-semibold text-white"><?= (int)$item['quantity'] ?></p>
          <p class="text-xs" style="color:#A0A0A0;"><?= e($item['unit'] ?: 'pcs') ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Active Assignments -->
  <div class="card p-5">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-semibold text-sm text-white">Active Assignments</h3>
      <a href="<?= url('assignments') ?>" class="text-xs" style="color:#D4A853;">View all →</a>
    </div>
    <?php if (empty($recentActive)): ?>
      <div class="text-center py-8">
        <p class="text-xs" style="color:#B0B0B0;">No active assignments.</p>
      </div>
    <?php else: ?>
      <div class="space-y-2">
        <?php foreach ($recentActive as $a): ?>
        <div class="flex items-center gap-3 p-3 rounded-lg" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.04);">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.12);">
            <svg class="w-4 h-4" style="color:#3B82F6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/></svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-white truncate"><?= e($a['item_name']) ?></p>
            <p class="text-xs" style="color:#A0A0A0;"><?= e($a['assigned_to_name']) ?> · <?= e(ucfirst($a['assigned_to_type'])) ?></p>
          </div>
          <div class="flex-shrink-0 text-right">
            <p class="text-sm font-semibold" style="color:#3B82F6;"><?= (int)$a['quantity_assigned'] ?></p>
            <?php if ($a['expected_return_date']): ?>
              <p class="text-xs" style="color:#A0A0A0;">due <?= format_date($a['expected_return_date'], 'd M') ?></p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
