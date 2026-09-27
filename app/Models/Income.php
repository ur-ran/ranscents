<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'amount', 'source', 'category_id', 'date',
        'notes', 'is_recurring', 'recurrence'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
