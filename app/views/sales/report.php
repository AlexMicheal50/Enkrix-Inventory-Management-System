<?php
$title    = 'Sales Report';
$subtitle = 'Period: ' . date('d M Y', strtotime($from)) . ' – ' . date('d M Y', strtotime($to));
$headerAction = '<a href="' . url('sales/export?period=' . urlencode($period)) . '" class="btn-outline text-xs flex items-center gap-1.5">
  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
  Export CSV
</a>';
ob_start();
?>

<!-- Period Selector -->
<div class="card p-3 mb-6 flex flex-wrap gap-2">
  <?php
  $periods = [
    'today'     => 'Today',
    'week'      => '7 Days',
    '2weeks'    => '14 Days',
    'month'     => '30 Days',
    'quarterly' => 'Quarterly',
    'yearly'    => 'Yearly',
  ];
  foreach ($periods as $key => $label):
  ?>
    <a href="<?= url('sales/report?period=' . $key) ?>"
       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all <?= $period === $key ? 'btn-gold' : 'btn-outline' ?>">
      <?= $label ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="stat-card p-4">
    <p class="text-xs mb-1" style="color:#A0A0A0;">Transactions</p>
    <p class="text-2xl font-bold text-white"><?= number_format((int)($summary['total_transactions'] ?? 0)) ?></p>
  </div>
  <div class="stat-card p-4">
    <p class="text-xs mb-1" style="color:#A0A0A0;">Units Sold</p>
    <p class="text-2xl font-bold text-white"><?= number_format((int)($summary['total_units'] ?? 0)) ?></p>
  </div>
  <div class="stat-card p-4">
    <p class="text-xs mb-1" style="color:#A0A0A0;">Revenue</p>
    <p class="text-2xl font-bold" style="color:#D4A853;"><?= format_currency((float)($summary['total_revenue'] ?? 0)) ?></p>
  </div>
  <div class="stat-card p-4">
    <p class="text-xs mb-1" style="color:#A0A0A0;">Gross Profit</p>
    <?php $gp = (float)($summary['total_profit'] ?? 0); ?>
    <p class="text-2xl font-bold <?= $gp >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= format_currency($gp) ?></p>
  </div>
</div>

<!-- Net Profit row -->
<div class="card p-5 mb-6">
  <div class="flex items-center justify-between flex-wrap gap-4">
    <div class="flex items-center gap-6 flex-wrap">
      <div>
        <p class="text-xs mb-1" style="color:#A0A0A0;">Total Expenses</p>
        <p class="text-xl font-bold text-red-400"><?= format_currency($expenses) ?></p>
      </div>
      <div class="text-2xl font-light" style="color:#444;">−</div>
      <div>
        <p class="text-xs mb-1" style="color:#A0A0A0;">Gross Profit</p>
        <p class="text-xl font-bold text-white"><?= format_currency($gp) ?></p>
      </div>
      <div class="text-2xl font-light" style="color:#444;">=</div>
      <div>
        <p class="text-xs mb-1" style="color:#A0A0A0;">Net Profit / Loss</p>
        <p class="text-xl font-bold <?= $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= format_currency($netProfit) ?></p>
      </div>
    </div>
    <?php if ($netProfit >= 0): ?>
      <span class="badge" style="background:rgba(34,197,94,0.12);color:#22C55E;border:1px solid rgba(34,197,94,0.2);">In Profit</span>
    <?php else: ?>
      <span class="badge" style="background:rgba(239,68,68,0.12);color:#F87171;border:1px solid rgba(239,68,68,0.2);">In Loss</span>
    <?php endif; ?>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

  <!-- Daily chart -->
  <div class="xl:col-span-2 card p-5">
    <h3 class="text-sm font-semibold text-white mb-4">Daily Revenue</h3>
    <?php if (empty($daily)): ?>
      <div class="flex items-center justify-center h-40">
        <p class="text-sm" style="color:#888;">No data for this period.</p>
      </div>
    <?php else: ?>
      <canvas id="dailyChart" height="200"></canvas>
    <?php endif; ?>
  </div>

  <!-- Top items -->
  <div class="card p-5">
    <h3 class="text-sm font-semibold text-white mb-4">Top Items</h3>
    <?php if (empty($topItems)): ?>
      <p class="text-xs text-center py-8" style="color:#888;">No sales this period.</p>
    <?php else: ?>
      <div class="space-y-3">
        <?php foreach ($topItems as $i => $item): ?>
        <div class="flex items-center gap-3">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:rgba(212,168,83,0.15);color:#D4A853;"><?= $i + 1 ?></div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate"><?= e($item['item_name']) ?></p>
            <p class="text-xs" style="color:#A0A0A0;"><?= number_format((int)$item['units']) ?> units · <?= format_currency((float)$item['profit']) ?> profit</p>
          </div>
          <p class="text-sm font-semibold flex-shrink-0" style="color:#D4A853;"><?= format_currency((float)$item['revenue']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Daily breakdown table -->
<?php if (!empty($daily)): ?>
<div class="card">
  <div class="p-4 border-b" style="border-color:rgba(212,168,83,0.08);">
    <h3 class="text-sm font-semibold text-white">Daily Breakdown</h3>
  </div>
  <div class="table-wrap">
    <table class="w-full text-sm">
      <thead>
        <tr style="border-bottom:1px solid rgba(212,168,83,0.08);">
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Date</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Units</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Revenue</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Cost</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Profit</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($daily as $d): ?>
        <tr class="table-row">
          <td class="p-4" style="color:#C8C8C8;"><?= e(date('d M Y', strtotime($d['sale_date']))) ?></td>
          <td class="p-4 text-right text-white"><?= number_format((int)$d['units']) ?></td>
          <td class="p-4 text-right font-semibold" style="color:#D4A853;"><?= format_currency((float)$d['revenue']) ?></td>
          <td class="p-4 text-right" style="color:#C0C0C0;"><?= format_currency((float)$d['cost']) ?></td>
          <td class="p-4 text-right font-semibold <?= (float)$d['profit'] >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= format_currency((float)$d['profit']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
const ctx = document.getElementById('dailyChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?= json_encode(array_map(fn($d) => date('d M', strtotime($d['sale_date'])), $daily)) ?>,
    datasets: [
      {
        label: 'Revenue',
        data: <?= json_encode(array_map(fn($d) => round((float)$d['revenue'], 2), $daily)) ?>,
        backgroundColor: 'rgba(212,168,83,0.6)',
        borderColor: '#D4A853',
        borderWidth: 1,
        borderRadius: 4,
      },
      {
        label: 'Profit',
        data: <?= json_encode(array_map(fn($d) => round((float)$d['profit'], 2), $daily)) ?>,
        backgroundColor: 'rgba(34,197,94,0.4)',
        borderColor: '#22C55E',
        borderWidth: 1,
        borderRadius: 4,
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { labels: { color: '#C0C0C0', font: { size: 11 } } },
      tooltip: { callbacks: { label: ctx => ' £' + ctx.parsed.y.toFixed(2) } }
    },
    scales: {
      x: { ticks: { color: '#888', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
      y: { ticks: { color: '#888', font: { size: 10 }, callback: v => '£' + v }, grid: { color: 'rgba(255,255,255,0.04)' } }
    }
  }
});
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
