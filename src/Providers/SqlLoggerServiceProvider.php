<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/08/02 - 23:51
 */
namespace Workable\SqlLogger\Providers;

use Workable\SqlLogger\Command\SqlLoggerCommand;
use Workable\SqlLogger\Config;
use Workable\SqlLogger\SqlLogger;
use Illuminate\Support\ServiceProvider;

class SqlLoggerServiceProvider extends ServiceProvider
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->config = $this->app->make(Config::class);
    }

    public function boot()
    {
        $this->commands(SqlLoggerCommand::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // make sure events will be fired in Lumen
        $this->app->make('events');

        // merge config
        $this->mergeConfigFrom($this->configFileLocation(), 'sql_logger');

        // register files to be published
        $this->publishes($this->getPublished());

        $this->setupListener();

        $this->config->setChanel();
    }

    protected function setupListener()
    {
        if (! $this->shouldLogAnything()) {
            return;
        }

        // create logger class
        $logger = $this->app->make(SqlLogger::class);

        $this->app['db']->listen($this->getListenClosure($logger));
    }

    /**
     * Get files to be published.
     *
     * @return array
     */
    protected function getPublished()
    {
        return [
            $this->configFileLocation() => (function_exists('config_path') ?
                config_path('sql_logger.php') :
                base_path('config/sql_logger.php')),
        ];
    }

    /**
     * Verify whether anything should be logged.
     *
     * @return bool
     */
    protected function shouldLogAnything()
    {
        return $this->config->logAllQueries() || $this->config->logSlowQueries();
    }

    /**
     * Get config file location.
     *
     * @return bool|string
     */
    protected function configFileLocation()
    {
        return realpath(__DIR__ . '/../../config/sql_logger.php');
    }

    /**
     * Get closure that will be used for listening executed database queries.
     *
     * @param SqlLogger $logger
     *
     * @return \Closure
     */
    protected function getListenClosure(SqlLogger $logger)
    {
        return function ($query, $bindings = null, $time = null) use ($logger) {
            $stack = debug_backtrace(false, 30);
            $logger->setStack($stack)->log($query, $bindings, $time);
        };
    }
}