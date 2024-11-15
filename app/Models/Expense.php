<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\SystemUserScope;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Expense extends Model
{
    use Filterable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'network_id',
        'user_id',
        'description',
        'amount',
    ];

    /**
     * Get user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withoutGlobalScope(SystemUserScope::class);
    }

    /**
     * Get network.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }

    /**
     * Scope the expenses depending on user role.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isAhmed()) {
            return $query;
        }

        return $query->where(fn ($query) => $query->where('user_id', $user->id)
            ->orWhere('network_id', $user->network_id)
        );
    }
}
