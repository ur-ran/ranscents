<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['email', 'code', 'type', 'is_used', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate a new 6-digit OTP for the given email and type.
     */
    public static function generate(string $email, string $type = 'verify'): self
    {
        // Invalidate previous OTPs
        static::where('email', $email)->where('type', $type)->delete();

        return static::create([
            'email' => $email,
            'code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $type,
            'is_used' => false,
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }
}
