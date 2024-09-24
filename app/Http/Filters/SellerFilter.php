<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class SellerFilter extends QueryFilter
{
    /**
     * Filter the seller by the region id.
     */
    public function region($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('region_id', $ides);
    }
}
