<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class TransactionFilter extends QueryFilter
{
    /**
     * Filter the transaction by the seller id.
     */
    public function seller($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('seller_id', $ides);
    }

    /**
     * Filter the transaction by the created at.
     */
    public function createdAt($values): Builder
    {
        $dates = explode(',', $values);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $values);
    }
}
