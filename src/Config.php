<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/08/02 - 23:37
 */

namespace Workable\SqlLogger;

use Illuminate\Contracts\Config\Repository as ConfigRepository;

class Config
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;


    /**
     * Config constructor.
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function setChanel()
    {
        $logConfig = $this->configRepository->get('sql_logger.channel');

        if (!$this->configRepository->get('logging.channels.sql-logger')) {
            $this->configRepository->set('logging.channels.sql-logger', $logConfig);
        }
    }

    /**
     * Get directory where log files should be saved.
     *
     * @return string
     */
    public function logDirectory()
    {
        return $this->configRepository->get('sql_logger.general.directory');
    }

    /**
     * Whether query execution time should be converted to seconds.
     *
     * @return bool
     */
    public function useSeconds()
    {
        return (bool) $this->configRepository->get('sql_logger.general.use_seconds');
    }

    /**
     * Get suffix for console logs.
     *
     * @return string
     */
    public function consoleSuffix()
    {
        return (string) $this->configRepository->get('sql_logger.general.console_log_suffix');
    }

    /**
     * Get file extension for logs.
     *
     * @return string
     */
    public function fileExtension()
    {
        return $this->configRepository->get('sql_logger.general.extension');
    }

    /**
     * Whether all queries should be logged.
     *
     * @return bool
     */
    public function logAllQueries()
    {
        return (bool) $this->configRepository->get('sql_logger.all_queries.enabled');
    }

    /**
     * Whether SQL log should be overridden for each request.
     *
     * @return bool
     */
    public function overrideFile()
    {
        return (bool) $this->configRepository->get('sql_logger.all_queries.override_log');
    }

    /**
     * Get pattern for all queries.
     *
     * @return string
     */
    public function allQueriesPattern()
    {
        return $this->configRepository->get('sql_logger.all_queries.pattern');
    }

    /**
     * Get file name (without extension) for all queries.
     *
     * @return string
     */
    public function allQueriesFileName()
    {
        return $this->configRepository->get('sql_logger.all_queries.file_name');
    }

    /**
     * Whether slow queries should be logged.
     *
     * @return bool
     */
    public function logSlowQueries()
    {
        return (bool) $this->configRepository->get('sql_logger.slow_queries.enabled');
    }

    /**
     * Minimum execution time (in milliseconds) to consider query as slow.
     *
     * @return float
     */
    public function slowLogTime()
    {
        return $this->configRepository->get('sql_logger.slow_queries.min_exec_time');
    }

    /**
     * Get pattern for slow queries.
     *
     * @return string
     */
    public function slowQueriesPattern()
    {
        return $this->configRepository->get('sql_logger.slow_queries.pattern');
    }

    /**
     * Get file name (without extension) for slow queries.
     *
     * @return string
     */
    public function slowQueriesFileName()
    {
        return $this->configRepository->get('sql_logger.slow_queries.file_name');
    }

    /**
     * Whether new lines should be converted to spaces.
     *
     * @return string
     */
    public function newLinesToSpaces()
    {
        return $this->configRepository->get('sql_logger.formatting.new_lines_to_spaces');
    }

    /**
     * Get query format that should be used to save query.
     *
     * @return string
     */
    public function entryFormat()
    {
        return $this->configRepository->get('sql_logger.formatting.entry_format');
    }
}