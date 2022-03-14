<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/08/02 - 23:49
 */

namespace Workable\SqlLogger;

use Illuminate\Support\Facades\Log;
use Workable\SqlLogger\Objects\SqlQuery;

class Writer
{
    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @var Config
     */
    private $config;

    /**
     * Writer constructor.
     *
     * @param Formatter $formatter
     * @param Config $config
     */
    public function __construct(Formatter $formatter, Config $config)
    {
        $this->formatter = $formatter;
        $this->config = $config;
    }

    /**
     * Save queries to log.
     *
     * @param SqlQuery $query
     */
    public function save(SqlQuery $query, $stack)
    {
        $lineContent = $this->formatter->getLineContent($query, $stack);
        if ($this->shouldLogSlowQuery($query))
        {
            $this->saveLine($lineContent);
        }
    }

    /**
     * Verify whether slow query should be logged.
     *
     * @param SqlQuery $query
     *
     * @return bool
     */
    protected function shouldLogSlowQuery(SqlQuery $query)
    {
        return ($this->config->logSlowQueries()
            && $query->time() >= $this->config->slowLogTime());
    }

    /**
     * Save data to log file.
     *
     * @param string $line
     * @param string $fileName
     * @param bool $override
     */
    protected function saveLine($lineContent)
    {
        Log::channel("sql-logger")->warning($lineContent);
    }
}