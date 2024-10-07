<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class TransactionFilter extends QueryFilter
{
    /**
     * Filter the transaction by the user id.
     */
    public function user($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('user_id', $ides);
    }

    /**
     * Filter the transaction by the manager id.
     */
    public function manager($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('manager_id', $ides);
    }

    /**
     * Filter the payment by the network id.
     */
    public function network($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('network_id', $ides);
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
