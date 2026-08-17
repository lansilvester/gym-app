<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'package_id', 'start_date', 'end_date', 'status',
        'auto_renew', 'remaining_PT_sessions', 'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'auto_renew' => 'boolean',
            'cancelled_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function package(): BelongsTo { return $this->belongsTo(MembershipPackage::class, 'package_id'); }
    public function ptBookings(): HasMany { return $this->hasMany(PtBooking::class, 'subscription_id'); }
}
