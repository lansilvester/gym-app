<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'member_code', 'nik', 'birth_date', 'gender',
        'address', 'emergency_contact_name', 'emergency_contact_phone', 'notes',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function medicalInfo(): HasOne { return $this->hasOne(MemberMedicalInfo::class); }
    public function bodyMeasurements(): HasMany { return $this->hasMany(MemberBodyMeasurement::class); }
    public function subscriptions(): HasMany { return $this->hasMany(MemberSubscription::class); }
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(MemberSubscription::class)->where('status', 'active')->latest('start_date');
    }
    public function checkIns(): HasMany { return $this->hasMany(CheckIn::class); }
    public function ptBookings(): HasMany { return $this->hasMany(PtBooking::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function attendanceSummaries(): HasMany { return $this->hasMany(AttendanceSummary::class); }
}
