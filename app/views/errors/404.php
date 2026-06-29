<?php
$title = '404 — Not Found';
ob_start();
?>
<div class="flex flex-col items-center justify-center min-h-96 text-center">
  <p class="text-7xl font-black mb-4" style="color:#1A1A1A;">404</p>
  <h2 class="text-xl font-semibold text-white mb-2">Page not found</h2>
  <p class="text-sm mb-6" style="color:#A0A0A0;">The page you're looking for doesn't exist or was moved.</p>
  <a href="<?= url('dashboard') ?>" class="btn-gold">Back to Dashboard</a>
</div>
<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/main.php';
?>
