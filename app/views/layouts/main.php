<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Dashboard') ?> — Enkrix IMS</title>
  <meta name="description" content="Enkrix — Dripping in Royalty. Professional Inventory Management System by Avolution AI LTD.">
  <meta name="theme-color" content="#D4A853">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Enkrix IMS">
  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:site_name"   content="Enkrix IMS">
  <meta property="og:title"       content="Enkrix — Inventory Management System">
  <meta property="og:description" content="Dripping in Royalty. Professional asset tracking, stock control and audit trails by Avolution AI LTD.">
  <meta property="og:image"        content="<?= url('og.png?v=4') ?>">
  <meta property="og:image:type"   content="image/png">
  <meta property="og:image:width"  content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt"    content="Enkrix Inventory Management System — Dripping in Royalty">
  <meta property="og:url"          content="<?= url('') ?>">
  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="Enkrix — Inventory Management System">
  <meta name="twitter:description" content="Dripping in Royalty. Professional asset tracking by Avolution AI LTD.">
  <meta name="twitter:image"       content="<?= url('og.png?v=4') ?>">
  <meta name="twitter:image:alt"   content="Enkrix IMS — Dripping in Royalty">
  <!-- Favicons -->
  <link rel="icon" type="image/jpeg" href="<?= url('logo.jpg') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= url('icon-32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= url('icon-16.png') ?>">
  <link rel="apple-touch-icon" sizes="192x192" href="<?= url('icon-192.png') ?>">
  <!-- PWA -->
  <link rel="manifest" href="<?= url('manifest.json') ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            gold: { 200:'#F5DFA0', 300:'#F0D080', 400:'#E8C574', 500:'#D4A853', 600:'#B8922A', 700:'#9A7720', 800:'#7D601C' },
            ink:  { 50:'#F5F5F5', 100:'#E0E0E0', 200:'#ADADAD', 300:'#888888', 400:'#555555', 500:'#333333', 600:'#222222', 700:'#1A1A1A', 800:'#111111', 900:'#0D0D0D', 950:'#080808' }
          },
          fontFamily: { sans: ['Inter','system-ui','sans-serif'] },
          screens: { xs: '375px' }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    :root {
      --gold:#D4A853; --gold-light:#E8C574; --gold-dark:#B8922A;
      --ink-950:#080808; --ink-900:#0D0D0D; --ink-800:#111111;
      --ink-700:#1A1A1A; --sidebar-w:256px;
    }
    * { scrollbar-width:thin; scrollbar-color:var(--gold-dark) var(--ink-800); }
    ::-webkit-scrollbar { width:5px; height:5px; }
    ::-webkit-scrollbar-track { background:var(--ink-800); }
    ::-webkit-scrollbar-thumb { background:var(--gold-dark); border-radius:3px; }
    body { background:#080808; color:#F0F0F0; overflow-x:hidden; }

    /* ── SIDEBAR ── */
    #sidebar {
      position:fixed; top:0; left:0; height:100vh; width:var(--sidebar-w);
      background:#0D0D0D; border-right:1px solid rgba(212,168,83,0.12);
      display:flex; flex-direction:column; z-index:60;
      transform:translateX(-100%); transition:transform 0.28s cubic-bezier(.4,0,.2,1);
      overflow-y:auto;
    }
    #sidebar.open { transform:translateX(0); }
    @media (min-width:1024px) {
      #sidebar { transform:translateX(0); position:sticky; height:100vh; }
    }

    /* ── OVERLAY ── */
    #sidebar-overlay {
      display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65);
      backdrop-filter:blur(3px); z-index:50;
    }
    #sidebar-overlay.open { display:block; }
    @media (min-width:1024px) { #sidebar-overlay { display:none !important; } }

    /* ── MAIN OFFSET ── */
    @media (min-width:1024px) {
      #main-wrapper { margin-left:0; }
    }

    /* ── NAV LINKS ── */
    .sidebar-link {
      display:flex; align-items:center; gap:12px; padding:10px 16px;
      border-radius:8px; color:#C0C0C0; font-size:14px; font-weight:500;
      transition:all 0.2s; border-left:2px solid transparent; text-decoration:none;
    }
    .sidebar-link:hover { color:#D4A853; background:rgba(212,168,83,0.08); border-left-color:rgba(212,168,83,0.4); }
    .sidebar-link.active { color:#E8C574; background:rgba(212,168,83,0.14); border-left-color:#D4A853; }
    .sidebar-link svg { width:18px; height:18px; flex-shrink:0; }

    /* ── CARDS ── */
    .card { background:#111111; border:1px solid rgba(212,168,83,0.18); border-radius:12px; }
    .card-hover:hover { border-color:rgba(212,168,83,0.4); }
    .stat-card { background:linear-gradient(135deg,#111111,#0D0D0D); border:1px solid rgba(212,168,83,0.18); border-radius:14px; }
    .stat-card .icon-box { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; }

    /* ── BUTTONS ── */
    .btn-gold {
      display:inline-flex; align-items:center; justify-content:center;
      background:linear-gradient(135deg,#D4A853,#B8922A); color:#080808;
      font-weight:700; border-radius:8px; padding:8px 18px; font-size:14px;
      transition:all 0.2s; box-shadow:0 2px 12px rgba(212,168,83,0.35);
      cursor:pointer; text-decoration:none; border:none; white-space:nowrap;
    }
    .btn-gold:hover { background:linear-gradient(135deg,#F0D080,#D4A853); box-shadow:0 4px 20px rgba(212,168,83,0.5); transform:translateY(-1px); }
    .btn-gold:active { transform:translateY(0); }
    .btn-outline {
      display:inline-flex; align-items:center; justify-content:center;
      border:1px solid rgba(212,168,83,0.5); color:#E8C574; font-weight:600;
      border-radius:8px; padding:8px 18px; font-size:14px; transition:all 0.2s;
      text-decoration:none; white-space:nowrap; background:transparent;
    }
    .btn-outline:hover { background:rgba(212,168,83,0.12); border-color:#D4A853; color:#F0D080; }
    .btn-danger { background:#7F1D1D; color:#FCA5A5; font-weight:600; border-radius:8px; padding:6px 14px; font-size:13px; transition:all 0.2s; border:1px solid #991B1B; cursor:pointer; }
    .btn-danger:hover { background:#991B1B; }

    /* ── FORMS ── */
    .form-input {
      width:100%; background:rgba(212,168,83,0.06); border:1px solid rgba(212,168,83,0.45);
      border-radius:8px; padding:10px 14px; color:#FFFFFF; font-size:14px; outline:none;
      transition:all 0.2s; -webkit-appearance:none; appearance:none;
    }
    .form-input:focus { border-color:#D4A853; background:rgba(212,168,83,0.10); box-shadow:0 0 0 3px rgba(212,168,83,0.15); }
    .form-input::placeholder { color:#888; }
    select.form-input option { background:#1A1A1A; color:#F0F0F0; }
    .form-label { display:block; font-size:12px; font-weight:700; color:#D4A853; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px; }

    /* ── TABLE ── */
    .table-row { border-bottom:1px solid rgba(212,168,83,0.08); transition:background 0.15s; }
    .table-row:hover { background:rgba(212,168,83,0.06); }
    .table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }

    /* ── BADGE ── */
    .badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:100px; font-size:11px; font-weight:600; }

    /* ── MODAL ── */
    .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:70; backdrop-filter:blur(4px); display:none; align-items:center; justify-content:center; padding:16px; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:#111111; border:1px solid rgba(212,168,83,0.2); border-radius:16px; padding:24px; width:100%; max-width:520px; max-height:92vh; overflow-y:auto; }

    /* ── TOAST ── */
    .toast { position:fixed; top:16px; right:16px; left:16px; z-index:9999; padding:14px 18px; border-radius:10px; font-size:14px; font-weight:500; box-shadow:0 8px 32px rgba(0,0,0,0.5); display:flex; align-items:flex-start; gap:10px; animation:slideIn 0.3s ease; }
    @media (min-width:480px) { .toast { left:auto; min-width:300px; max-width:420px; } }
    .toast-success { background:#0A2416; border:1px solid #166534; color:#4ADE80; }
    .toast-error   { background:#2A0A0A; border:1px solid #991B1B; color:#FCA5A5; }
    .toast-info    { background:#0A1628; border:1px solid #1E3A8A; color:#93C5FD; }
    @keyframes slideIn { from { transform:translateY(-20px); opacity:0; } to { transform:translateY(0); opacity:1; } }

    .gold-text { background:linear-gradient(135deg,#E8C574,#D4A853,#B8922A); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }

    /* ── MOBILE TYPOGRAPHY ── */
    @media (max-width:640px) {
      .text-3xl { font-size:1.5rem; }
      .px-6 { padding-left:1rem; padding-right:1rem; }
      .p-6  { padding:1rem; }
      .p-7  { padding:1.25rem; }
      .gap-6 { gap:1rem; }
    }
  </style>
</head>
<body class="h-full font-sans antialiased" id="app">
<div class="flex h-full min-h-screen">

<!-- ═══════════════════════════════════════
     MOBILE OVERLAY
════════════════════════════════════════ -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ -->
<aside id="sidebar">

  <!-- Logo + Mobile Close -->
  <div class="px-4 py-3 border-b flex items-center justify-between" style="border-color:rgba(212,168,83,0.12);">
    <a href="<?= url('dashboard') ?>" class="flex items-center gap-2 min-w-0">
      <img src="<?= url('logo.jpg') ?>" alt="Enkrix Logo" class="w-10 h-10 rounded-lg flex-shrink-0" style="object-fit:contain;background:#060606;">
      <div class="min-w-0">
        <div class="font-black text-sm tracking-widest leading-none" style="color:#D4A853;">ENKRIX</div>
        <div class="text-xs tracking-wider leading-none mt-0.5" style="color:#888;">DRIPPING IN ROYALTY</div>
      </div>
    </a>
    <!-- Mobile close button -->
    <button onclick="closeSidebar()" class="lg:hidden p-2 rounded-lg" style="color:#A0A0A0;background:rgba(255,255,255,0.05);" aria-label="Close menu">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 px-3 py-4 space-y-1" role="navigation">
    <p class="px-3 text-xs font-semibold uppercase tracking-widest mb-2" style="color:#8A8A8A;">Main</p>

    <a href="<?= url('dashboard') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'dashboard') || rtrim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH)??'','/') === '' ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/></svg>
      Dashboard
    </a>

    <a href="<?= url('inventory') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/inventory') ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      Inventory
    </a>

    <a href="<?= url('categories') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/categories') ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
      Categories
    </a>

    <a href="<?= url('assignments') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/assignments') ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
      Assignments
    </a>

    <a href="<?= url('sales') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/sales') ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      Sales
    </a>

    <a href="<?= url('expenses') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/expenses') ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      Expenses
    </a>

    <a href="<?= url('reports') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/reports') ? 'active' : '' ?>" onclick="closeSidebar()">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Reports
    </a>

    <?php if (has_role('Admin')): ?>
    <div class="pt-3">
      <p class="px-3 text-xs font-semibold uppercase tracking-widest mb-2" style="color:#8A8A8A;">Admin</p>
      <a href="<?= url('users') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/users') ? 'active' : '' ?>" onclick="closeSidebar()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Users
      </a>
      <a href="<?= url('activity') ?>" class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'],'/activity') ? 'active' : '' ?>" onclick="closeSidebar()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Audit Log
      </a>
    </div>
    <?php endif; ?>
  </nav>

  <!-- User Profile + Copyright -->
  <div class="px-4 py-4 border-t" style="border-color:rgba(212,168,83,0.12);">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0" style="background:rgba(212,168,83,0.18);color:#D4A853;">
        <?= strtoupper(substr(auth()['name'] ?? 'U', 0, 1)) ?>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold truncate text-white"><?= e(auth()['name'] ?? '') ?></p>
        <p class="text-xs truncate" style="color:#D4A853;"><?= e(auth()['role'] ?? '') ?></p>
      </div>
    </div>
    <form method="POST" action="<?= url('logout') ?>" class="mt-3">
      <?= csrf_field() ?>
      <button type="submit" class="w-full text-left text-xs flex items-center gap-2 px-3 py-2 rounded-lg transition-colors" style="color:#A0A0A0;" onmouseover="this.style.color='#EF4444';this.style.background='rgba(239,68,68,0.08)'" onmouseout="this.style.color='#A0A0A0';this.style.background='transparent'">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Sign Out
      </button>
    </form>
    <p class="text-center mt-3 text-xs" style="color:#4A4A4A;">&copy; <?= date('Y') ?> Avolution AI LTD.</p>
  </div>
</aside>

<!-- ═══════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════ -->
<div id="main-wrapper" class="flex-1 flex flex-col min-h-screen min-w-0 lg:ml-0" style="width:100%;">

  <!-- TOP BAR -->
  <header class="sticky top-0 z-40 flex items-center gap-3 px-4 sm:px-6 py-3 sm:py-4" style="background:rgba(8,8,8,0.95); backdrop-filter:blur(12px); border-bottom:1px solid rgba(212,168,83,0.10);">

    <!-- Hamburger (mobile only) -->
    <button onclick="openSidebar()" class="lg:hidden flex-shrink-0 p-2 rounded-lg" style="background:rgba(212,168,83,0.08);color:#D4A853;" aria-label="Open menu">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <!-- Page title -->
    <div class="flex-1 min-w-0">
      <h1 class="text-base sm:text-lg font-semibold text-white truncate"><?= e($title ?? 'Dashboard') ?></h1>
      <?php if (!empty($subtitle)): ?>
        <p class="text-xs mt-0.5 truncate hidden sm:block" style="color:#B0B0B0;"><?= e($subtitle) ?></p>
      <?php endif; ?>
    </div>

    <!-- Right: date + optional action -->
    <div class="flex items-center gap-2 flex-shrink-0">
      <span class="text-xs px-2 py-1 rounded hidden sm:inline-block" style="background:rgba(212,168,83,0.1);color:#D4A853;">
        <?= date('d M Y') ?>
      </span>
      <?php if (!empty($headerAction)): ?>
        <?= $headerAction ?>
      <?php endif; ?>
    </div>
  </header>

  <!-- PAGE CONTENT -->
  <main class="flex-1 p-4 sm:p-6 overflow-auto">
    <?= $content ?? '' ?>
  </main>

  <!-- FOOTER (mobile) -->
  <footer class="lg:hidden text-center py-3 text-xs" style="color:#3A3A3A;border-top:1px solid rgba(212,168,83,0.06);">
    &copy; <?= date('Y') ?> Avolution AI LTD. All rights reserved.
  </footer>
</div>

</div><!-- end flex wrapper -->

<!-- TOAST -->
<div id="toast-container" style="position:fixed;top:16px;right:16px;left:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;pointer-events:none;">
</div>

<?php $successMsg = flash('success'); $errorMsg = flash('error'); $infoMsg = flash('info'); ?>
<?php if ($successMsg || $errorMsg || $infoMsg): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    <?php if ($successMsg): ?>showToast(<?= json_encode($successMsg) ?>, 'success');<?php endif; ?>
    <?php if ($errorMsg):   ?>showToast(<?= json_encode($errorMsg)   ?>, 'error');  <?php endif; ?>
    <?php if ($infoMsg):    ?>showToast(<?= json_encode($infoMsg)    ?>, 'info');   <?php endif; ?>
  });
</script>
<?php endif; ?>

<script>
/* ── SIDEBAR ── */
function openSidebar() {
  document.getElementById('sidebar').classList.add('open');
  document.getElementById('sidebar-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('open');
  document.body.style.overflow = '';
}

/* ── TOAST ── */
function showToast(message, type) {
  const icons = {
    success:'<svg style="width:16px;height:16px;flex-shrink:0;margin-top:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
    error:  '<svg style="width:16px;height:16px;flex-shrink:0;margin-top:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
    info:   '<svg style="width:16px;height:16px;flex-shrink:0;margin-top:2px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
  };
  const t = document.createElement('div');
  t.className = 'toast toast-' + type;
  t.style.cssText = 'pointer-events:auto;max-width:420px;margin-left:auto;';
  t.innerHTML = (icons[type] || '') + '<span>' + message + '</span>';
  document.getElementById('toast-container').appendChild(t);
  setTimeout(() => {
    t.style.transition = 'opacity 0.3s,transform 0.3s';
    t.style.opacity = '0'; t.style.transform = 'translateY(-8px)';
    setTimeout(() => t.remove(), 320);
  }, 4500);
}

/* ── MODAL ── */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
    closeSidebar();
  }
});
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});

/* ── Close sidebar on desktop resize ── */
window.addEventListener('resize', () => {
  if (window.innerWidth >= 1024) closeSidebar();
});
</script>
</body>
</html>
