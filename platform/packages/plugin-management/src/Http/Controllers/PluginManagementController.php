<?php

namespace Botble\PluginManagement\Http\Controllers;

use Assets;
use Botble\Base\Supports\Helper;
use Botble\PluginManagement\Commands\PluginActivateCommand;
use Botble\PluginManagement\Commands\PluginDeactivateCommand;
use Botble\PluginManagement\Commands\PluginRemoveCommand;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;

class PluginManagementController extends Controller
{
    /**
     * Show all plugins in system
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index()
    {
        page_title()->setTitle(trans('packages/plugin-management::plugin.plugins'));

        if (File::exists(plugin_path('.DS_Store'))) {
            File::delete(plugin_path('.DS_Store'));
        }

        $plugins = scan_folder(plugin_path());
        foreach ($plugins as $plugin) {
            if (File::exists(plugin_path($plugin . '/.DS_Store'))) {
                File::delete(plugin_path($plugin . '/.DS_Store'));
            }
        }

        Assets::addScriptsDirectly('vendor/core/js/plugin.js');

        $list = [];

        $plugins = scan_folder(plugin_path());
        if (!empty($plugins)) {
            $installed = get_active_plugins();
            foreach ($plugins as $plugin) {
                $pluginPath = plugin_path($plugin);
                if (!File::isDirectory($pluginPath) || !File::exists($pluginPath . '/plugin.json')) {
                    continue;
                }

                $content = get_file_data($pluginPath . '/plugin.json');
                if (!empty($content)) {
                    if (!in_array($plugin, $installed)) {
                        $content['status'] = 0;
                    } else {
                        $content['status'] = 1;
                    }

                    $content['path'] = $plugin;
                    $content['image'] = null;
                    if (File::exists($pluginPath . '/screenshot.png')) {
                        $content['image'] = base64_encode(File::get($pluginPath . '/screenshot.png'));
                    }
                    $list[] = (object)$content;
                }
            }
        }

        return view('packages/plugin-management::index', compact('list'));
    }

    /**
     * Activate or Deactivate plugin
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param PluginActivateCommand $pluginActivateCommand
     * @param PluginDeactivateCommand $pluginDeactivateCommand
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function update(
        Request $request,
        BaseHttpResponse $response,
        PluginActivateCommand $pluginActivateCommand,
        PluginDeactivateCommand $pluginDeactivateCommand
    ) {
        $plugin = strtolower($request->input('alias'));

        $content = get_file_data(plugin_path($plugin . '/plugin.json'));
        if (!empty($content)) {

            try {
                $activated_plugins = get_active_plugins();
                if (!in_array($plugin, $activated_plugins)) {
                    if (!empty(Arr::get($content, 'require'))) {
                        $count_required_plugins = count(array_intersect($content['require'], $activated_plugins));
                        $valid = $count_required_plugins == count($content['require']);
                        if (!$valid) {
                            return $response
                                ->setError()
                                ->setMessage(trans('packages/plugin-management::plugin.missing_required_plugins', [
                                    'plugins' => implode(',', $content['require']),
                                ]));
                        }
                    }

                    Helper::executeCommand($pluginActivateCommand->getName(), ['name' => $plugin]);
                } else {
                    Helper::executeCommand($pluginDeactivateCommand->getName(), ['name' => $plugin]);
                }

                return $response->setMessage(trans('packages/plugin-management::plugin.update_plugin_status_success'));
            } catch (Exception $ex) {
                info($ex->getMessage());
                return $response
                    ->setError()
                    ->setMessage($ex->getMessage());
            }
        }

        return $response
            ->setError()
            ->setMessage(trans('packages/plugin-management::plugin.invalid_plugin'));
    }

    /**
     * Remove plugin
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param PluginRemoveCommand $pluginRemoveCommand
     * @return mixed
     */
    public function destroy(Request $request, BaseHttpResponse $response, PluginRemoveCommand $pluginRemoveCommand)
    {
        $plugin = strtolower($request->input('plugin'));

        if (in_array($plugin, scan_folder(plugin_path()))) {
            try {
                Helper::executeCommand($pluginRemoveCommand->getName(), ['name' => $plugin, '--force' => true]);
                return $response->setMessage(trans('packages/plugin-management::plugin.remove_plugin_success'));
            } catch (Exception $ex) {
                return $response
                    ->setError()
                    ->setMessage($ex->getMessage());
            }
        }

        return $response
            ->setError()
            ->setMessage(trans('packages/plugin-management::plugin.invalid_plugin'));
    }
}
