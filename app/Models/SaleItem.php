<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the sale that owns the SaleItem
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product that was sold
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Boot method for model events
     */
    protected static function booted(): void
    {
        static::creating(function (SaleItem $saleItem) {
            // Auto-calculate subtotal if not set
            if (empty($saleItem->subtotal)) {
                $saleItem->subtotal = $saleItem->quantity * $saleItem->unit_price;
            }
        });

        static::updating(function (SaleItem $saleItem) {
            // Recalculate subtotal when quantity or unit_price changes
            if ($saleItem->isDirty(['quantity', 'unit_price'])) {
                $saleItem->subtotal = $saleItem->quantity * $saleItem->unit_price;
            }
        });
    }
}
