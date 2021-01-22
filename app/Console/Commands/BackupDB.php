<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Carbon\Carbon;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database';

    protected $process, $process2;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->process = new Process(sprintf( './opt/lampp/bin/mysqldump -u%s -p%s %s > %s',
            'root',
            'Clb__Lyn103185',
            'akuntansi',
            storage_path("backupdb/backupAkuntansi". Carbon::now()->format('Y-m-d') .'.sql')));

        $this->process2 = new Process(sprintf( './opt/lampp/bin/mysqldump -u%s -p%s %s > %s',
            'root',
            'Clb__Lyn103185',
            'd-ger',
            storage_path("backupdb/backupDGer". Carbon::now()->format('Y-m-d') .'.sql')));

        /*$this->process2 = new Process(sprintf( 'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            'd-ger',
            storage_path("backupdb/backupdger". Carbon::now()->format('Y-m-d') . '.sql')));*/
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->process->mustRun();
            $this->process2->mustRun();

            $this->info('The backup has been proceed successfully.');
        }
        catch(ProcessFailedException $exception) {
            $this->info('The backup process has been failed');
        }
    }
}
