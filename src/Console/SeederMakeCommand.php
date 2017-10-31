<?php
declare(strict_types=1);
namespace RabbitCMS\Modules\Devel\Console;

use InvalidArgumentException;
use RabbitCMS\Modules\Exceptions\ModuleNotFoundException;
use RabbitCMS\Modules\Facades\Modules;

/**
 * Class SeederMakeCommand
 *
 * @package RabbitCMS\Modules\Devel\Console
 */
class SeederMakeCommand extends \Illuminate\Database\Console\Seeds\SeederMakeCommand
{
    /**
     * @var Modules
     */
    protected $modules;

    protected $signature = 'make:seed {name : The name of the class.}
        {--module= : The module where the migration file should be created.}';



    /**
     * Get the destination class path.
     *
     * @param  string $name
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getPath($name)
    {
        if (null !== $moduleName = $this->input->getOption('module')) {
            try {
                $module = Modules::getByName($moduleName);
            } catch (ModuleNotFoundException $exception) {
                throw new InvalidArgumentException("Module {$moduleName} not found.", 0, $exception);
            }
            return $module->getPath("src/Database/Seeders/{$name}.php");
        }

        return parent::getPath($name);
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    protected function rootNamespace()
    {
        if (null !== $moduleName = $this->input->getOption('module')) {
            try {
                $module = Modules::getByName($moduleName);
            } catch (ModuleNotFoundException $exception) {
                throw new InvalidArgumentException("Module {$moduleName} not found.", 0, $exception);
            }
            return $module->getNamespace().'\\Database\\Seeders';
        }
        return parent::rootNamespace();
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        $ns = array_merge(explode('\\', $this->rootNamespace()), array_slice(explode('\\', $name), 0, -1));
        return implode('\\', $ns);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/seeder.stub';
    }
}
