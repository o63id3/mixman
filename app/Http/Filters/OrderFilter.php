<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class OrderFilter extends QueryFilter
{
    /**
     * Filter the order by the user id.
     */
    public function user($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('user_id', $ides);
    }

    /**
     * Filter the order by the manager id.
     */
    public function manager($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('manager_id', $ides);
    }

    /**
     * Filter the order by the network id.
     */
    public function network($values): Builder
    {
        if ($this->user->role !== 'ahmed') {
            return $this->builder;
        }

        $ides = explode(',', $values);

        return $this->builder->whereIn('network_id', $ides);
    }

    /**
     * Filter the order by the status.
     */
    public function status($values): Builder
    {
        $statues = explode(',', $values);

        return $this->builder->whereIn('status', $statues);
    }

    /**
     * Filter the order by the updated at.
     */
    public function updatedAt($values): Builder
    {
        $dates = explode(',', $values);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $values);
    }
}
