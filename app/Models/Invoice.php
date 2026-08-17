<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'member_id', 'subscription_id', 'subtotal',
        'discount_amount', 'tax_amount', 'total_amount', 'notes',
        'due_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Invoice $invoice) {
            if (empty($invoice->invoice_number)) {
                $date = now()->format('Ymd');
                $last = static::where('invoice_number', 'like', "INV-{$date}-%")
                    ->orderByDesc('invoice_number')
                    ->value('invoice_number');
                $seq = $last ? (int) substr($last, -6) + 1 : 1;
                $invoice->invoice_number = 'INV-' . $date . '-' . str_pad($seq, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function member(): BelongsTo { return $this->belongsTo(Member::class); }
    public function subscription(): BelongsTo { return $this->belongsTo(MemberSubscription::class, 'subscription_id'); }
    public function items(): HasMany { return $this->hasMany(InvoiceItem::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function getAmountPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getAmountRemainingAttribute(): float
    {
        return (float) $this->total_amount - $this->amount_paid;
    }
}
