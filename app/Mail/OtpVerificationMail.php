<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otpCode,
        public string $userName,
        public string $type = 'verify', // 'verify' or 'reset'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === 'reset'
            ? '🔐 Reset Password — MoneyManager'
            : '✅ Verifikasi Email Anda — MoneyManager';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}
