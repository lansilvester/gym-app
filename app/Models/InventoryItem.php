<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'sku', 'name', 'description', 'type', 'quantity',
        'unit', 'min_stock', 'max_stock', 'purchase_price', 'current_value',
        'location', 'status',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'current_value' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo { return $this->belongsTo(InventoryCategory::class, 'category_id'); }
    public function transactions(): HasMany { return $this->hasMany(InventoryTransaction::class, 'inventory_item_id'); }
    public function maintenanceSchedules(): HasMany { return $this->hasMany(MaintenanceSchedule::class, 'inventory_item_id'); }
}
