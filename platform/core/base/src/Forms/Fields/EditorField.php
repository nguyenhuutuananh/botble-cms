<?php

namespace Botble\Base\Forms\Fields;

use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;

class EditorField extends FormField
{

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'core/base::forms.fields.editor';
    }

    /**
     * @param array $options
     * @param bool $showLabel
     * @param bool $showField
     * @param bool $showError
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['class'] = Arr::get($options, 'class', '') . 'form-control editor-' .
            setting('rich_editor', config('core.base.general.editor.primary'));

        $options['id'] = Arr::has($options, 'id') ? $options['id'] : $this->getName();
        $options['with-short-code'] = Arr::get($options, 'with-short-code', false);

        return parent::render($options, $showLabel, $showField, $showError);
    }
}