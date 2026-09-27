@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="auth-steps">
  <div class="auth-step done"></div>
  <div class="auth-step active"></div>
  <div class="auth-step"></div>
</div>

<div style="text-align:center; margin-bottom:20px;">
  <div style="font-size:48px; margin-bottom:12px;"></div>
  <div class="auth-card-title">Cek Inbox Email Anda!</div>
  <div class="auth-card-subtitle">
    Kami mengirim kode OTP 6 digit ke<br>
    <strong style="color:var(--gold-400);">{{ session('pending_verify_email', 'email Anda') }}</strong>
  </div>
</div>

<form action="{{ route('auth.otp.verify') }}" method="POST" id="otp-form">
  @csrf

  {{-- DEV MODE: Show OTP code on screen --}}
  @if(session('dev_otp'))
  <div style="background:linear-gradient(135deg,rgba(212,175,55,0.12),rgba(212,175,55,0.06));border:1px solid rgba(212,175,55,0.4);border-radius:12px;padding:16px 20px;margin-bottom:20px;text-align:center;">
    <div style="font-size:11px;font-weight:700;letter-spacing:2px;color:var(--text-300);text-transform:uppercase;margin-bottom:8px;">️ Mode Development — Kode OTP Anda</div>
    <div style="font-size:38px;font-weight:800;letter-spacing:10px;color:var(--gold-400);font-family:'Quicksand',serif;">{{ session('dev_otp') }}</div>
    <div style="font-size:11px;color:var(--text-400);margin-top:6px;">Banner ini tidak muncul di production. Email tersimpan di <code style="color:var(--gold-500);">storage/logs/laravel.log</code></div>
  </div>
  @endif

  <div class="form-group">
    <label class="form-label" style="text-align:center; display:block; margin-bottom:14px;">
      Masukkan 6 Digit Kode OTP
    </label>

    {{-- Visual 6-box OTP input --}}
    <div class="otp-inputs" id="otp-boxes">
      @for($i = 0; $i < 6; $i++)
        <input type="text" class="otp-input" maxlength="1"
            inputmode="numeric" pattern="[0-9]"
            id="otp-{{ $i }}" data-index="{{ $i }}"
            autocomplete="off">
      @endfor
    </div>

    {{-- Hidden aggregated input --}}
    <input type="hidden" name="otp" id="otp-value">

    @error('otp')
      <div class="invalid-feedback" style="justify-content:center;">️ {{ $message }}</div>
    @enderror
  </div>

  <div style="background:rgba(212,175,55,0.06); border:1px solid rgba(212,175,55,0.15); border-radius:10px; padding:12px 16px; margin-bottom:18px; font-size:13px; color:var(--text-300); line-height:1.6;">
    ⏱️ Kode berlaku selama <strong style="color:var(--gold-400);" id="countdown">10:00</strong>
    <br> Tidak menerima? Periksa folder spam.
  </div>

  <button type="submit" class="btn-auth" id="verify-btn">
     Verifikasi Sekarang
  </button>
</form>

<div style="text-align:center; margin-top:18px;">
  <form action="{{ route('auth.otp.resend') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="resend-btn" id="resend-btn" disabled>
       Kirim ulang OTP <span id="resend-timer">(60 detik)</span>
    </button>
  </form>
</div>

<div class="auth-footer" style="margin-top:14px;">
  <a href="{{ route('auth.register') }}" class="auth-link" style="font-size:13px;">← Kembali ke pendaftaran</a>
</div>
@endsection

@section('scripts')
<script>
// OTP box logic
const inputs = document.querySelectorAll('.otp-input');
const hiddenOtp = document.getElementById('otp-value');

function updateHidden() {
  hiddenOtp.value = Array.from(inputs).map(i => i.value).join('');
}

inputs.forEach((input, idx) => {
  input.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value) {
      this.classList.add('filled');
      if (idx < 5) inputs[idx + 1].focus();
    } else {
      this.classList.remove('filled');
    }
    updateHidden();

    // Auto-submit when all filled
    if (Array.from(inputs).every(i => i.value)) {
      setTimeout(() => document.getElementById('otp-form').requestSubmit(), 300);
    }
  });

  input.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && !this.value && idx > 0) {
      inputs[idx - 1].focus();
      inputs[idx - 1].value = '';
      inputs[idx - 1].classList.remove('filled');
      updateHidden();
    }
    if (e.key === 'ArrowLeft' && idx > 0) inputs[idx - 1].focus();
    if (e.key === 'ArrowRight' && idx < 5) inputs[idx + 1].focus();
  });

  // Handle paste
  input.addEventListener('paste', function(e) {
    e.preventDefault();
    const paste = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
    paste.split('').forEach((char, i) => {
      if (inputs[i]) {
        inputs[i].value = char;
        inputs[i].classList.add('filled');
      }
    });
    updateHidden();
    if (paste.length === 6) setTimeout(() => document.getElementById('otp-form').requestSubmit(), 300);
    else if (inputs[paste.length]) inputs[paste.length].focus();
  });
});

inputs[0].focus();

// OTP Countdown
let seconds = 600; // 10 minutes
const countdownEl = document.getElementById('countdown');
const timer = setInterval(() => {
  seconds--;
  const m = Math.floor(seconds / 60).toString().padStart(2,'0');
  const s = (seconds % 60).toString().padStart(2,'0');
  countdownEl.textContent = `${m}:${s}`;
  if (seconds <= 60) countdownEl.style.color = 'var(--danger)';
  if (seconds <= 0) { clearInterval(timer); countdownEl.textContent = 'Kadaluarsa!'; }
}, 1000);

// Resend cooldown (60s)
let resendSecs = 60;
const resendBtn = document.getElementById('resend-btn');
const resendTimer = document.getElementById('resend-timer');
const resendInterval = setInterval(() => {
  resendSecs--;
  resendTimer.textContent = `(${resendSecs} detik)`;
  if (resendSecs <= 0) {
    clearInterval(resendInterval);
    resendBtn.disabled = false;
    resendTimer.textContent = '';
  }
}, 1000);

document.getElementById('otp-form').addEventListener('submit', function() {
  document.getElementById('verify-btn').disabled = true;
  document.getElementById('verify-btn').textContent = '⏳ Memverifikasi...';
});
</script>
@endsection
