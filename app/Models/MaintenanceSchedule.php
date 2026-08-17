<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id', 'maintenance_type', 'title', 'description',
        'frequency_days', 'next_due_date', 'last_performed_at', 'assigned_to',
        'status', 'priority',
    ];

    protected function casts(): array
    {
        return [
            'next_due_date' => 'date',
            'last_performed_at' => 'datetime',
        ];
    }

    public function inventoryItem(): BelongsTo { return $this->belongsTo(InventoryItem::class, 'inventory_item_id'); }
    public function assignedTo(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
    public function logs(): HasMany { return $this->hasMany(MaintenanceLog::class, 'maintenance_schedule_id'); }
}
