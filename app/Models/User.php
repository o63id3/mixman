<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleEnum;
use App\Models\Scopes\SystemUserScope;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ScopedBy([SystemUserScope::class])]
final class User extends Authenticatable
{
    use Filterable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'telegram',
        'password',
        'role',
        'contact_info',
        'percentage',
        'network_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Check if the user is ahmed.
     */
    public function isAhmed(): bool
    {
        return $this->role === RoleEnum::Ahmed->value;
    }

    /**
     * Check if the user is manager.
     */
    public function isManager(): bool
    {
        return $this->isAhmed()
            || ($this->role === RoleEnum::Partner->value && $this->network_id !== null);
    }

    /**
     * Check if the user is partner.
     */
    public function isPartner(): bool
    {
        return $this->isAhmed()
            || $this->role === RoleEnum::Partner->value;
    }

    /**
     * Check if the user is seller.
     */
    public function isSeller(): bool
    {
        return $this->role === RoleEnum::Seller->value;
    }

    /**
     * Get the user network.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Get the networks that the user own shares in.
     */
    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'network_partners');
    }

    /**
     * Get the orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the payments that the user received.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'recipient_id');
    }

    /**
     * Get the user expenses.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the user transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope the users visibility.
     */
    public function scopeVisibleTo(Builder $query, self $user): Builder
    {
        if ($user->isAhmed()) {
            return $query;
        }

        return $query->where('network_id', $user->network_id);
    }

    /**
     * Scope the users to managers only.
     */
    public function scopeManager(Builder $query): Builder
    {
        return $query
            ->where('role', RoleEnum::Ahmed)
            ->orWhere(function (Builder $query) {
                $query->where('role', RoleEnum::Partner)->whereNotNull('network_id');
            });
    }

    /**
     * Calculate the sellers balance.
     */
    public function scopeWithBalance(Builder $query): Builder
    {
        return $query->withSum(['transactions as balance' => function ($query) {
            $query->whereHas('user', fn ($query) => $query->where('role', RoleEnum::Seller));
        }], 'amount');
    }

    /**
     * Scope the users to beneficiaries only.
     */
    public function scopeBeneficiary(Builder $query, ?self $user = null): Builder
    {
        if ($user?->isAhmed()) {
            return $query
                ->whereNot('role', RoleEnum::Ahmed)
                ->where(function (Builder $query) use ($user): Builder {
                    return $query
                        ->orWhere(function (Builder $query) {
                            $query->where('role', RoleEnum::Partner)->whereNotNull('network_id');
                        })
                        ->when($user->network_id, fn () => $query->orWhere('network_id', $user->network_id));
                });
        }

        return $query
            ->whereNot('role', RoleEnum::Ahmed)
            ->whereNotNull('network_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'admin' => 'boolean',
            'active' => 'boolean',
        ];
    }
}
