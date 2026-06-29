<?php
ob_start();
$error = flash('error');
$old   = $_SESSION['_old'] ?? [];
unset($_SESSION['_old']);
?>
<div class="min-h-screen flex">

  <!-- Left — Branding Panel -->
  <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden" style="background:linear-gradient(135deg,#0D0D0D 0%,#111111 100%);">
    <div class="absolute inset-0 pointer-events-none" style="background:radial-gradient(ellipse at 20% 50%,rgba(212,168,83,0.06) 0%,transparent 60%);"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 pointer-events-none" style="background:radial-gradient(circle,rgba(212,168,83,0.04) 0%,transparent 70%);"></div>

    <!-- Logo -->
    <div class="flex items-center gap-3 relative z-10">
      <img src="<?= url('logo.jpg') ?>" alt="Enkrix" class="w-14 h-14 rounded-xl" style="object-fit:contain;background:#060606;">
      <div>
        <span class="font-black tracking-widest text-sm" style="color:#D4A853;">ENKRIX</span>
        <p class="text-xs tracking-widest mt-0.5" style="color:#888;">DRIPPING IN ROYALTY</p>
      </div>
    </div>

    <!-- Hero text -->
    <div class="relative z-10 space-y-6">
      <h2 class="text-4xl font-bold leading-tight text-white">
        Smart Inventory<br>
        <span style="background:linear-gradient(135deg,#E8C574,#D4A853,#B8922A);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Control</span><br>
        Redefined
      </h2>
      <p class="text-base leading-relaxed" style="color:#C0C0C0;">
        Enkrix gives your organisation complete visibility over every asset — track stock, manage assignments, and generate reports from one powerful platform.
      </p>

      <!-- Feature list -->
      <ul class="space-y-3">
        <?php $features = [
          'Real-time stock tracking & low-stock alerts',
          'Item assignment with full audit trails',
          'Role-based access control',
          'Asset valuation & CSV reporting',
        ]; ?>
        <?php foreach ($features as $f): ?>
        <li class="flex items-center gap-3 text-sm" style="color:#C0C0C0;">
          <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(212,168,83,0.15);">
            <svg class="w-3 h-3" style="color:#D4A853;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
          </span>
          <?= e($f) ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <p class="text-xs relative z-10" style="color:#C8C8C8;">&copy; <?= date('Y') ?> Avolution AI LTD. All rights reserved.</p>
  </div>

  <!-- Right — Login Form -->
  <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12" style="background:#080808;">
    <div class="w-full max-w-md">

      <!-- Mobile logo -->
      <div class="flex lg:hidden flex-col items-center mb-10 gap-2">
        <img src="<?= url('logo.jpg') ?>" alt="Enkrix" class="w-24 h-24 rounded-2xl" style="object-fit:contain;background:#060606;">
        <p class="text-xs tracking-widest" style="color:#888;">DRIPPING IN ROYALTY</p>
      </div>

      <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Welcome back</h1>
        <p class="mt-1 text-sm" style="color:#C0C0C0;">Sign in to access your inventory dashboard</p>
      </div>

      <?php if ($error): ?>
      <div class="mb-6 flex items-start gap-3 px-4 py-3 rounded-lg text-sm" style="background:#2A0A0A;border:1px solid #7F1D1D;color:#FCA5A5;">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span><?= e($error) ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" action="<?= url('login') ?>" class="space-y-5">
        <?= csrf_field() ?>

        <div>
          <label class="form-label">Email Address</label>
          <input type="email" name="email" value="<?= old('email', $old['email'] ?? '') ?>"
            class="form-input" placeholder="Enter your email" required autocomplete="email">
        </div>

        <div>
          <label class="form-label">Password</label>
          <div class="relative">
            <input type="password" name="password" id="passwordInput"
              class="form-input pr-12" placeholder="••••••••" required autocomplete="current-password">
            <button type="button" onclick="togglePwd()" class="absolute right-3 top-1/2 -translate-y-1/2 p-1" style="color:#D4A853;">
              <svg id="eyeIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-gold w-full py-3 flex items-center justify-center gap-2 mt-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          Sign In
        </button>
      </form>

      <div class="mt-8 pt-6 border-t text-center" style="border-color:rgba(212,168,83,0.08);">
        <p class="text-xs" style="color:#4A4A4A;">&copy; <?= date('Y') ?> Avolution AI LTD. All rights reserved.</p>
      </div>
    </div>
  </div>
</div>

<script>
function togglePwd() {
  const i = document.getElementById('passwordInput');
  i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
<?php
$content = ob_get_clean();
require APP_PATH . '/views/layouts/auth.php';
?>
