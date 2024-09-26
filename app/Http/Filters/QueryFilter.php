<?php

declare(strict_types=1);

namespace App\Http\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /**
     * List of the filters.
     */
    public array $filters = [];

    /**
     * List of the sorts.
     */
    public string $sorts = '';

    /**
     * Query builder.
     */
    protected Builder $builder;

    /**
     * Construct the request field.
     */
    public function __construct(
        protected Request $request
    ) {}

    /**
     * Applies the filters.
     *
     * @var array<string, string>
     */
    final public function filter(array $filters): void
    {
        $this->filters = $filters;

        foreach ($filters as $key => $values) {
            if (Str::contains($key, '.')) {
                $this->applyNestedFilter($key, $values);

                continue;
            }

            $key = Str::camel($key);
            if (method_exists($this, $key)) {
                $this->$key($values);
            }
        }
    }

    /**
     * Applies the sorts.
     *
     * @var array<string>
     */
    final public function sort(string $values): void
    {
        $this->sorts = $values;

        $sortAttrs = explode(',', $values);

        foreach ($sortAttrs as $sortAttr) {
            $direction = 'asc';

            if (mb_strpos($sortAttr, '-') === 0) {
                $direction = 'desc';
                $sortAttr = mb_substr($sortAttr, 1);
            }

            $this->builder
                ->orderByRaw("$sortAttr IS NULL")
                ->orderBy($sortAttr, $direction);
        }
    }

    /**
     * Applies the directives such sort, and filter.
     *
     * @var Builder
     */
    final public function apply(Builder $builder, User $user): Builder
    {
        if (! $user->isAdmin()) {
            return $builder;
        }

        $this->builder = $builder;

        foreach ($this->request->all() as $key => $values) {
            if ($values && method_exists($this, $key)) {
                $this->$key($values);
            }
        }

        return $builder;
    }

    /**
     * Applies the nested filters such user.name.
     *
     * @var key<string>
     * @var values<string>
     */
    protected function applyNestedFilter(string $key, $values): void
    {
        [$relation, $field] = explode('.', $key);

        $this->builder->whereHas($relation, function (Builder $query) use ($field, $values) {
            $values = explode(',', $values);

            return $query->whereIn($field, $values);
        });
    }
}
