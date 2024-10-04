<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Order extends Model
{
    use Filterable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'orderer_id',
        'status',
        'notes',
    ];

    /**
     * Check if the order state is complete
     */
    public function completed(): bool
    {
        return $this->status === OrderStatusEnum::Completed || $this->status === OrderStatusEnum::Returned;
    }

    /**
     * Check if the order state is complete
     */
    public function pending(): bool
    {
        return $this->status === OrderStatusEnum::Pending;
    }

    /**
     * Scope the users to users only.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAhmed()) {
            return $query;
        }

        return $query->where('orderer_id', $user->id)->orWhere('manager_id', $user->id);
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
     * Get the seller.
     */
    public function orderer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orderer_id');
    }

    /**
     * Get the seller.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the seller.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Get the order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
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

        $setManagerAndNetwork = function ($model) {
            $user = User::with('network:id,manager_id')->find($model->orderer->id);

            $model->network_id = $user->network->id;
            $model->manager_id = $user->isManager() ? User::where('role', 'ahmed')->first(['id'])->id : $user->network->manager_id;

            $model->completed_at = $model->isDirty('status') && $model->status === OrderStatusEnum::Pending ? null : now();
        };

        self::creating($setManagerAndNetwork);
        self::updating($setManagerAndNetwork);
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
            'completed_at' => 'datetime',
        ];
    }
}
