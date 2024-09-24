<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class OrderFilter extends QueryFilter
{
    public function seller($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('seller_id', $ides);
    }

    public function status($values): Builder
    {
        $statues = explode(',', $values);

        return $this->builder->whereIn('status', $statues);
    }

    public function updatedAt($values): Builder
    {
        $dates = explode(',', $values);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $values);
    }
}
