<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'cost_price',
        'selling_price',
        'stock',
        'min_stock',
        'unit',
        'image',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'integer',
        'selling_price' => 'integer',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getFormattedSellingPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->selling_price, 0, ',', '.');
    }

    public function getFormattedCostPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->cost_price, 0, ',', '.');
    }
}
