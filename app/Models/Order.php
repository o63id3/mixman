<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seller_id',
        'action_by',
        'status',
        'notes',
    ];

    /**
     * Check if the order state is complete
     */
    public function completed(): bool
    {
        return $this->status === 'C';
    }

    /**
     * Scope the users to sellers only.
     */
    public function scopeSeller(Builder $query): Builder
    {
        if (auth()->user()->isAdmin()) {
            return $query;
        }

        return $query->where('seller_id', auth()->id());
    }

    /**
     * Get the seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the seller.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    /**
     * Get the order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
