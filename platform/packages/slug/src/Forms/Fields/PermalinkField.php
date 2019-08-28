<?php

namespace Botble\Slug\Forms\Fields;

use Assets;
use Kris\LaravelFormBuilder\Fields\FormField;

class PermalinkField extends FormField
{

    /**
     * @return string
     * @author Sang Nguyen
     */
    protected function getTemplate()
    {
        Assets::addAppModule(['slug']);

        return 'packages.slug::forms.fields.permalink';
    }
}