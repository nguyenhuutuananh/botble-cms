<?php

namespace Botble\Base\Http\Controllers;

use Assets;
use Botble\Base\Commands\ClearLogCommand;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Helper;
use Botble\Base\Supports\SystemManagement;
use Botble\Base\Tables\InfoTable;
use Botble\Table\TableBuilder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SystemController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Throwable
     */
    public function getInfo(Request $request, TableBuilder $tableBuilder)
    {
        page_title()->setTitle(trans('core/base::system.info.title'));

        Assets::addScriptsDirectly('vendor/core/js/system-info.js')
            ->addStylesDirectly(['vendor/core/css/system-info.css']);

        $composerArray = SystemManagement::getComposerArray();
        $packages = SystemManagement::getPackagesAndDependencies($composerArray['require']);

        $infoTable = $tableBuilder->create(InfoTable::class);

        if ($request->expectsJson()) {
            return $infoTable->renderTable();
        }

        $systemEnv = SystemManagement::getSystemEnv();
        $serverEnv = SystemManagement::getServerEnv();
        $serverExtras = SystemManagement::getServerExtras();
        $systemExtras = SystemManagement::getSystemExtras();
        $extraStats = SystemManagement::getExtraStats();

        return view('core/base::system.info', compact(
            'packages',
            'infoTable',
            'systemEnv',
            'serverEnv',
            'extraStats',
            'serverExtras',
            'systemExtras'
        ));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCacheManagement()
    {
        page_title()->setTitle(trans('core/base::cache.cache_management'));

        Assets::addScriptsDirectly('vendor/core/js/cache.js');

        return view('core/base::system.cache');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param ClearLogCommand $clearLogCommand
     * @return BaseHttpResponse
     * @throws \Exception
     */
    public function postClearCache(Request $request, BaseHttpResponse $response, ClearLogCommand $clearLogCommand)
    {
        if (function_exists('proc_open')) {
            switch ($request->input('type')) {
                case 'clear_cms_cache':
                    Helper::executeCommand('cache:clear');
                    break;
                case 'refresh_compiled_views':
                    Helper::executeCommand('view:clear');
                    break;
                case 'clear_config_cache':
                    Helper::executeCommand('config:clear');
                    break;
                case 'clear_route_cache':
                    Helper::executeCommand('route:clear');
                    break;
                case 'clear_log':
                    Helper::executeCommand($clearLogCommand->getName());
                    break;
            }
        }

        return $response->setMessage(trans('core/base::cache.commands.' . $request->input('type') . '.success_msg'));
    }
}
