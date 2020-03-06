<?php

namespace Botble\DevTool\Commands;

use Botble\PluginManagement\Commands\PluginActivateCommand;
use Botble\Setting\Supports\SettingStore;
use DB;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ThemeInstallSampleDataCommand extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:theme:install-sample-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install sample data for current active theme';

    /**
     * @var SettingStore
     */
    protected $settingStore;

    /**
     * @var PluginActivateCommand
     */
    protected $pluginActivateCommand;

    /**
     * ThemeInstallSampleDataCommand constructor.
     * @param SettingStore $settingStore
     * @param PluginActivateCommand $pluginActivateCommand
     */
    public function __construct(SettingStore $settingStore, PluginActivateCommand $pluginActivateCommand)
    {
        parent::__construct();
        $this->settingStore = $settingStore;
        $this->pluginActivateCommand = $pluginActivateCommand;
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->info('Processing ...');

        $theme = $this->settingStore->get('theme');
        if (!$theme) {
            $theme = Arr::first(scan_folder(theme_path()));
            $this->settingStore
                ->set('theme', $theme)
                ->save();
        }

        $content = get_file_data(theme_path($theme . '/theme.json'));

        if (!empty($content)) {
            $required_plugins = Arr::get($content, 'required_plugins', []);
            if (!empty($required_plugins)) {
                $this->info('Activating required plugins ...');
                foreach ($required_plugins as $required_plugin) {
                    $this->info('Activating plugin "' . $required_plugin . '"');
                    $this->call($this->pluginActivateCommand->getName(), ['name' => $required_plugin]);
                }
            }
        }

        $database = theme_path($theme . '/data/sample.sql');

        if (File::exists($database)) {

            $this->info('Importing sample data...');
            // Force the new login to be used
            DB::purge();
            DB::unprepared('USE `' . config('database.connections.mysql.database')  . '`');
            DB::connection()->setDatabaseName(config('database.connections.mysql.database') );
            DB::unprepared(File::get($database));
        }

        $this->info('Done!');

        return true;
    }
}
