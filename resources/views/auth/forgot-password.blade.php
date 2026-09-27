@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div style="text-align:center; margin-bottom:20px;">
  <div style="font-size:42px; margin-bottom:10px;"></div>
  <div class="auth-card-title">Lupa Password?</div>
  <div class="auth-card-subtitle">
    Masukkan email Anda dan kami akan mengirimkan kode OTP untuk mereset password
  </div>
</div>

<form action="{{ route('auth.forgot.send') }}" method="POST" id="forgot-form">
  @csrf

  <div class="form-group">
    <label class="form-label" for="email"> Alamat Email Terdaftar</label>
    <input type="email" id="email" name="email"
        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
        value="{{ old('email') }}"
        placeholder="email@contoh.com"
        autofocus required>
    @error('email')
      <div class="invalid-feedback">️ {{ $message }}</div>
    @enderror
  </div>

  <div style="background:rgba(59,130,246,0.08); border:1px solid rgba(59,130,246,0.2); border-radius:10px; padding:12px 16px; margin-bottom:18px; font-size:13px; color:#93C5FD; line-height:1.6;">
    ℹ️ Kode OTP akan dikirim ke email yang terdaftar dan berlaku selama 10 menit.
  </div>

  <button type="submit" class="btn-auth" id="submit-btn">
     Kirim Kode OTP Reset
  </button>
</form>

<div class="auth-footer" style="margin-top:18px;">
  <a href="{{ route('auth.login') }}" class="auth-link">← Kembali ke Login</a>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('forgot-form').addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.textContent = '⏳ Mengirim OTP...';
});
</script>
@endsection
