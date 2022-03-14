## Laravel SQL Logger

This module allows you to log SQL queries (and slow SQL queries) to log file in Laravel/Lumen framework. It's useful mainly
when developing your application to verify whether your queries are valid and to make sure your application doesn't run too many or too slow database queries.

### Support

Using this package is free of charge, however to keep it up-to-date and add new features small money support is appreciated.

### Installation

1. Run

   ```php
   composer require core-packages/sql-logger --dev
   ```

   in console to install this module (Notice `--dev` flag - it's recommended to use this package only for development).

2. If you use Laravel < 5.5 open `config/app.php` and in `providers` section add:

   ```php
   Workable\SqlLogger\Providers\ServiceProvider::class,
   ```

   Laravel 5.5 uses Package Auto-Discovery and it will automatically load this service provider so you don't need to add anything into above file.

   If you are using Lumen open `bootstrap/app.php` and add:

   ```php
   $app->register(Workable\SqlLogger\Providers\SqlLoggerServiceProvider::class);
   ```

3. If you use Laravel < 5.5 run:

   ```php
   php artisan vendor:publish --provider="Workable\SqlLogger\Providers\SqlLoggerServiceProvider"
   ```

   in your console to publish default configuration files.

   If you are using Laravel 5.5 run:

   ```php
   php artisan vendor:publish
   ```

   and choose the number matching `"Workable\SqlLogger\Providers\SqlLoggerServiceProvider"` provider.

   By default you should not edit published file because all the settings are loaded from `.env` file by default.

   For Lumen you should skip this step.

# Entry format - Json

```
$contentLogSql = [
    'origin' => $this->originLine(),
    'query_count'   => $query->number(),
    'time'   => $this->time($query->time()),
    "unit" => $this->getTimeUnit(),
    'sql'   => $this->queryLine($query),
    "source" => $query->findSource($stack)
];
return json_encode($contentLogSql);
```

## Testing

    ```
    	# Install composer
    	composer install

    	# Test all
    	composer test

    	# Test class
    	php vendor/bin/phpunit --filter ConfigTest --testdox tests

    	# Via Composer
    	composer test --filter ConfigTest

    	# Test a function in class
    	php vendor/bin/phpunit --filter it_returns_valid_sql_query_object_when_bindings_are_null tests/QueryTest.php
    ```

### Source

- https://github.com/mnabialek/laravel-sql-logger
- https://github.com/123job-Group/laravel-slow-query
- https://github.com/rokde/laravel-slow-query-logger
