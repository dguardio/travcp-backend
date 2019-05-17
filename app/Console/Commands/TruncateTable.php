<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:truncate {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = $this->argument('table');

        if (!$this->confirm('CONFIRM TRUNCATE TABLE '. strtoupper($table). ' IN THE CURRENT DATABASE? [y|N]')) {
            exit('Truncate Tables command aborted');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table($table)->truncate();
        //turn referential integrity back on
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->comment(PHP_EOL."If no errors showed up, the table was truncated".PHP_EOL);
        return true;
    }
}
