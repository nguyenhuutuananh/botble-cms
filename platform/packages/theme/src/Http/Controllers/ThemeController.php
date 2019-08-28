<?php

namespace Botble\Theme\Http\Controllers;

use App\Console\Kernel;
use Assets;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Supports\SettingStore;
use Botble\Theme\Commands\ThemeActivateCommand;
use Botble\Theme\Commands\ThemeRemoveCommand;
use Botble\Theme\Forms\CustomCSSForm;
use Botble\Theme\Http\Requests\CustomCssRequest;
use Exception;
use File;
use Illuminate\Http\Request;
use ThemeOption;

class ThemeController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList()
    {
        page_title()->setTitle(trans('packages/theme::theme.theme'));

        if (File::exists(theme_path('.DS_Store'))) {
            File::delete(theme_path('.DS_Store'));
        }

        Assets::addAppModule(['theme']);

        return view('packages.theme::list');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getOptions()
    {
        page_title()->setTitle(trans('packages/theme::theme.theme_options'));

        Assets::addScripts(['bootstrap-tagsinput', 'typeahead', 'are-you-sure', 'colorpicker'])
            ->addStyles(['bootstrap-tagsinput', 'colorpicker'])
            ->addStylesDirectly([
                'vendor/core/packages/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css',
                'vendor/core/packages/fontselect/fontselect-default.css',
            ])
            ->addScriptsDirectly([
                'vendor/core/packages/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js',
                'vendor/core/packages/fontselect/jquery.fontselect.min.js',
            ])
            ->addAppModule(['theme-options']);

        return view('packages.theme::options');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param SettingStore $settingStore
     * @return BaseHttpResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Sang Nguyen
     */
    public function postUpdate(Request $request, BaseHttpResponse $response, SettingStore $settingStore)
    {
        $sections = ThemeOption::constructSections();
        foreach ($sections as $section) {
            foreach ($section['fields'] as $field) {
                $key = $field['attributes']['name'];
                ThemeOption::setOption($key, $request->input($key, 0));
            }
        }
        $settingStore->save();

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param Kernel $kernel
     * @param ThemeActivateCommand $themeActivateCommand
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postActivateTheme(
        Request $request,
        BaseHttpResponse $response,
        Kernel $kernel,
        ThemeActivateCommand $themeActivateCommand
    )
    {
        $kernel->call($themeActivateCommand->getName(), ['name' => $request->input('theme')]);

        return $response
            ->setMessage(trans('packages/theme::theme.active_success'));
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     * @author Sang Nguyen
     */
    public function getCustomCss(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/theme::theme.custom_css'));

        Assets::addAppModule(['custom-css'])
            ->addStylesDirectly([
                'vendor/core/packages/codemirror/lib/codemirror.css',
                'vendor/core/packages/codemirror/addon/hint/show-hint.css',
                'vendor/core/css/custom-css.css',
            ])
            ->addScriptsDirectly([
                'vendor/core/packages/codemirror/lib/codemirror.js',
                'vendor/core/packages/codemirror/lib/css.js',
                'vendor/core/packages/codemirror/addon/hint/show-hint.js',
                'vendor/core/packages/codemirror/addon/hint/anyword-hint.js',
                'vendor/core/packages/codemirror/addon/hint/css-hint.js',
            ]);

        return $formBuilder->create(CustomCSSForm::class)->renderForm();
    }

    /**
     * @param CustomCssRequest $request
     * @param BaseHttpResponse $response
     * @param SettingStore $setting
     * @return BaseHttpResponse
     * @author Sang Nguyen
     */
    public function postCustomCss(CustomCssRequest $request, BaseHttpResponse $response, SettingStore $setting)
    {
        $file = public_path(config('packages.theme.general.themeDir') . '/' . $setting->get('theme') . '/css/style.integration.css');
        $css = $request->input('custom_css');
        $css = htmlspecialchars(htmlentities(strip_tags($css)));
        save_file_data($file, $css, false);

        return $response->setMessage(__('Update custom CSS successfully!'));
    }

    /**
     * Remove plugin
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param Kernel $kernel
     * @param ThemeRemoveCommand $themeRemoveCommand
     * @return mixed
     * @author Sang Nguyen
     */
    public function postRemoveTheme(
        Request $request,
        BaseHttpResponse $response,
        Kernel $kernel,
        ThemeRemoveCommand $themeRemoveCommand
    )
    {
        $theme = strtolower($request->input('theme'));

        if (in_array($theme, scan_folder(theme_path()))) {
            try {
                $kernel->call($themeRemoveCommand->getName(), ['name' => $theme, '--force' => true]);
                return $response->setMessage(trans('packages/theme::theme.remove_theme_success'));
            } catch (Exception $ex) {
                info($ex->getMessage());
                return $response
                    ->setError()
                    ->setMessage($ex->getMessage());
            }
        }

        return $response
            ->setError()
            ->setMessage(trans('packages/theme::theme.theme_is_note_existed'));
    }
}
