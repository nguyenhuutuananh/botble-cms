<?php

namespace Botble\Base\Commands;

use Botble\Base\Models\Migration;
use Botble\Base\Supports\Helper;
use Composer\Autoload\ClassLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Schema;

class PluginRemoveCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:remove {name : The plugin that you want to remove} {--force : Force to remove plugin without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a plugin in the /plugins directory.';

    /**
     * @var PluginDeactivateCommand
     */
    protected $deactivateCommand;

    /**
     * Create a new key generator command.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param PluginDeactivateCommand $deactivateCommand
     * @author Sang Nguyen
     */
    public function __construct(Filesystem $files, PluginDeactivateCommand $deactivateCommand)
    {
        parent::__construct();

        $this->files = $files;
        $this->deactivateCommand = $deactivateCommand;
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function handle()
    {
        if (!preg_match('/^[a-z0-9\-]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $plugin = ucfirst(strtolower($this->argument('name')));
        $location = plugin_path(strtolower($plugin));

        if ($this->files->isDirectory($location)) {
            if (app()->runningInConsole() &&
                $this->confirm('Are you sure you want to permanently delete? [yes|no]', $this->hasOption('force'))
            ) {
                return $this->processRemove($plugin, $location);
            }

            return $this->processRemove($plugin, $location);
        } else {
            $this->line('This plugin is not exists!');
        }

        return true;
    }

    /**
     * @param $plugin
     * @param $location
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     * @return boolean
     */
    protected function processRemove($plugin, $location)
    {
        $this->call($this->deactivateCommand->getName(), ['name' => strtolower($plugin)]);

        $content = get_file_data($location . '/plugin.json');
        if (!empty($content)) {
            if (!class_exists($content['provider'])) {
                $loader = new ClassLoader;
                $loader->setPsr4($content['namespace'], plugin_path($plugin . '/src'));
                $loader->register(true);
            }

            Schema::disableForeignKeyConstraints();
            if (class_exists($content['namespace'] . 'Plugin')) {
                call_user_func([$content['namespace'] . 'Plugin', 'remove']);
            }
            Schema::enableForeignKeyConstraints();
            $this->line('<info>Remove plugin successfully!</info>');
        }

        $migrations = scan_folder($location . '/database/migrations');
        foreach ($migrations as $migration) {
            Migration::where('migration', pathinfo($migration, PATHINFO_FILENAME))->delete();
        }

        $this->files->deleteDirectory($location);

        if (empty($this->files->directories(plugin_path()))) {
            $this->files->deleteDirectory(plugin_path());
        }

        Helper::removePluginData($plugin);

        $this->call('cache:clear');

        return true;
    }
}
