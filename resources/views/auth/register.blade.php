@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div class="auth-steps">
  <div class="auth-step active"></div>
  <div class="auth-step"></div>
  <div class="auth-step"></div>
</div>

<div class="auth-card-title">Buat Akun Baru </div>
<div class="auth-card-subtitle">Mulai kelola keuangan Anda hari ini</div>

<form action="{{ route('auth.register.post') }}" method="POST" id="register-form">
  @csrf

  <div class="form-group">
    <label class="form-label" for="name"> Nama Lengkap</label>
    <input type="text" id="name" name="name"
        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
        value="{{ old('name') }}"
        placeholder="Nama lengkap Anda"
        autofocus required>
    @error('name')
      <div class="invalid-feedback">️ {{ $message }}</div>
    @enderror
  </div>

  <div class="form-group">
    <label class="form-label" for="email"> Alamat Email</label>
    <input type="email" id="email" name="email"
        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email') }}"
        placeholder="email@contoh.com"
        required>
    @error('email')
      <div class="invalid-feedback">️ {{ $message }}</div>
    @enderror
    <div style="font-size:11px;color:var(--text-400);margin-top:5px;">
       Kode OTP verifikasi akan dikirim ke email ini
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="password"> Password</label>
    <div class="input-wrapper">
      <input type="password" id="password" name="password"
          class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
          placeholder="Minimal 8 karakter"
          required minlength="8">
      <button type="button" class="password-toggle" onclick="togglePassword('password', this)">️</button>
    </div>
    @error('password')
      <div class="invalid-feedback">️ {{ $message }}</div>
    @enderror
  </div>

  <div class="form-group">
    <label class="form-label" for="password_confirmation"> Konfirmasi Password</label>
    <div class="input-wrapper">
      <input type="password" id="password_confirmation" name="password_confirmation"
          class="form-control"
          placeholder="Ulangi password Anda"
          required>
      <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">️</button>
    </div>
    <div id="pwd-match-hint" style="font-size:11px;margin-top:5px;"></div>
  </div>

  {{-- Password strength indicator --}}
  <div style="margin-bottom:18px;">
    <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--text-400); margin-bottom:6px;">
      <span>Kekuatan Password</span>
      <span id="strength-label">—</span>
    </div>
    <div style="height:4px; background:var(--bg-500); border-radius:2px; overflow:hidden;">
      <div id="strength-bar" style="height:100%; width:0%; border-radius:2px; transition:all 0.4s;"></div>
    </div>
  </div>

  <button type="submit" class="btn-auth" id="submit-btn">
     Daftar & Kirim OTP
  </button>
</form>

<div class="auth-footer" style="margin-top:20px;">
  Sudah punya akun?
  <a href="{{ route('auth.login') }}" class="auth-link">Masuk →</a>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(id, btn) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
  btn.textContent = input.type === 'password' ? '️' : '';
}

// Password strength
document.getElementById('password').addEventListener('input', function() {
  const pwd = this.value;
  let score = 0;
  if (pwd.length >= 8) score++;
  if (pwd.length >= 12) score++;
  if (/[A-Z]/.test(pwd)) score++;
  if (/[0-9]/.test(pwd)) score++;
  if (/[^A-Za-z0-9]/.test(pwd)) score++;

  const bar = document.getElementById('strength-bar');
  const label = document.getElementById('strength-label');
  const levels = [
    { pct: '20%', color: '#EF4444', text: 'Sangat Lemah' },
    { pct: '40%', color: '#F97316', text: 'Lemah' },
    { pct: '60%', color: '#F59E0B', text: 'Sedang' },
    { pct: '80%', color: '#84CC16', text: 'Kuat' },
    { pct: '100%', color: '#22C55E', text: 'Sangat Kuat ' },
  ];
  const lvl = levels[Math.max(0, score - 1)] || levels[0];
  if (pwd.length > 0) {
    bar.style.width = lvl.pct;
    bar.style.background = lvl.color;
    label.textContent = lvl.text;
    label.style.color = lvl.color;
  } else {
    bar.style.width = '0%';
    label.textContent = '—';
    label.style.color = '';
  }
});

// Password match
document.getElementById('password_confirmation').addEventListener('input', function() {
  const pwd = document.getElementById('password').value;
  const hint = document.getElementById('pwd-match-hint');
  if (!this.value) { hint.textContent = ''; return; }
  if (this.value === pwd) {
    hint.innerHTML = '<span style="color:#22C55E;"> Password cocok</span>';
  } else {
    hint.innerHTML = '<span style="color:#EF4444;"> Password tidak cocok</span>';
  }
});

document.getElementById('register-form').addEventListener('submit', function(e) {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.textContent = '⏳ Mendaftarkan...';
});
</script>
@endsection
