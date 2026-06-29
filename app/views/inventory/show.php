<?php
$title    = e($item['name']);
$subtitle = e($item['category_name']);
ob_start();
?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

  <!-- Left: Item Details -->
  <div class="xl:col-span-2 space-y-6">

    <!-- Header card -->
    <div class="card p-6">
      <div class="flex items-start gap-5">
        <?php if ($item['image']): ?>
          <img src="<?= url('uploads/' . $item['image']) ?>" class="w-24 h-24 rounded-xl object-cover flex-shrink-0">
        <?php else: ?>
          <div class="w-24 h-24 rounded-xl flex items-center justify-center flex-shrink-0 text-3xl font-bold" style="background:rgba(212,168,83,0.1);color:#D4A853;"><?= strtoupper(substr($item['name'],0,1)) ?></div>
        <?php endif; ?>
        <div class="flex-1 min-w-0">
          <h2 class="text-xl font-bold text-white"><?= e($item['name']) ?></h2>
          <span class="badge mt-2" style="background:<?= e($item['category_color']) ?>20;color:<?= e($item['category_color']) ?>;border:1px solid <?= e($item['category_color']) ?>30;"><?= e($item['category_name']) ?></span>
          <?php if ($item['description']): ?>
            <p class="text-sm mt-3 leading-relaxed" style="color:#C8C8C8;"><?= e($item['description']) ?></p>
          <?php endif; ?>
          <?php if (can('inventory.*')): ?>
          <div class="flex gap-3 mt-4">
            <a href="<?= url('inventory/' . $item['id'] . '/edit') ?>" class="btn-gold text-sm flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit Item
            </a>
            <a href="<?= url('assignments?open=assign&item_id=' . $item['id']) ?>" class="btn-outline text-sm">Assign Item</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Details grid -->
    <div class="card p-6">
      <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Item Details</h3>
      <dl class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <?php
        $details = [
          'Total Quantity'     => number_format((int)$item['quantity']) . ' ' . ($item['unit'] ?: 'pcs'),
          'Assigned'           => number_format((int)$item['quantity_assigned']),
          'Available'          => number_format((int)$item['available']),
          'Condition'          => null, // rendered separately
          'Location'           => $item['location'] ?: '—',
          'Purchase Date'      => format_date($item['purchase_date']),
          'Unit Cost'          => format_currency((float)$item['cost']),
          'Selling Price'      => format_currency((float)($item['selling_price'] ?? 0)),
          'Total Value'        => format_currency((float)$item['cost'] * (int)$item['quantity']),
          'Low Stock Alert'    => '≤ ' . $item['low_stock_threshold'] . ' units',
        ];
        ?>
        <?php foreach ($details as $label => $val): ?>
        <div class="py-2">
          <dt class="text-xs font-medium uppercase tracking-wider" style="color:#B0B0B0;"><?= e($label) ?></dt>
          <dd class="mt-1 text-sm font-semibold text-white">
            <?php if ($label === 'Condition'): ?>
              <?= condition_badge($item['condition_status']) ?>
            <?php elseif ($label === 'Available'): ?>
              <span class="<?= (int)$item['available'] <= (int)$item['low_stock_threshold'] ? 'text-amber-400' : 'text-emerald-400' ?>"><?= $val ?></span>
            <?php else: ?>
              <?= e($val) ?>
            <?php endif; ?>
          </dd>
        </div>
        <?php endforeach; ?>
        <?php if ($item['barcode']): ?>
        <div class="py-2">
          <dt class="text-xs font-medium uppercase tracking-wider" style="color:#B0B0B0;">Barcode</dt>
          <dd class="mt-1 font-mono text-sm text-white"><?= e($item['barcode']) ?></dd>
        </div>
        <?php endif; ?>
      </dl>
    </div>
  </div>

  <!-- Right: Stock Visual -->
  <div class="space-y-6">
    <div class="card p-6">
      <h3 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#D4A853;">Stock Status</h3>
      <?php
        $total    = max(1, (int)$item['quantity']);
        $assigned = (int)$item['quantity_assigned'];
        $pct      = round(($assigned / $total) * 100);
      ?>
      <div class="space-y-3 mb-5">
        <div class="flex justify-between text-xs mb-1">
          <span style="color:#C8C8C8;">Utilization</span>
          <span class="font-semibold" style="color:#D4A853;"><?= $pct ?>%</span>
        </div>
        <div class="w-full h-2 rounded-full" style="background:#1A1A1A;">
          <div class="h-2 rounded-full transition-all" style="width:<?= min(100, $pct) ?>%;background:linear-gradient(90deg,#D4A853,#B8922A);"></div>
        </div>
      </div>

      <div class="space-y-2">
        <div class="flex items-center justify-between p-3 rounded-lg" style="background:#1A1A1A;">
          <span class="text-xs" style="color:#C0C0C0;">Total</span>
          <span class="text-sm font-bold text-white"><?= number_format($total) ?></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg" style="background:#1A1A1A;">
          <span class="text-xs" style="color:#C0C0C0;">Assigned</span>
          <span class="text-sm font-bold" style="color:#3B82F6;"><?= number_format($assigned) ?></span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-lg" style="background:#1A1A1A;">
          <span class="text-xs" style="color:#C0C0C0;">Available</span>
          <span class="text-sm font-bold <?= (int)$item['available'] <= (int)$item['low_stock_threshold'] ? 'text-amber-400' : 'text-emerald-400' ?>"><?= number_format((int)$item['available']) ?></span>
        </div>
      </div>

      <?php if ((int)$item['available'] <= (int)$item['low_stock_threshold']): ?>
      <div class="mt-4 flex items-center gap-2 p-3 rounded-lg" style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);">
        <svg class="w-4 h-4 flex-shrink-0" style="color:#F59E0B;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <p class="text-xs" style="color:#F59E0B;">Low stock alert triggered</p>
      </div>
      <?php endif; ?>
    </div>

    <div class="card p-5">
      <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#B0B0B0;">Added On</p>
      <p class="text-sm text-white"><?= format_date($item['created_at'], 'd M Y') ?></p>
      <p class="text-xs mt-3 font-semibold uppercase tracking-wider mb-1" style="color:#B0B0B0;">Last Updated</p>
      <p class="text-sm text-white"><?= format_date($item['updated_at'], 'd M Y') ?></p>
    </div>
  </div>
</div>

<!-- Stock Movement History -->
<div class="card mt-6">
  <div class="p-5 border-b flex items-center justify-between" style="border-color:rgba(212,168,83,0.08);">
    <h3 class="text-sm font-semibold text-white flex items-center gap-2">
      <svg class="w-4 h-4" style="color:#D4A853;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Stock Movement History
    </h3>
    <span class="text-xs" style="color:#888;">Last 20 events</span>
  </div>
  <?php if (empty($movements)): ?>
    <div class="text-center py-8">
      <p class="text-sm" style="color:#888;">No stock movements recorded yet.</p>
    </div>
  <?php else: ?>
  <div class="table-wrap">
    <table class="w-full text-sm">
      <thead>
        <tr style="border-bottom:1px solid rgba(212,168,83,0.08);">
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Date & Time</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Type</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider" style="color:#D4A853;">Change</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#D4A853;">Before</th>
          <th class="text-right p-4 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#D4A853;">After</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#D4A853;">Notes</th>
          <th class="text-left p-4 text-xs font-semibold uppercase tracking-wider hidden lg:table-cell" style="color:#D4A853;">By</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $typeColors = [
          'stock_in'   => ['bg' => 'rgba(34,197,94,0.12)',   'color' => '#22C55E',  'label' => 'Stock In'],
          'stock_out'  => ['bg' => 'rgba(239,68,68,0.12)',   'color' => '#F87171',  'label' => 'Stock Out'],
          'sale'       => ['bg' => 'rgba(212,168,83,0.12)',  'color' => '#D4A853',  'label' => 'Sale'],
          'assignment' => ['bg' => 'rgba(59,130,246,0.12)',  'color' => '#3B82F6',  'label' => 'Assigned'],
          'return'     => ['bg' => 'rgba(168,85,247,0.12)',  'color' => '#A855F7',  'label' => 'Returned'],
          'adjustment' => ['bg' => 'rgba(245,158,11,0.12)',  'color' => '#F59E0B',  'label' => 'Adjusted'],
        ];
        foreach ($movements as $mv):
          $tc = $typeColors[$mv['movement_type']] ?? ['bg' => 'rgba(255,255,255,0.05)', 'color' => '#888', 'label' => ucfirst($mv['movement_type'])];
        ?>
        <tr class="table-row">
          <td class="p-4 whitespace-nowrap text-xs" style="color:#C8C8C8;"><?= e(date('d M Y, g:ia', strtotime($mv['created_at']))) ?></td>
          <td class="p-4">
            <span class="badge" style="background:<?= $tc['bg'] ?>;color:<?= $tc['color'] ?>;border:1px solid <?= $tc['color'] ?>30;">
              <?= $tc['label'] ?>
            </span>
          </td>
          <td class="p-4 text-right font-bold <?= (int)$mv['quantity_change'] > 0 ? 'text-emerald-400' : 'text-red-400' ?>">
            <?= (int)$mv['quantity_change'] > 0 ? '+' : '' ?><?= (int)$mv['quantity_change'] ?>
          </td>
          <td class="p-4 text-right hidden sm:table-cell" style="color:#A0A0A0;"><?= number_format((int)$mv['quantity_before']) ?></td>
          <td class="p-4 text-right font-semibold hidden sm:table-cell text-white"><?= number_format((int)$mv['quantity_after']) ?></td>
          <td class="p-4 hidden md:table-cell text-xs max-w-xs truncate" style="color:#888;"><?= e($mv['notes'] ?? '—') ?></td>
          <td class="p-4 hidden lg:table-cell text-xs" style="color:#A0A0A0;"><?= e($mv['by_name'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
