<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Payment extends Model
{
    use Filterable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'network_id',
        'amount',
        'notes',
    ];

    /**
     * Scope the payments depending on user role.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAhmed()) {
            return $query;
        }

        return $query->where('user_id', $user->id)->orWhere('recipient_id', $user->id);
    }

    /**
     * Get seller.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get registerer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get registerer.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
