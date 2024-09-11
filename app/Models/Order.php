<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
