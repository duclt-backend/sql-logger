<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/08/02 - 23:33
 */

namespace Workable\SqlLogger\Objects;
use Workable\SqlLogger\Objects\Concerns\ReplacesBindings;

class SqlQuery
{
    use ReplacesBindings;

    /**
     * @var int
     */
    private $number;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $bindings;

    /**
     * @var float
     */
    private $time;

    /**
     * SqlQuery constructor.
     *
     * @param int $number
     * @param string $sql
     * @param array|null $bindings
     * @param float $time
     */
    public function __construct($number = 0, $sql = '', array $bindings = null, $time = 0)
    {
        $this->number = $number;
        $this->sql = $sql;
        $this->bindings = $bindings ?: [];
        $this->time = $time;
    }

    /**
     * Get number of query.
     *
     * @return int
     */
    public function number()
    {
        return $this->number;
    }

    /**
     * Get raw SQL (without bindings).
     *
     * @return string
     */
    public function raw()
    {
        return $this->sql;
    }

    /**
     * Get bindings.
     *
     * @return array
     */
    public function bindings()
    {
        return $this->bindings;
    }

    /**
     * Get time.
     *
     * @return float
     */
    public function time()
    {
        return $this->time;
    }

    /**
     * Get full query with values from bindings inserted.
     *
     * @return string
     */
    public function get()
    {
        return $this->replaceBindings($this->sql, $this->bindings);
    }

    public function findSource($stack)
    {
        try
        {
            foreach ($stack as $trace)
            {
                if (isset($trace['class']) && isset($trace['file'])
                    && strpos($trace['file'], '/vendor/') === false) {
                    $file = str_replace(base_path(), '', $trace['file']);
                    $line = isset($trace['line']) ? $trace['line'] : '?';
                    return $file . ':' . $line;
                } elseif (isset($trace['function']) && $trace['function'] == 'Illuminate\Routing\{closure}'){
                    /** @var \Illuminate\Routing\Route $route */
                    $route = $trace['args'][1];
                    return 'Route '.$route->getUri();
                }
            }
        }catch (\Exception $e)
        {
            return null;
        }
    }

}