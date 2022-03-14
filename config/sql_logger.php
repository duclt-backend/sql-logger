<?php

return [
    "channel"=> [
        'driver' => 'daily',
        'level' => 'warning',
        'path' => storage_path('sqls/sql.log'),
        'days' => 10,
    ],
    'general' => [
        /*s
         * Whether execution time in log file should be displayed in seconds
         * (by default it's in milliseconds)
         */
        'use_seconds' => env('SQL_LOGGER_USE_SECONDS', false),

        /*
         * Suffix for Artisan queries logs (if it's empty same files will be used for Artisan)
         */
        'console_log_suffix' => env('SQL_LOGGER_CONSOLE_SUFFIX', ''),

        /*
         * Extension for log files
         */
        'extension' => env('SQL_LOGGER_LOG_EXTENSION', '.log'),
    ],

    'formatting' => [
        /*
         * Whether new lines should be replaced by spaces (to keep query in single line)
         */
        'new_lines_to_spaces' => env('SQL_LOGGER_FORMAT_NEW_LINES_TO_SPACES', false)
    ],

    'slow_queries' => [
        /*
         * Whether slow SQL queries should be logged (you can log all queries and
         * also slow queries in separate file or you might to want log only slow
         * queries)
         */
        'enabled' => env('SQL_LOGGER_SLOW_QUERIES_ENABLED', true),

        /*
         * Time of query (in milliseconds) when this query is considered as slow
         */
        'min_exec_time' => env('SQL_LOGGER_SLOW_QUERIES_MIN_EXEC_TIME', 100)
    ],
];