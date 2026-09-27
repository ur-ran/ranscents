<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'person_name', 'type', 'amount', 'paid_amount',
        'date', 'due_date', 'description', 'status', 'contact'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->amount == 0) return 0;
        return min(100, round(($this->paid_amount / $this->amount) * 100));
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->due_date || $this->status === 'paid') return false;
        return $this->due_date->isPast();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
