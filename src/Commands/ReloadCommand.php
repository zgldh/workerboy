<?php namespace zgldh\workerboy\Commands;

/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/4/11
 * Time: 21:29
 */


use Illuminate\Console\Command;
use Workerman\Worker;

class ReloadCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'workerboy:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload workerman';

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
    public function fire()
    {
        //
        global $argv;
        $argv[0] = 'artisan';
        $argv[1] = 'reload';

        Worker::runAll();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }

}
