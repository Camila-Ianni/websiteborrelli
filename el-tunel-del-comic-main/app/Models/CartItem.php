<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'session_id',
        'comic_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function comic(): BelongsTo
    {
        return $this->belongsTo(Comic::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->comic->price;
    }
}
