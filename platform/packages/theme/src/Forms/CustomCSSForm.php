<?php

namespace Botble\Theme\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Theme\Http\Requests\CustomCssRequest;
use File;

class CustomCSSForm extends FormAbstract
{
    /**
     * @return mixed|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function buildForm()
    {
        $css = null;
        $file = public_path(config('packages.theme.general.themeDir') . '/' . setting('theme') . '/css/style.integration.css');
        if (File::exists($file)) {
            $css = get_file_data($file, false);
        }

        $this
            ->setUrl(route('theme.custom-css.post'))
            ->setValidatorClass(CustomCssRequest::class)
            ->add('custom_css', 'textarea', [
                'label'      => trans('packages/theme::theme.custom_css'),
                'label_attr' => ['class' => 'control-label'],
                'value'      => $css,
                'help_block' => [
                    'text' => __('Using Ctrl + Space to auto complete.'),
                ],
            ]);
    }
}