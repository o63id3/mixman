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
        'seller_id',
        'amount',
        'notes',
    ];

    /**
     * Scope the payments depending on user role.
     */
    public function scopeVisibleTo(Builder $query, User|Seller $user): Builder
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where('seller_id', $user->id);
    }

    /**
     * Get seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Get registerer.
     */
    public function registerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
