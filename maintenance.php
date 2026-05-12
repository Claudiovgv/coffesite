<?php
// ---- Manutenção: muda para false para desativar ----
define('MAINTENANCE_MODE', true);

if (!MAINTENANCE_MODE) return;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maint_user'])) {
    if ($_POST['maint_user'] === 'admin' && $_POST['maint_pass'] === 'adminAC') {
        $_SESSION['maint_access'] = true;
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }
    $error = true;
}

if (!empty($_SESSION['maint_access'])) return;

header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: 3600');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Atlantic Crown — Under Maintenance</title>
  <link rel="icon" type="image/png" href="/images/favicon_browser-logo.png" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --dark:  #0d0c0a;
      --gold:  #c9a84c;
      --gold-light: #e8c97e;
      --gold-dim:   #7a6230;
      --text:  #f0ebe0;
      --muted: #9a8a70;
      --brand: 'Times New Roman', Georgia, serif;
    }
    body {
      background: var(--dark);
      color: var(--text);
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .wrap {
      text-align: center;
      padding: 2rem;
      max-width: 480px;
      width: 100%;
    }
    .logo {
      font-family: var(--brand);
      font-size: 1.5rem;
      letter-spacing: 0.18em;
      color: var(--gold);
      margin-bottom: 0.4rem;
    }
    .logo-sub {
      font-size: 0.6rem;
      letter-spacing: 0.3em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 3rem;
    }
    .divider {
      width: 40px; height: 1px;
      background: var(--gold-dim);
      margin: 0 auto 2.5rem;
    }
    h1 {
      font-family: 'Georgia', serif;
      font-size: clamp(1.4rem, 4vw, 1.9rem);
      font-weight: 400;
      letter-spacing: 0.06em;
      color: var(--text);
      margin-bottom: 0.75rem;
    }
    p {
      font-size: 0.85rem;
      color: var(--muted);
      line-height: 1.7;
      margin-bottom: 2.5rem;
    }

    /* Modal trigger */
    .btn-access {
      display: inline-block;
      padding: 0.75rem 2rem;
      background: transparent;
      border: 1px solid var(--gold-dim);
      color: var(--gold);
      font-size: 0.72rem;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.3s, border-color 0.3s;
    }
    .btn-access:hover { background: rgba(201,168,76,0.08); border-color: var(--gold); }

    /* Overlay */
    .overlay {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.75);
      z-index: 100;
      align-items: center;
      justify-content: center;
    }
    .overlay.open { display: flex; }

    /* Modal */
    .modal {
      background: #161410;
      border: 1px solid rgba(201,168,76,0.25);
      padding: 2.5rem;
      width: 100%;
      max-width: 360px;
      position: relative;
    }
    .modal::before {
      content: '';
      position: absolute; top: 0; left: 0;
      width: 24px; height: 24px;
      border-top: 1px solid var(--gold);
      border-left: 1px solid var(--gold);
    }
    .modal::after {
      content: '';
      position: absolute; bottom: 0; right: 0;
      width: 24px; height: 24px;
      border-bottom: 1px solid var(--gold);
      border-right: 1px solid var(--gold);
    }
    .modal-title {
      font-family: 'Georgia', serif;
      font-size: 1.1rem;
      color: var(--gold);
      letter-spacing: 0.08em;
      margin-bottom: 1.75rem;
      text-align: center;
    }
    .field { margin-bottom: 1rem; }
    .field label {
      display: block;
      font-size: 0.65rem;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 0.4rem;
    }
    .field input {
      width: 100%;
      background: #0d0c0a;
      border: 1px solid var(--gold-dim);
      color: var(--text);
      padding: 0.65rem 0.9rem;
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.25s;
    }
    .field input:focus { border-color: var(--gold); }
    .error-msg {
      font-size: 0.75rem;
      color: #c0674a;
      margin-bottom: 1rem;
      text-align: center;
    }
    .btn-login {
      width: 100%;
      padding: 0.8rem;
      background: var(--gold);
      color: #0d0c0a;
      border: none;
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      cursor: pointer;
      margin-top: 0.5rem;
      transition: background 0.25s;
    }
    .btn-login:hover { background: var(--gold-light); }
    .btn-close {
      position: absolute; top: 0.9rem; right: 1rem;
      background: none; border: none;
      color: var(--muted); font-size: 1.1rem;
      cursor: pointer; line-height: 1;
    }
    .btn-close:hover { color: var(--text); }
  </style>
</head>
<body>

<div class="wrap">
  <div class="logo">Atlantic Crown</div>
  <div class="logo-sub">Coffee &amp; Heritage</div>
  <div class="divider"></div>
  <h1>Under Maintenance</h1>
  <p>We are making improvements to bring you a better experience.<br>We will be back shortly.</p>
  <button class="btn-access" onclick="document.getElementById('overlay').classList.add('open')">
    Staff Access
  </button>
</div>

<div class="overlay" id="overlay">
  <div class="modal">
    <button class="btn-close" onclick="document.getElementById('overlay').classList.remove('open')">&times;</button>
    <div class="modal-title">Staff Access</div>
    <form method="POST">
      <?php if (!empty($error)): ?>
        <div class="error-msg">Invalid credentials.</div>
      <?php endif; ?>
      <div class="field">
        <label for="u">Username</label>
        <input id="u" type="text" name="maint_user" autocomplete="username" required />
      </div>
      <div class="field">
        <label for="p">Password</label>
        <input id="p" type="password" name="maint_pass" autocomplete="current-password" required />
      </div>
      <button type="submit" class="btn-login">Enter</button>
    </form>
  </div>
</div>

<?php if (!empty($error)): ?>
<script>document.getElementById('overlay').classList.add('open');</script>
<?php endif; ?>

</body>
</html>
<?php exit; ?>
