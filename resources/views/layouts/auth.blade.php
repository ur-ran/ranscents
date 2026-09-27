<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Ran's Cents — Kelola keuangan Anda dengan cerdas dan elegan">
  <title>@yield('title', 'Masuk') — Ran's Cents</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      /* PASTEL PEACH AS ACCENT */
      --gold-400: #FABFA6;
      --gold-500: #F8AB8C;
      --gold-600: #F69671;
      --gold-700: #D97E5D;
      --gold-glow: rgba(248, 171, 140, 0.2);

      /* SOFT PASTEL LIGHT THEME */
      --bg-900: #F3F4F6;
      --bg-800: #FFFFFF;
      --bg-700: #F9FAFB;
      --bg-600: #F3F4F6;
      --bg-500: #E5E7EB;

      /* DARK TEXT FOR LIGHT THEME */
      --text-100: #111827;
      --text-200: #374151;
      --text-300: #4B5563;
      --text-400: #6B7280;

      --success: #10B981;
      --danger: #EF4444;
      --warning: #F59E0B;
      --info: #3B82F6;
      
      --border: #E5E7EB;
      --radius: 14px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    body.dark-theme {
      --bg-900: #080810;
      --bg-800: #0F0F1E;
      --bg-700: #161628;
      --bg-600: #1E1E34;
      --bg-500: #25253E;
      --text-100: #F5F0E8;
      --text-200: #D4C8B0;
      --text-300: #9A8F78;
      --text-400: #6B6255;
      --border: rgba(255, 255, 255, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-900);
      background-image: radial-gradient(var(--text-300) 1px, transparent 1px);
      background-size: 24px 24px;
      color: var(--text-100);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow-x: hidden;
      overflow-y: auto;
      padding: 20px;
    }

    /* Background decorations */
    body::before {
      content: '';
      position: fixed;
      top: -20%;
      right: -10%;
      width: 60vw;
      height: 60vw;
      background: radial-gradient(circle, var(--gold-glow-strong) 0%, transparent 65%);
      pointer-events: none;
      z-index: 0;
    }

    body::after {
      content: '';
      position: fixed;
      bottom: -20%;
      left: -10%;
      width: 50vw;
      height: 50vw;
      background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    .auth-container {
      width: 100%;
      max-width: 440px;
      padding: 24px;
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(24px); }
      to  { opacity: 1; transform: translateY(0); }
    }

    /* Brand */
    .auth-brand {
      text-align: center;
      margin-bottom: 28px;
    }

    .auth-brand-icon {
      width: 64px;
      height: 64px;
      background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
      border-radius: 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      margin-bottom: 14px;
      box-shadow: 0 8px 30px var(--gold-glow);
    }

    .auth-brand-name {
      font-family: 'Quicksand', serif;
      font-size: 26px;
      font-weight: 700;
      background: linear-gradient(135deg, var(--gold-400), var(--gold-600));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      display: block;
    }

    .auth-brand-tagline {
      font-size: 13px;
      color: var(--text-300);
      margin-top: 4px;
    }

    /* Card */
    .auth-card {
      background: var(--bg-700);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 36px 32px;
      box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    }

    .auth-card-title {
      font-size: 22px;
      font-weight: 700;
      color: var(--text-100);
      margin-bottom: 6px;
    }

    .auth-card-subtitle {
      font-size: 14px;
      color: var(--text-300);
      margin-bottom: 28px;
    }

    /* Form elements */
    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--text-200);
      margin-bottom: 8px;
    }

    .form-control {
      width: 100%;
      background: var(--bg-600);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-100);
      padding: 13px 16px;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      transition: var(--transition);
      outline: none;
    }

    .form-control:focus {
      border-color: var(--gold-500);
      box-shadow: 0 0 0 3px var(--gold-glow);
      background: var(--bg-500);
    }

    .form-control::placeholder { color: var(--text-400); }
    .form-control.is-invalid { border-color: var(--danger); }

    .invalid-feedback {
      font-size: 12px;
      color: var(--danger);
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* Password toggle */
    .input-wrapper {
      position: relative;
    }

    .input-wrapper .form-control {
      padding-right: 48px;
    }

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-300);
      cursor: pointer;
      font-size: 16px;
      padding: 4px;
      transition: color 0.2s;
    }

    .password-toggle:hover { color: var(--gold-400); }

    /* Submit button */
    .btn-auth {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
      color: #080810;
      font-size: 15px;
      font-weight: 700;
      font-family: 'Inter', sans-serif;
      border: none;
      border-radius: var(--radius);
      cursor: pointer;
      transition: var(--transition);
      margin-top: 8px;
      box-shadow: 0 4px 20px var(--gold-glow);
    }

    .btn-auth:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(212,175,55,0.4);
    }

    .btn-auth:active {
      transform: translateY(0);
    }

    /* Links */
    .auth-link {
      color: var(--gold-400);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .auth-link:hover {
      color: var(--gold-300, #FFD54F);
      text-decoration: underline;
    }

    .auth-footer {
      text-align: center;
      margin-top: 22px;
      font-size: 14px;
      color: var(--text-300);
    }

    /* Alerts */
    .alert {
      padding: 14px 18px;
      border-radius: var(--radius);
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      line-height: 1.5;
    }

    .alert-success {
      background: rgba(34,197,94,0.1);
      border: 1px solid rgba(34,197,94,0.3);
      color: #4ADE80;
    }

    .alert-danger {
      background: rgba(239,68,68,0.1);
      border: 1px solid rgba(239,68,68,0.3);
      color: #FCA5A5;
    }

    .alert-info {
      background: rgba(59,130,246,0.1);
      border: 1px solid rgba(59,130,246,0.3);
      color: #93C5FD;
    }

    /* Divider */
    .auth-divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
    }

    .auth-divider::before,
    .auth-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    .auth-divider span {
      font-size: 12px;
      color: var(--text-400);
    }

    /* OTP Input */
    .otp-inputs {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin: 8px 0;
    }

    .otp-input {
      width: 52px;
      height: 60px;
      text-align: center;
      font-size: 24px;
      font-weight: 700;
      background: var(--bg-600);
      border: 2px solid var(--border);
      border-radius: 12px;
      color: var(--gold-400);
      transition: var(--transition);
      outline: none;
      font-family: 'Quicksand', serif;
    }

    .otp-input:focus {
      border-color: var(--gold-500);
      box-shadow: 0 0 0 3px var(--gold-glow);
      background: var(--bg-500);
    }

    .otp-input.filled {
      border-color: var(--gold-500);
      background: rgba(212,175,55,0.08);
    }

    .resend-btn {
      background: none;
      border: none;
      color: var(--gold-400);
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      padding: 0;
      font-family: 'Inter', sans-serif;
      transition: color 0.2s;
    }

    .resend-btn:hover { color: var(--gold-300, #FFD54F); }
    .resend-btn:disabled { color: var(--text-400); cursor: not-allowed; }

    /* Progress dots */
    .auth-steps {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-bottom: 24px;
    }

    .auth-step {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--bg-500);
      transition: var(--transition);
    }

    .auth-step.active {
      width: 24px;
      border-radius: 4px;
      background: linear-gradient(90deg, var(--gold-500), var(--gold-600));
    }

    .auth-step.done {
      background: var(--success);
    }

    /* Remember me */
    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .checkbox-group input[type="checkbox"] {
      width: 16px;
      height: 16px;
      accent-color: var(--gold-500);
      cursor: pointer;
    }

    .checkbox-group label {
      font-size: 13px;
      color: var(--text-200);
      cursor: pointer;
    }

    @media (max-width: 480px) {
      .auth-card { padding: 24px 20px; }
      .otp-input { width: 44px; height: 52px; font-size: 20px; }
    }
  </style>
  @yield('styles')
</head>
<body>
  <div class="auth-container">
    <div class="auth-brand">
      <div class="auth-brand-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 32px; height: 32px;"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
      </div>
      <span class="auth-brand-name">Ran's Cents</span>
      <p class="auth-brand-tagline">Kelola keuangan Anda dengan cerdas</p>
    </div>

    @if(session('success'))
      <div class="alert alert-success"> {{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger"> {{ session('error') }}</div>
    @endif

    @if(session('info'))
      <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
    @endif

    <div class="auth-card">
      @yield('content')
    </div>

    @yield('footer')
  </div>

  @yield('scripts')
  <script>
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-theme');
    }
  </script>
</body>
</html>
