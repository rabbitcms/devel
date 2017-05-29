<?php
declare(strict_types=1);

namespace RabbitCMS\Modules\Devel;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use RabbitCMS\Modules\Devel\Console\MigrateMakeCommand;
use RabbitCMS\Modules\Managers\Modules;

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
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->extend(
            'command.migrate.make',
            function ($command, Application $app) {
                // Once we have the migration creator registered, we will create the command
                // and inject the creator. The creator is responsible for the actual file
                // creation of the migrations, and may be extended by these developers.
                $creator = $app['migration.creator'];

                $composer = $app['composer'];

                $modules = $app->make(Modules::class);

                return new MigrateMakeCommand($creator, $composer, $modules);
            }
        );
    }
}
