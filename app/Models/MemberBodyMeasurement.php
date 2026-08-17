<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberBodyMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'measured_at', 'weight_kg', 'body_fat_pct',
        'chest_cm', 'waist_cm', 'hip_cm', 'bicep_cm', 'thigh_cm', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'measured_at' => 'date',
            'weight_kg' => 'decimal:1',
            'body_fat_pct' => 'decimal:1',
            'chest_cm' => 'decimal:1',
            'waist_cm' => 'decimal:1',
            'hip_cm' => 'decimal:1',
            'bicep_cm' => 'decimal:1',
            'thigh_cm' => 'decimal:1',
        ];
    }

    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
}
