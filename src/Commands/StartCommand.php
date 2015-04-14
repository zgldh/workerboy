<?php namespace zgldh\workerboy\Commands;

/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/4/11
 * Time: 21:29
 */


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Workerman\Worker;

class StartCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'workerboy:start';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Start workerman';

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
		$deamon_mode = $this->option('d');

		// 检查扩展
		if (!extension_loaded('pcntl'))
		{
			$this->error("Please install pcntl extension. See http://doc3.workerman.net/install/install.html\n");

			return;
		}

		if (!extension_loaded('posix'))
		{
			$this->error("Please install posix extension. See http://doc3.workerman.net/install/install.html\n");

			return;
		}

		// 标记是全局启动
		define('GLOBAL_START', 1);

		$applications = Config::get('workerboy.applications');
		// 加载所有Applications/*/start.php，以便启动所有服务
		foreach ($applications as $application)
		{
			$start_path = base_path($application);
			require_once $start_path;
		}
		// 运行所有服务

		global $argv;
		$argv[0] = 'artisan';
		$argv[1] = 'start';
		$argv[2] = $deamon_mode? '-d' :null;

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
			//			['example', InputArgument::REQUIRED, 'An example argument.'],
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
			['d', null, InputOption::VALUE_OPTIONAL, 'Deamon mode.'],
		];
	}

}
