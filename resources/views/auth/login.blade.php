@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="auth-card-title">Selamat Datang Kembali </div>
<div class="auth-card-subtitle">Masuk ke akun Ran's Cents Anda</div>

<form action="{{ route('auth.login.post') }}" method="POST" id="login-form">
  @csrf

  <div class="form-group">
    <label class="form-label" for="email"> Alamat Email</label>
    <input type="email" id="email" name="email"
        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email') }}"
        placeholder="email@contoh.com"
        autofocus required>
    @error('email')
      <div class="invalid-feedback">️ {{ $message }}</div>
    @enderror
  </div>

  <div class="form-group">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
      <label class="form-label" style="margin:0;" for="password"> Password</label>
      <a href="{{ route('auth.forgot') }}" class="auth-link" style="font-size:12px;">Lupa password?</a>
    </div>
    <div class="input-wrapper">
      <input type="password" id="password" name="password"
          class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
          placeholder="Masukkan password Anda"
          required>
      <button type="button" class="password-toggle" onclick="togglePassword('password', this)">️</button>
    </div>
    @error('password')
      <div class="invalid-feedback">️ {{ $message }}</div>
    @enderror
  </div>

  <div class="form-group">
    <div class="checkbox-group">
      <input type="checkbox" id="remember" name="remember">
      <label for="remember">Ingat saya selama 30 hari</label>
    </div>
  </div>

  <button type="submit" class="btn-auth" id="submit-btn">
    <span id="btn-text"> Masuk Sekarang</span>
  </button>
</form>

<div class="auth-footer" style="margin-top:20px;">
  Belum punya akun?
  <a href="{{ route('auth.register') }}" class="auth-link">Daftar gratis →</a>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(id, btn) {
  const input = document.getElementById(id);
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '';
  } else {
    input.type = 'password';
    btn.textContent = '️';
  }
}

document.getElementById('login-form').addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.innerHTML = '⏳ Memproses...';
});
</script>
@endsection
