<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number', 'invoice_id', 'payment_method_id', 'amount',
        'payment_date', 'reference_number', 'notes', 'received_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Payment $payment) {
            if (empty($payment->payment_number)) {
                $date = now()->format('Ymd');
                $last = static::where('payment_number', 'like', "PAY-{$date}-%")
                    ->orderByDesc('payment_number')
                    ->value('payment_number');
                $seq = $last ? (int) substr($last, -6) + 1 : 1;
                $payment->payment_number = 'PAY-' . $date . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(PaymentMethod::class); }
    public function receivedBy(): BelongsTo { return $this->belongsTo(User::class, 'received_by'); }
    public function refunds(): HasMany { return $this->hasMany(PaymentRefund::class); }
}
