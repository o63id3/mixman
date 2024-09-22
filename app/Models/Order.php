<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatusEnum;
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
        return $this->status === OrderStatusEnum::Completed;
    }

    /**
     * Scope the users to sellers only.
     */
    public function scopeVisibleTo(Builder $query, User|Seller $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where('seller_id', $user->id);
    }

    /**
     * Check if the order state is complete
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::Completed);
    }

    /**
     * Check if the order state is complete
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::Pending);
    }

    /**
     * Check if the order state is complete
     */
    public function scopeReturned(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::Returned);
    }

    /**
     * Search by seller name.
     */
    public function scopeSearchBySellerName(Builder $query, $search): Builder
    {
        if ($search === '' || $search === null) {
            return $query;
        }

        return $query->whereHas('seller', function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%");
        });
    }

    /**
     * filter by seller name.
     */
    public function scopeFilterByStatus(Builder $query, $status): Builder
    {
        if ($status === null || $status === 'all') {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Get the seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatusEnum::class,
        ];
    }
}
