<?php

namespace Botble\DevTool\Commands;

use Botble\Setting\Supports\SettingStore;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class InstallCommand extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install CMS';

    /**
     * @var UserCreateCommand
     */
    protected $userCreateCommand;

    /**
     * InstallCommand constructor.
     * @param UserCreateCommand $userCreateCommand
     */
    public function __construct(UserCreateCommand $userCreateCommand)
    {
        parent::__construct();
        $this->userCreateCommand = $userCreateCommand;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        $this->info('Starting installation...');
        try {
            $this->call('migrate:fresh');
        } catch (QueryException $exception) {
            $this->call('migrate:fresh');
        }

        if (config('database.default') === 'mysql' && $this->confirm('Do you want to install sample data? [yes|no]')) {
            $this->call('cms:theme:install-sample-data');
        }

        if ($this->confirm('Do you want to new admin user? [yes|no]')) {
            $this->call($this->userCreateCommand->getName());
        }

        $this->info('Publish vendor assets');
        $this->call('vendor:publish', ['--tag' => 'cms-public']);

        $this->info('Adding the storage symlink to your public folder');
        $this->call('storage:link');

        $this->info('Installed Botble CMS successfully!');

        return true;
    }
}
