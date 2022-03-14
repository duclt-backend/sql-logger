<?php
/**
 * Created by PhpStorm.
 * User: Hungokata
 * Date: 2021/12/27 - 17:31
 */
namespace Workable\SqlLogger\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SqlLoggerCommand extends Command
{
    protected $signature = 'sql-logger:run';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table("users")
            ->where("id", "<", 100)
            ->limit(12)
            ->get();
    }
}