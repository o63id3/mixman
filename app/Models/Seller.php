<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Seller extends Model
{
    use Filterable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'region_id',
        'contact_info',
        'notes',
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
     * Seller is not an admin.
     */
    public function isAdmin(): bool
    {
        return false;
    }

    /**
     * Get the seller region.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the seller orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the seller region.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the seller transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate the balance.
     */
    public function scopeWithBalance(Builder $query): Builder
    {
        return $query->withSum('transactions as balance', 'amount');
    }

    /**
     * Load the balance.
     */
    public function loadBalance(): void
    {
        $this->loadSum('transactions as balance', 'amount');
    }

    /**
     * The "booting" method of the model.
     *
     * This method is used to register any event listeners for the model,
     * such as handling actions before a model is created or updated.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $setTotalPrices = function ($model) {
            $model->admin = false;
        };

        self::creating($setTotalPrices);
        self::updating($setTotalPrices);
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
            'active' => 'boolean',
        ];
    }
}
