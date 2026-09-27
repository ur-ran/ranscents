<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $type === 'reset' ? 'Reset Password' : 'Verifikasi Email' }} — Ran's Cents</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background: #0D0D1A;
      color: #E8E0CC;
      padding: 40px 20px;
    }
    .email-container {
      max-width: 560px;
      margin: 0 auto;
      background: #16162A;
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid rgba(212, 175, 55, 0.2);
    }
    .email-header {
      background: linear-gradient(135deg, #1A1A2E, #16213E);
      padding: 40px 40px 30px;
      text-align: center;
      border-bottom: 1px solid rgba(212, 175, 55, 0.15);
    }
    .logo-icon {
      font-size: 42px;
      margin-bottom: 12px;
    }
    .brand-name {
      font-size: 22px;
      font-weight: 700;
      background: linear-gradient(135deg, #D4AF37, #B8860B);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: 1px;
    }
    .email-body {
      padding: 36px 40px;
    }
    .greeting {
      font-size: 18px;
      font-weight: 600;
      color: #F5F0E8;
      margin-bottom: 12px;
    }
    .message {
      font-size: 14px;
      line-height: 1.7;
      color: #9A8F78;
      margin-bottom: 30px;
    }
    .otp-container {
      background: linear-gradient(135deg, #1E1E32, #252540);
      border: 2px solid rgba(212, 175, 55, 0.3);
      border-radius: 16px;
      padding: 28px;
      text-align: center;
      margin: 24px 0;
    }
    .otp-label {
      font-size: 12px;
      font-weight: 600;
      color: #9A8F78;
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 14px;
    }
    .otp-code {
      font-size: 48px;
      font-weight: 800;
      letter-spacing: 12px;
      background: linear-gradient(135deg, #D4AF37, #FFD700);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-family: 'Courier New', monospace;
    }
    .otp-expires {
      font-size: 12px;
      color: #6B6255;
      margin-top: 12px;
    }
    .warning-box {
      background: rgba(239, 68, 68, 0.08);
      border: 1px solid rgba(239, 68, 68, 0.2);
      border-radius: 10px;
      padding: 14px 18px;
      font-size: 13px;
      color: #F87171;
      margin-top: 20px;
    }
    .divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.3), transparent);
      margin: 28px 0;
    }
    .footer {
      padding: 20px 40px 28px;
      text-align: center;
      font-size: 12px;
      color: #6B6255;
      border-top: 1px solid rgba(212, 175, 55, 0.1);
    }
    .footer a {
      color: #D4AF37;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="email-header">
      <div class="logo-icon"></div>
      <div class="brand-name">Ran's Cents</div>
      <div style="font-size:13px;color:#6B6255;margin-top:6px;">Kelola Keuangan Anda</div>
    </div>

    <div class="email-body">
      <div class="greeting">
        Halo, {{ $userName }}! {{ $type === 'reset' ? '' : '' }}
      </div>

      @if($type === 'reset')
        <div class="message">
          Kami menerima permintaan reset password untuk akun Ran's Cents Anda.
          Gunakan kode OTP di bawah ini untuk membuat password baru.
          <br><br>
          Jika Anda tidak meminta reset password, abaikan email ini.
        </div>
      @else
        <div class="message">
          Terima kasih telah mendaftar di Ran's Cents! 
          <br><br>
          Untuk mengaktifkan akun Anda, masukkan kode verifikasi di bawah ini.
          Kode berlaku selama <strong style="color:#D4AF37;">10 menit</strong>.
        </div>
      @endif

      <div class="otp-container">
        <div class="otp-label">Kode Verifikasi OTP</div>
        <div class="otp-code">{{ $otpCode }}</div>
        <div class="otp-expires">⏱️ Berlaku selama 10 menit</div>
      </div>

      <div class="warning-box">
        ️ Jangan bagikan kode ini kepada siapapun, termasuk tim Ran's Cents.
        Kami tidak pernah meminta kode OTP Anda.
      </div>

      <div class="divider"></div>

      <div style="font-size:13px;color:#6B6255;line-height:1.6;">
        Jika Anda mengalami masalah, silakan hubungi kami melalui email atau kunjungi aplikasi Ran's Cents.
      </div>
    </div>

    <div class="footer">
      <p>© {{ date('Y') }} <strong style="color:#D4AF37;">Ran's Cents</strong> · Kelola Keuangan Dengan Cerdas</p>
      <p style="margin-top:6px;">Email ini dikirim secara otomatis, mohon jangan membalas.</p>
    </div>
  </div>
</body>
</html>
