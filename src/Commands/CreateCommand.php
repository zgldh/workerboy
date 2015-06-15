<?php namespace zgldh\workerboy\Commands;

/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/4/11
 * Time: 21:29
 */


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'workerboy:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new workerman application';

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
        $name = $this->argument('name');
        $workerOnly = $this->option('worker-only');

        $workermanApps = app_path('WorkermanApps');
        if (!is_dir($workermanApps)) {
            mkdir($workermanApps);
        }
        $appPath = $workermanApps . DIRECTORY_SEPARATOR . $name;
        if (!is_dir($appPath)) {
            mkdir($appPath);
        }
        $appType = $workerOnly == 1 ? 'WorkerApplication' : 'GatewayBusinessWorkerApplication';
        $files = array();
        foreach (array('Event.php', 'start.php') as $item) {
            $fileName = $item;
            $files[$fileName] = array(
                'source'      =>
                    __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $appType . DIRECTORY_SEPARATOR . $fileName,
                'destination' => $appPath . DIRECTORY_SEPARATOR . $fileName
            );
        }
        if ($workerOnly == 1) {
            unset($files['Event.php']);
        }
        foreach ($files as $file) {
            $content = file_get_contents($file['source']);
            $content = str_replace('{WorkermanAppName}', $name, $content);
            file_put_contents($file['destination'], $content);
        }

        $this->info('Workerman application "' . $name . '" created at ' . $appPath);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'A workerman application needs a name.'],
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
            [
                'worker-only',
                null,
                InputOption::VALUE_NONE,
                'Create application in Worker mode.'
            ],
        ];
    }
}
