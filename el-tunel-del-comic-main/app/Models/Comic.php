<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'publisher_id',
        'price',
        'stock',
        'isbn',
        'pages',
        'language',
        'publication_date',
        'image_path',
        'is_featured',
        'is_new_release',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'publication_date' => 'date',
        'is_featured' => 'boolean',
        'is_new_release' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }
}
