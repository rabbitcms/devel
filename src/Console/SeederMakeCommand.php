<?php
declare(strict_types=1);
namespace RabbitCMS\Modules\Devel\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use InvalidArgumentException;
use RabbitCMS\Modules\Managers\Modules;

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
     * SeederMakeCommand constructor.
     *
     * @param Filesystem $files
     * @param Composer   $composer
     * @param Modules    $modules
     */
    public function __construct(Filesystem $files, Composer $composer, Modules $modules)
    {
        parent::__construct($files, $composer);
        $this->modules = $modules;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        if (!is_null($moduleName = $this->input->getOption('module'))) {
            if (!$this->modules->has($moduleName)) {
                throw new InvalidArgumentException("Module {$moduleName} not found.");
            }
            return $this->modules->get($moduleName)->getPath("src/Database/Seeders/{$name}.php");
        }

        return parent::getPath($name);
    }

    /**
     * @inheritdoc
     */
    protected function rootNamespace()
    {
        if (!is_null($moduleName = $this->input->getOption('module'))) {
            if (!$this->modules->has($moduleName)) {
                throw new InvalidArgumentException("Module {$moduleName} not found.");
            }
            $module = $this->modules->get($moduleName);
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
