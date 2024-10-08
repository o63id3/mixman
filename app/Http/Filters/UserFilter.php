<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

final class UserFilter extends QueryFilter
{
    /**
     * Filter the user by the network id.
     */
    public function network($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('network_id', $ides);
    }

    /**
     * Filter the user by the role.
     */
    public function role($values): Builder
    {
        $ides = explode(',', $values);

        return $this->builder->whereIn('role', $ides);
    }
}
