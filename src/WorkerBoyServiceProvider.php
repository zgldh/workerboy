<?php namespace zgldh\workerboy;

use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/4/11
 * Time: 20:51
 */
class WorkerBoyServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommandCreate();
        $this->registerCommandStart();
        $this->registerCommandStop();
        $this->registerCommandRestart();
        $this->registerCommandReload();
        $this->registerCommandStatus();

        $this->registerCredentialProcessor();
    }

    private function registerCommandCreate()
    {
        $this->app->singleton(
            'command.workerboy.create',
            function ($app) {
                return $app['zgldh\workerboy\Commands\CreateCommand'];
            }
        );

        $this->commands('command.workerboy.create');
    }

    private function registerCommandStart()
    {
        $this->app->singleton(
            'command.workerboy.start',
            function ($app) {
                return $app['zgldh\workerboy\Commands\StartCommand'];
            }
        );

        $this->commands('command.workerboy.start');
    }

    private function registerCommandStop()
    {
        $this->app->singleton(
            'command.workerboy.stop',
            function ($app) {
                return $app['zgldh\workerboy\Commands\StopCommand'];
            }
        );

        $this->commands('command.workerboy.stop');
    }

    private function registerCommandRestart()
    {
        $this->app->singleton(
            'command.workerboy.restart',
            function ($app) {
                return $app['zgldh\workerboy\Commands\RestartCommand'];
            }
        );

        $this->commands('command.workerboy.restart');
    }

    private function registerCommandReload()
    {
        $this->app->singleton(
            'command.workerboy.reload',
            function ($app) {
                return $app['zgldh\workerboy\Commands\ReloadCommand'];
            }
        );

        $this->commands('command.workerboy.reload');
    }

    private function registerCommandStatus()
    {
        $this->app->singleton(
            'command.workerboy.status',
            function ($app) {
                return $app['zgldh\workerboy\Commands\StatusCommand'];
            }
        );

        $this->commands('command.workerboy.status');
    }

    private function registerCredentialProcessor()
    {
        $this->app->singleton(
            'workerboy.credentialprocessor',
            function ($app) {
                $credentialProcessorClass = \Config::get('workerboy.credential_processor', 'zgldh\workerboy\CredentialProcessor');
                return $app[$credentialProcessorClass];
            }
        );
    }

    public function boot()
    {
        // Configuration publish
        $this->publishes(
            [
                __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'workerboy.php' => config_path(
                    'workerboy.php'
                ),
            ],
            'config'
        );
    }
}