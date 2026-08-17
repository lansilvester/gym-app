<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id', 'type', 'quantity', 'reference_number',
        'notes', 'performed_by',
    ];

    public function inventoryItem(): BelongsTo { return $this->belongsTo(InventoryItem::class, 'inventory_item_id'); }
    public function performer(): BelongsTo { return $this->belongsTo(User::class, 'performed_by'); }
}
