<?php


namespace App\Services;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class PaginationService
{
    /**
     * @param Request $request
     * @param EloquentBuilder|QueryBuilder $builder
     * @param int $perPage
     * @param array $defaults
     * @return LengthAwarePaginator
     */
    final public static function paginateEloquentResults(Request $request, $builder, int $perPage, array $defaults = []): LengthAwarePaginator
    {
        $sort = $request->query('sort');
        $sortDirection = in_array($sort, ['asc', 'desc'], true) ? $sort : $defaults['sort'] ?? null;
        $sortKey = $request->query('sortKey') ?? $defaults['sortKey'] ?? null;
        if (null !== $sortDirection && null !== $sortKey) {
            $builder = $builder->orderBy($sortKey, $sortDirection);
        }
        return $builder->paginate($perPage)->appends('sort', $sortDirection)->appends('sortKey', $sortKey);
    }

    final public static function withSortKeys(LengthAwarePaginator $paginator): callable {
        $firstResult = $paginator->items()[0] ?? null;
        $attributes = $firstResult instanceof Model ? array_keys($firstResult->getAttributes()) : [];
        $uri = $paginator->url($paginator->currentPage());
        $queryParams = [];
        parse_str(explode('?', $uri)[1] ?? '', $queryParams);
        $oldSortKey = $queryParams['sortKey'] ?? null;
        return static function(string $key, bool $toggle = true) use ($oldSortKey, $attributes, $paginator, $queryParams) {
            if ($toggle && isset($queryParams['sort'])) {
                $queryParams['sort'] = $queryParams['sort'] === 'desc' ? 'asc' : 'desc';
            }
            if (in_array($key, $attributes, true)) {
                $queryParams['sortKey'] = $key;
            }
            $queryStringKeys = array_keys($queryParams);
            $firstKey = array_shift($queryStringKeys);
            $queryString = "?{$firstKey}={$queryParams[$firstKey]}";
            foreach($queryStringKeys as $param) {
                $queryString .= "&{$param}={$queryParams[$param]}";
            }
            $url = $paginator->path() . $queryString;
            $onClick = "onClick=\"window.location.href = '{$url}';\"";
            $sings = $key === $queryParams['sortKey'] ? ('sort-' . $queryParams['sort'] ?? 'asc') : '';
            $sings .= ' sortable' . ($key === $oldSortKey ? ' sortable-active' : '');
            $full = "{$onClick} class=\"{$sings}\"";
            return (object)compact('url', 'onClick', 'sings', 'full');
        };
    }
}