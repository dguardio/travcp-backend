<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tables:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables';

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
        if (!$this->confirm('CONFIRM TRUNCATE ALL TABLES IN THE CURRENT DATABASE? [y|N]')) {
            exit('Truncate Tables command aborted');
        }

        $colname = 'Tables_in_' . env('DB_DATABASE');

        $tables = DB::select('SHOW TABLES');

        foreach($tables as $table) {

            $droplist[] = $table->$colname;

        }
//        $droplist = implode(',', $droplist);

        DB::beginTransaction();
        //turn off referential integrity
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($droplist as $table){
            DB::statement("TRUNCATE TABLE $table");
            DB::commit();

        }
        //turn referential integrity back on
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $this->comment(PHP_EOL."If no errors showed up, all tables were truncated".PHP_EOL);
    }
}
