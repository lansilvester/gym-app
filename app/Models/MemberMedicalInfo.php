<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberMedicalInfo extends Model
{
    use HasFactory;

    protected $table = 'member_medical_info';

    protected $fillable = [
        'member_id', 'blood_type', 'height_cm', 'weight_kg',
        'medical_conditions', 'allergies', 'doctor_clearance',
    ];

    protected function casts(): array
    {
        return [
            'height_cm' => 'decimal:1',
            'weight_kg' => 'decimal:1',
            'doctor_clearance' => 'boolean',
        ];
    }

    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
}
