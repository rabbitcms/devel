<?php
declare(strict_types=1);

namespace RabbitCMS\Modules\Devel\Console;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\Composer;
use InvalidArgumentException;
use RabbitCMS\Modules\Exceptions\ModuleNotFoundException;
use RabbitCMS\Modules\Facades\Modules;

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
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        $this->signature .= "\n{--module= : The module where the migration file should be created.}";
        parent::__construct($creator, $composer);
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getMigrationPath()
    {
        if (null !== $moduleName = $this->input->getOption('module')) {
            try {
                $module = Modules::getByName($moduleName);
            } catch (ModuleNotFoundException $exception) {
                throw new InvalidArgumentException("Module {$moduleName} not found.", 0, $exception);
            }
            $path = $module->getPath('src/Database/Migrations');
            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                throw new InvalidArgumentException("Can not create directory {$path}");
            }

            return $path;
        }

        return parent::getMigrationPath();
    }
}
