<?php

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    public array $filters = [];

    protected Builder $builder;

    public function __construct(
        protected Request $request
    ) {}

    final public function filter(array $filters): void
    {
        $this->filters = $filters;

        foreach ($filters as $key => $values) {
            if (method_exists($this, $key)) {
                $this->$key($values);
            }
        }
    }

    final public function sort(string $values): void
    {
        $sortAttrs = explode(',', $values);

        foreach ($sortAttrs as $sortAttr) {
            $direction = 'asc';

            if (mb_strpos($sortAttr, '-') === 0) {
                $direction = 'desc';
                $sortAttr = mb_substr($sortAttr, 1);
            }

            $this->builder->orderBy($sortAttr, $direction);
        }
    }

    final public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $values) {
            if (method_exists($this, $key)) {
                $this->$key($values);
            }
        }

        return $builder;
    }
}
