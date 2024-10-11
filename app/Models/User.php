<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleEnum;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
        'notes',
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
     * Check if the user is admin.
     */
    public function isAhmed(): bool
    {
        return $this->role === RoleEnum::Ahmed->value;
    }

    /**
     * Check if the user is admin.
     */
    public function isManager(): bool
    {
        return $this->isAhmed()
            || ($this->role === RoleEnum::Partner->value && $this->network_id !== null);
    }

    /**
     * Check if the user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->isAhmed()
            || $this->role === RoleEnum::Partner->value;
    }

    /**
     * Get the seller region.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Scope the users to users only.
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
        return $query->where('role', RoleEnum::Ahmed)->orWhere(function (Builder $query) {
            $query->where('role', RoleEnum::Partner)->whereNotNull('network_id');
        });
    }

    /**
     * Scope the users to users only.
     */
    public function scopeWithBalance(Builder $query): Builder
    {
        return $query->withSum(['transactions as balance' => function ($query) {
            $query->whereHas('user', fn ($query) => $query->where('role', RoleEnum::Seller));
        }], 'amount');
    }

    /**
     * Scope the users to managers only.
     */
    public function scopeBenefiter(Builder $query): Builder
    {
        return $query->whereNotNull('network_id');
    }

    /**
     * Get the seller region.
     */
    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'network_partners');
    }

    /**
     * Get the seller region.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'recipient_id');
    }

    /**
     * Get the seller region.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Activate the user.
     */
    public function activate(): void
    {
        $this->active = true;
        $this->save();

    }

    /**
     * Deactivate the user.
     */
    public function deactivate(): void
    {
        $this->active = false;
        $this->save();

        DB::table('sessions')
            ->where('user_id', $this->id)
            ->delete();
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        self::addGlobalScope(function (Builder $builder) {
            $builder->where('username', '!=', 'system');
        });
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
