<?php

namespace Botble\Base\Commands\Abstracts;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;

abstract class BaseMakeCommand extends Command
{
    /**
     * Generate the module in Modules directory.
     * @author Sang Nguyen
     * @param $from
     * @param $to
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function publishStubs(string $from, string $to)
    {
        if (File::isDirectory($from)) {
            $this->publishDirectory($from, $to);
        } else {
            File::copy($from, $to);
        }
    }

    /**
     * Search and replace all occurrences of ‘Module’
     * in all files with the name of the new module.
     * @author Sang Nguyen
     * @param string $patern
     * @param string $location
     * @param null $stub
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function searchAndReplaceInFiles(string $patern, string $location, $stub = null)
    {
        $replacements = $this->replacements($patern);

        if (File::isFile($location)) {
            if (!$stub) {
                $stub = File::get($this->getStub());
            }

            $replace = $this->getReplacements($patern) + $this->baseReplacements($patern);

            $content = str_replace(array_keys($replace), $replace, $stub);

            File::put($location, $content);
            return true;
        }

        $manager = new MountManager([
            'directory' => new Flysystem(new LocalAdapter($location)),
        ]);

        foreach ($manager->listContents('directory://', true) as $file) {
            if ($file['type'] === 'file') {
                $content = str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $manager->read('directory://' . $file['path'])
                );

                $manager->put('directory://' . $file['path'], $content);
            }
        }

        return true;
    }

    /**
     * Rename models and repositories.
     * @param string $location
     * @return boolean
     * @author Sang Nguyen
     */
    public function renameFiles($patern, $location)
    {
        $paths = scan_folder($location);

        if (empty($paths)) {
            return false;
        }

        foreach ($paths as $path) {
            $path = $location . DIRECTORY_SEPARATOR . $path;

            $newPath = $this->transformFileName($patern, $path);
            rename($path, $newPath);

            $this->renameFiles($patern, $newPath);
        }

        return true;
    }

    /**
     * Rename file in path.
     *
     * @param string $path
     * @return string
     * @author Sang Nguyen
     */
    public function transformFileName(string $patern, string $path)
    {
        $replacements = $this->replacements($patern);

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $path
        );
    }

    /**
     * Publish the directory to the given directory.
     *
     * @param string $from
     * @param string $to
     * @return void
     * @author Sang Nguyen
     * @throws \League\Flysystem\FileNotFoundException
     */
    protected function publishDirectory($from, $to)
    {
        $manager = new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to'   => new Flysystem(new LocalAdapter($to)),
        ]);

        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file' && (!$manager->has('to://' . $file['path']) || $this->option('force'))) {
                $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
            }
        }
    }

    /**
     * Create the directory to house the published files if needed.
     *
     * @param string $path
     * @return void
     * @author Sang Nguyen
     */
    protected function createParentDirectory($path)
    {
        if (!File::isDirectory($path) && !File::isFile($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * @param string $replaceText
     * @return array
     */
    public function baseReplacements(string $replaceText)
    {
        return [
            '{-plugin}'      => strtolower($replaceText),
            '{plugin}'       => Str::snake(str_replace('-', '_', $replaceText)),
            '{+plugin}'      => Str::camel($replaceText),
            '{plugins}'      => Str::plural(Str::snake(str_replace('-', '_', $replaceText))),
            '{-plugins}'     => Str::plural($replaceText),
            '{PLUGIN}'       => strtoupper(Str::snake(str_replace('-', '_', $replaceText))),
            '{Plugin}'       => ucfirst(Str::camel($replaceText)),
            '.stub'          => '.php',
            '{migrate_date}' => now(config('app.timezone'))->format('Y_m_d_His'),
        ];
    }

    /**
     * @param string $replaceText
     * @return array
     */
    public function replacements(string $replaceText)
    {
        return array_merge($this->getReplacements($replaceText), $this->baseReplacements($replaceText));
    }

    /**
     * @param string $replaceText
     * @return array
     */
    abstract public function getReplacements(string $replaceText);

    /**
     * @return string
     */
    abstract public function getStub();
}
