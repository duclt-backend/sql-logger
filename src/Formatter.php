<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/08/02 - 23:40
 */

namespace Workable\SqlLogger;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Workable\SqlLogger\Objects\Concerns\ReplacesBindings;
use Workable\SqlLogger\Objects\SqlQuery;

class Formatter
{
    use ReplacesBindings;

    /**
     * @var Container
     */
    private $app;

    /**
     * @var Config
     */
    private $config;

    private $request;

    /**
     * Formatter constructor.
     *
     * @param Container $app
     * @param Config $config
     */
    public function __construct(Container $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
        $this->request = $this->app['request'];
    }

    /**
     * Get formatted line.
     *
     * @param SqlQuery $query
     *
     * @return string
     */
    public function getLineContent(SqlQuery $query, $stack)
    {
        $contentLogSql = [
            'origin' => $this->originLine(),
            'query_count'   => $query->number(),
            'time'   => $this->time($query->time()),
            "unit" => $this->getTimeUnit(),
            'sql'   => $this->queryLine($query),
            "source" => $query->findSource($stack)
        ];
        return json_encode($contentLogSql);
    }

    protected function getTimeUnit()
    {
        return $this->config->useSeconds() ? 's' : 'ms';
    }

    /**
     * Format time.
     *
     * @param float $time
     *
     * @return string
     */
    protected function time($time)
    {
        return $this->config->useSeconds() ? ($time / 1000.0) : $time ;
    }

    /**
     * Get origin line.
     *
     * @return string
     */
    protected function originLine()
    {
        return 'Origin ' . ($this->app->runningInConsole()
                ? '(console): ' . $this->getArtisanLine()
                : '(request): ' . $this->getRequestLine());
    }

    /**
     * Get query line.
     *
     * @param SqlQuery $query
     *
     * @return string
     */
    protected function queryLine(SqlQuery $query)
    {
        return $this->format($query->get()) . ';';
    }

    /**
     * Get Artisan line.
     *
     * @return string
     */
    protected function getArtisanLine()
    {
        $command = $this->app['request']->server('argv', []);

        if (is_array($command)) {
            $command = implode(' ', $command);
        }

        return $command;
    }

    /**
     * Get request line.
     *
     * @return string
     */
    protected function getRequestLine()
    {
        return $this->request->method() . ' ' . $this->request->fullUrl();
    }

    /**
     * Format given query.
     *
     * @param string $query
     *
     * @return string
     */
    protected function format($query)
    {
        return $this->removeNewLines($query);
    }

    /**
     * Remove new lines from SQL to keep it in single line if possible.
     *
     * @param string $sql
     *
     * @return string
     */
    protected function removeNewLines($sql)
    {
        if (! $this->config->newLinesToSpaces()) {
            return $sql;
        }

        return preg_replace($this->wrapRegex($this->notInsideQuotes('\v', false)), ' ', $sql);
    }
}