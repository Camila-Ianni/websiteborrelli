<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publisher extends Model
{
    protected $fillable = [
        'name',
        'country',
        'website',
    ];

    public function comics(): HasMany
    {
        return $this->hasMany(Comic::class);
    }
}
