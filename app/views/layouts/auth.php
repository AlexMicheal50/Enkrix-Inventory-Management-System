<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Login') ?> — Enkrix IMS</title>
  <meta name="description" content="Enkrix — Dripping in Royalty. Professional Inventory Management System by Avolution AI LTD.">
  <meta name="theme-color" content="#D4A853">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Enkrix IMS">
  <!-- Open Graph -->
  <meta property="og:type"         content="website">
  <meta property="og:site_name"    content="Enkrix IMS">
  <meta property="og:title"        content="Enkrix — Inventory Management System">
  <meta property="og:description"  content="Dripping in Royalty. Professional asset tracking, stock control and audit trails by Avolution AI LTD.">
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
  <link rel="apple-touch-icon" sizes="192x192"    href="<?= url('icon-192.png') ?>">
  <!-- PWA -->
  <link rel="manifest" href="<?= url('manifest.json') ?>">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            gold: { 300:'#F0D080', 400:'#E8C574', 500:'#D4A853', 600:'#B8922A', 700:'#9A7720' },
            dark: { 800:'#0D0D0D', 900:'#080808' }
          },
          fontFamily: { sans: ['Inter','system-ui','sans-serif'] }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { background: #080808; color: #F0F0F0; }
    .form-input {
      width:100%; background:rgba(212,168,83,0.07); border:1px solid rgba(212,168,83,0.45);
      border-radius:8px; padding:11px 14px; color:#FFFFFF; font-size:14px; outline:none;
      transition:all 0.2s; -webkit-appearance:none;
    }
    .form-input:focus { border-color:#D4A853; background:rgba(212,168,83,0.12); box-shadow:0 0 0 3px rgba(212,168,83,0.15); }
    .form-input::placeholder { color:#888; }
    .form-label { display:block; font-size:12px; font-weight:700; color:#D4A853; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px; }
    .btn-gold { display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#D4A853,#B8922A); color:#080808; font-weight:700; border-radius:8px; padding:12px 20px; font-size:14px; transition:all 0.2s; box-shadow:0 2px 12px rgba(212,168,83,0.35); cursor:pointer; border:none; width:100%; }
    .btn-gold:hover { background:linear-gradient(135deg,#F0D080,#D4A853); box-shadow:0 4px 20px rgba(212,168,83,0.5); }
  </style>
</head>
<body class="h-full font-sans antialiased">
  <?= $content ?? '' ?>
</body>
</html>
