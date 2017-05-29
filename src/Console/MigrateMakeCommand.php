<?php
declare(strict_types=1);

namespace RabbitCMS\Modules\Devel\Console;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use InvalidArgumentException;
use RabbitCMS\Modules\Managers\Modules;

/**
 * Class MigrateMakeCommand
 *
 * @package RabbitCMS\Modules\Devel\Console
 */
class MigrateMakeCommand extends \Illuminate\Database\Console\Migrations\MigrateMakeCommand
{
    /**
     * Modules manager.
     *
     * @var Modules
     */
    protected $modules;

    /**
     * MigrateMakeCommand constructor.
     *
     * @param MigrationCreator $creator
     * @param Composer         $composer
     * @param Modules          $modules
     */
    public function __construct(MigrationCreator $creator, Composer $composer, Modules $modules)
    {
        $this->signature .= "\n{--module= : The module where the migration file should be created.}";
        parent::__construct($creator, $composer);
        $this->modules = $modules;
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        $targetPath = $this->input->getOption('path');
        if (!is_null($moduleName = $this->input->getOption('module'))) {
            if (!$this->modules->has($moduleName)) {
                throw new InvalidArgumentException("Module {$moduleName} not found.");
            }
            $module = $this->modules->get($moduleName);
            $path = $module->getPath($targetPath ?: 'src/Database/Migrations');
            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                throw new InvalidArgumentException("Can not create directory {$path}");
            }

            return $path;
        }

        return parent::getMigrationPath();
    }
}
