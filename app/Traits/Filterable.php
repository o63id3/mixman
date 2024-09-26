<?php

declare(strict_types=1);

namespace App\Traits;

use App\Http\Filters\QueryFilter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply the filters.
     */
    public function scopeFilter(Builder $builder, QueryFilter $filters, User $user): Builder
    {
        return $filters->apply($builder, $user);
    }
}
