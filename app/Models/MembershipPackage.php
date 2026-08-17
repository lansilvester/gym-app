<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MembershipPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'duration_days', 'price',
        'max_checkin_per_week', 'includes_personal_training', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'includes_personal_training' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (MembershipPackage $package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });

        static::updating(function (MembershipPackage $package) {
            if ($package->isDirty('name') && !$package->isDirty('slug')) {
                $package->slug = Str::slug($package->name);
            }
        });
    }

    public function subscriptions(): HasMany { return $this->hasMany(MemberSubscription::class, 'package_id'); }
}
