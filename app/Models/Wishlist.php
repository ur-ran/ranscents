<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'estimated_price', 'priority', 'category',
        'notes', 'url', 'store', 'is_purchased', 'target_date', 'saved_amount'
    ];

    protected $casts = [
        'estimated_price' => 'decimal:2',
        'saved_amount' => 'decimal:2',
        'is_purchased' => 'boolean',
        'target_date' => 'date',
    ];

    public function getSavingProgressAttribute()
    {
        if (!$this->estimated_price || $this->estimated_price == 0) return 0;
        return min(100, round(($this->saved_amount / $this->estimated_price) * 100));
    }

    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'urgent'    => 'Mendesak',
            'important' => 'Penting',
            'need'      => 'Butuh',
            'want'      => 'Ingin',
            'low'       => 'Tidak Penting',
            default     => 'Lainnya',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent'    => '#EF4444',
            'important' => '#F59E0B',
            'need'      => '#3B82F6',
            'want'      => '#8B5CF6',
            'low'       => '#6B7280',
            default     => '#D4AF37',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
