<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class SellerFilter extends QueryFilter
{
    public function region($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('region_id', $ides);
    }

    public function createdAt($values): Builder
    {
        $dates = explode(',', $values);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $values);
    }
}
