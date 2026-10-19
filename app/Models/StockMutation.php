<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'supplier_name',
        'qty',
        'cost_price',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'qty' => 'integer',
        'cost_price' => 'integer',
        'total_cost' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedTotalCostAttribute(): string
    {
        return 'Rp ' . number_format($this->total_cost, 0, ',', '.');
    }
}
