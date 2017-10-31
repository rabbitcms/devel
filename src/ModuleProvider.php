<?php
declare(strict_types=1);

namespace RabbitCMS\Modules\Devel;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use RabbitCMS\Modules\Devel\Console\MigrateMakeCommand;
use RabbitCMS\Modules\Devel\Console\SeederMakeCommand;

/**
 * Class ModuleProvider
 *
 * @package RabbitCMS\Modules\Devel
 */
class ModuleProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerMigrateMakeCommand();
        $this->registerSeederMakeCommand();
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->extend('command.migrate.make', function ($command, Application $app) {
            return new MigrateMakeCommand($app['migration.creator'], $app['composer']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeederMakeCommand()
    {
        $this->app->extend('command.seeder.make', function ($command, $app) {
            return new SeederMakeCommand($app['files'], $app['composer']);
        });
    }
}
