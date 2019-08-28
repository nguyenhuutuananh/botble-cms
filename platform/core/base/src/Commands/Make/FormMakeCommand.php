<?php

namespace Botble\Base\Commands\Make;

use Botble\Base\Commands\Abstracts\BaseMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class FormMakeCommand extends BaseMakeCommand
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
    protected $signature = 'cms:make:form {name : The table that you want to create} {plugin : Plugin name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a form';

    /**
     * Create a new key generator command.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @author Sang Nguyen
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (!preg_match('/^[a-z0-9\-\_]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $name = $this->argument('name');
        $path = plugin_path(strtolower($this->argument('plugin')) . '/src/Forms/' . ucfirst(Str::studly($name)) . 'Form.php');

        $this->publishStubs($this->getStub(), $path);
        $this->renameFiles($name, $path);
        $this->searchAndReplaceInFiles($name, $path);
        $this->line('------------------');

        $this->info('Create successfully!');

        return true;
    }

    /**
     * @return string
     */
    public function getStub()
    {
        return core_path('base/stubs/Form.stub');
    }

    /**
     * @param string $replaceText
     * @return array
     */
    public function getReplacements(string $replaceText)
    {
        return [
            '{-name}'  => strtolower($replaceText),
            '{name}'   => Str::snake(str_replace('-', '_', $replaceText)),
            '{+name}'  => Str::camel($replaceText),
            '{names}'  => Str::plural(Str::snake(str_replace('-', '_', $replaceText))),
            '{-names}' => Str::plural($replaceText),
            '{NAME}'   => strtoupper(Str::snake(str_replace('-', '_', $replaceText))),
            '{Name}'   => ucfirst(Str::camel($replaceText)),
            '.stub'     => '.php',
            '{Plugin}'  => ucfirst(Str::camel($this->argument('plugin'))),
        ];
    }
}
