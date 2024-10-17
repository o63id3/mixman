<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Network extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'internet_price_per_week',
        'active',
        'manager_id',
    ];

    /**
     * Network partners.
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'network_partners')->withPivot('share');
    }

    /**
     * Get the network manager.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the network payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the network reports.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(WeeklyReport::class);
    }

    /**
     * Get the network expenses.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the network transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope the networks visibility.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAhmed()) {
            return $query;
        }

        return $query->whereIn('id', $user->networks()->get(['networks.id'])->pluck('id'));
    }

    /**
     * Load the network balance.
     */
    public function scopeWithBalance(Builder $query): Builder
    {
        return $query->withSum(['transactions as balance' => function ($query) {
            $query->whereHas('user', fn ($query) => $query->whereIn('role', [RoleEnum::Ahmed, RoleEnum::Partner]));
        }], 'amount');
    }

    /**
     * Get the network's available share.
     */
    protected function availableShare(): Attribute
    {
        return Attribute::make(
            get: fn () => 100 - (int) ((float) $this->partners()->sum('share') * 100)
        )->shouldCache();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
