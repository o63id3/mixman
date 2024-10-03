<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

final class Region extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the region users.
     */
    public function users(): HasMany
    {
        return $this->hasMany(Seller::class);
    }

    /**
     * Get the region transactions.
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Seller::class);
    }

    /**
     * Get the region payments.
     */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Seller::class);
    }
}
