@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="auth-steps">
  <div class="auth-step done"></div>
  <div class="auth-step active"></div>
  <div class="auth-step"></div>
</div>

<div style="text-align:center; margin-bottom:20px;">
  <div style="font-size:42px; margin-bottom:10px;"></div>
  <div class="auth-card-title">Reset Password</div>
  <div class="auth-card-subtitle">
    Masukkan kode OTP yang dikirim ke<br>
    <strong style="color:var(--gold-400);">{{ session('pending_reset_email', 'email Anda') }}</strong>
  </div>
</div>

<form action="{{ route('auth.otp.reset-verify') }}" method="POST" id="reset-form">
  @csrf

  {{-- DEV MODE --}}
  @if(session('dev_otp'))
  <div style="background:linear-gradient(135deg,rgba(212,175,55,0.12),rgba(212,175,55,0.06));border:1px solid rgba(212,175,55,0.4);border-radius:12px;padding:16px 20px;margin-bottom:20px;text-align:center;">
    <div style="font-size:11px;font-weight:700;letter-spacing:2px;color:var(--text-300);text-transform:uppercase;margin-bottom:8px;">️ Mode Development — Kode OTP Reset</div>
    <div style="font-size:38px;font-weight:800;letter-spacing:10px;color:var(--gold-400);font-family:'Quicksand',serif;">{{ session('dev_otp') }}</div>
    <div style="font-size:11px;color:var(--text-400);margin-top:6px;">Email tersimpan di <code style="color:var(--gold-500);">storage/logs/laravel.log</code></div>
  </div>
  @endif

  <div class="form-group">
    <label class="form-label" style="text-align:center; display:block; margin-bottom:14px;">
      Kode OTP Reset Password
    </label>

    <div class="otp-inputs" id="otp-boxes">
      @for($i = 0; $i < 6; $i++)
        <input type="text" class="otp-input" maxlength="1"
            inputmode="numeric" pattern="[0-9]"
            id="otp-{{ $i }}" data-index="{{ $i }}"
            autocomplete="off">
      @endfor
    </div>
    <input type="hidden" name="otp" id="otp-value">

    @error('otp')
      <div class="invalid-feedback" style="justify-content:center;">️ {{ $message }}</div>
    @enderror
  </div>

  <div style="height:1px; background:linear-gradient(90deg, transparent, rgba(212,175,55,0.3), transparent); margin: 20px 0;"></div>

  <div class="form-group">
    <label class="form-label" for="password"> Password Baru</label>
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
    <div style="margin-top:6px;">
      <div style="height:4px; background:var(--bg-500); border-radius:2px; overflow:hidden;">
        <div id="strength-bar" style="height:100%; width:0%; transition:all 0.4s;"></div>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label class="form-label" for="password_confirmation"> Konfirmasi Password Baru</label>
    <div class="input-wrapper">
      <input type="password" id="password_confirmation" name="password_confirmation"
          class="form-control"
          placeholder="Ulangi password baru"
          required>
      <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">️</button>
    </div>
    <div id="pwd-match-hint" style="font-size:11px; margin-top:5px;"></div>
  </div>

  <div style="background:rgba(212,175,55,0.06); border:1px solid rgba(212,175,55,0.15); border-radius:10px; padding:11px 14px; margin-bottom:16px; font-size:12px; color:var(--text-300);">
    ⏱️ Kode berlaku: <strong style="color:var(--gold-400);" id="countdown">10:00</strong>
  </div>

  <button type="submit" class="btn-auth" id="submit-btn">
     Reset Password Sekarang
  </button>
</form>

<div class="auth-footer" style="margin-top:14px;">
  <a href="{{ route('auth.forgot') }}" class="auth-link" style="font-size:13px;">← Kirim ulang OTP</a>
</div>
@endsection

@section('scripts')
<script>
// OTP boxes
const inputs = document.querySelectorAll('.otp-input');
const hiddenOtp = document.getElementById('otp-value');

function updateHidden() {
  hiddenOtp.value = Array.from(inputs).map(i => i.value).join('');
}

inputs.forEach((input, idx) => {
  input.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value) {
      this.classList.add('filled');
      if (idx < 5) inputs[idx + 1].focus();
    } else {
      this.classList.remove('filled');
    }
    updateHidden();
  });

  input.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && !this.value && idx > 0) {
      inputs[idx - 1].focus();
      inputs[idx - 1].value = '';
      inputs[idx - 1].classList.remove('filled');
      updateHidden();
    }
  });

  input.addEventListener('paste', function(e) {
    e.preventDefault();
    const paste = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
    paste.split('').forEach((char, i) => {
      if (inputs[i]) { inputs[i].value = char; inputs[i].classList.add('filled'); }
    });
    updateHidden();
    if (inputs[paste.length]) inputs[paste.length].focus();
  });
});
inputs[0].focus();

// Toggle password
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
  const colors = ['#EF4444','#F97316','#F59E0B','#84CC16','#22C55E'];
  const widths = ['20%','40%','60%','80%','100%'];
  if (pwd.length > 0) {
    bar.style.width = widths[Math.max(0, score - 1)];
    bar.style.background = colors[Math.max(0, score - 1)];
    bar.style.borderRadius = '2px';
  }
});

// Password match
document.getElementById('password_confirmation').addEventListener('input', function() {
  const hint = document.getElementById('pwd-match-hint');
  if (this.value === document.getElementById('password').value) {
    hint.innerHTML = '<span style="color:#22C55E;"> Password cocok</span>';
  } else {
    hint.innerHTML = '<span style="color:#EF4444;"> Password tidak cocok</span>';
  }
});

// Countdown
let seconds = 600;
const countdownEl = document.getElementById('countdown');
const timer = setInterval(() => {
  seconds--;
  const m = Math.floor(seconds / 60).toString().padStart(2,'0');
  const s = (seconds % 60).toString().padStart(2,'0');
  countdownEl.textContent = `${m}:${s}`;
  if (seconds <= 60) countdownEl.style.color = 'var(--danger)';
  if (seconds <= 0) { clearInterval(timer); countdownEl.textContent = 'Kadaluarsa!'; }
}, 1000);

document.getElementById('reset-form').addEventListener('submit', function() {
  const btn = document.getElementById('submit-btn');
  btn.disabled = true;
  btn.textContent = '⏳ Mereset...';
});
</script>
@endsection
