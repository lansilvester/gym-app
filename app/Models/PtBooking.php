<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'trainer_id', 'subscription_id', 'booking_date',
        'start_time', 'end_time', 'status', 'session_type', 'notes',
        'cancelled_at', 'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'cancelled_at' => 'datetime',
        ];
    }

    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function trainer(): BelongsTo { return $this->belongsTo(Trainer::class); }
    public function subscription(): BelongsTo { return $this->belongsTo(MemberSubscription::class, 'subscription_id'); }
}
