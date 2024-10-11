<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WeeklyReport extends Model
{
    use HasFactory;

    /**
     * Get the network.
     */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class);
    }
}
