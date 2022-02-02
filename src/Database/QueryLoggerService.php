<?php
namespace Platform\Database;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Start logging your queries in php.
 *
 * Get a summary of queries run most often or most time-consuming.
 */
class QueryLoggerService
{
    /**
     * Start logging queries.
     */
    public function enable()
    {
        DB::enableQueryLog();
    }

    /**
     * Stop logging queries.
     */
    public function disable()
    {
        DB::disableQueryLog();
    }

    /**
     * Get queries sorted by the number of times executed.
     *
     * @param bool $resolveBindings if true, queries are grouped by exact values (not by e.g. WHERE user_id = ?)
     * @return Collection
     */
    public function getMostOftenExecuted(bool $resolveBindings = false): Collection
    {
        return $this->getAllQueries($resolveBindings)
            ->map(function ($queries) {
                return $queries->count();
            })
            ->sort(function ($query1Count, $query2Count) {
                return $query2Count - $query1Count;
            });
    }

    /**
     * Get queries sorted by most time-consuming. Returns total execution time in seconds for every query.
     *
     * @param bool $resolveBindings if true, queries are grouped by exact values (not by e.g. WHERE user_id = ?)
     * @return Collection
     */
    public function getMostTimeConsuming(bool $resolveBindings = false): Collection
    {
        $summary = $this->getAllQueries($resolveBindings)
            ->map(function ($queries) {
                return collect($queries)->sum('time') / 1000.0;
            })
            ->sort(function ($query1Count, $query2Count) {
                return $query2Count - $query1Count;
            });

        $summary = $summary->prepend($summary->sum(), '### TOTAL QUERY EXECUTION TIME IN SECONDS ###');

        return $summary;
    }

    /**
     * Get all queries executed after calling enable(), optionally grouped by the sql.
     *
     * @param bool $resolveBindings if true, queries are grouped by exact values (not by e.g. WHERE user_id = ?)
     * @return Collection
     */
    public function getAllQueries(bool $resolveBindings = false): Collection
    {
        $queries = collect($this->getRawQueries());

        if ($resolveBindings) {
            return $queries->groupBy(function ($queryArray) {
                $query = $queryArray['query'];
                $bindings = collect($queryArray['bindings']);
                while ($index = strpos($query, '?') !== false) {
                    $binding = $bindings->shift();
                    $query = str_replace_first('?', is_string($binding) ? '"'.$binding.'"' : $binding, $query);
                }
                return $query;
            });
        }
        else {
            return $queries->groupBy('query');
        }
    }

    /**
     * @return array
     */
    public function getRawQueries()
    {
        return DB::getQueryLog();
    }
}
