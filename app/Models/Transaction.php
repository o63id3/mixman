<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Models\Scopes\SystemUserScope;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Transaction extends Model
{
    use Filterable;

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withoutGlobalScope(SystemUserScope::class);
    }

    /**
     * Get the manager.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the network.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Scope the transactions visibility.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAhmed()) {
            return $query;
        }

        return $query
            ->when($user->isSeller(), fn ($query) => $query->where(fn ($query) => $query->whereStatus(null)->orWhereNot('status', OrderStatusEnum::Returned)))
            ->where(fn ($query) => $query->where('user_id', $user->id)
                ->orWhere('manager_id', $user->id)
                ->orWhereIn('id', $user->networks()->select(['networks.id']))
            );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
        ];
    }
}
