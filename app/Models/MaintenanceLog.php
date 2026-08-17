<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_schedule_id', 'performed_at', 'performed_by',
        'parts_replaced', 'cost', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'cost' => 'decimal:2',
        ];
    }

    public function maintenanceSchedule(): BelongsTo { return $this->belongsTo(MaintenanceSchedule::class, 'maintenance_schedule_id'); }
    public function performer(): BelongsTo { return $this->belongsTo(User::class, 'performed_by'); }
    public function performedBy(): BelongsTo { return $this->belongsTo(User::class, 'performed_by'); }
}
