<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/08/02 - 23:42
 */

namespace Workable\SqlLogger;

use Workable\SqlLogger\Objects\SqlQuery;
use Mnabialek\LaravelVersion\Version;

class Query
{
    /**
     * @var Version
     */
    private $version;

    /**
     * Query constructor.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    /**
     * @param int $number
     * @param string|\Illuminate\Database\Events\QueryExecuted $query
     * @param array|null $bindings
     * @param float|null $time
     *
     * @return SqlQuery
     */
    public function get($number, $query, array $bindings = null, $time = null)
    {
        // for Laravel/Lumen 5.2+ $query is object and it holds all the data
        if ($this->version->min('5.2.0')) {
            $bindings = $query->bindings;
            $time = $query->time;
            $query = $query->sql;
        }

        return new SqlQuery($number, $query, $bindings, $time);
    }
}