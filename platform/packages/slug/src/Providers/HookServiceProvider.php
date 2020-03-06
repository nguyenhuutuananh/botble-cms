<?php

namespace Botble\Slug\Providers;

use Assets;
use Botble\Base\Forms\FormAbstract;
use Botble\Slug\Forms\Fields\PermalinkField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Kris\LaravelFormBuilder\FormHelper;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_filter(BASE_FILTER_SLUG_AREA, [$this, 'addSlugBox'], 17, 3);

        add_filter(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, [$this, 'getItemSlug'], 3, 3);

        add_filter('form_custom_fields', [$this, 'addCustomFormFields'], 29, 2);
    }

    /**
     * @param $screen
     * @param $object
     * @param null $prefix
     * @return null|string
     * @throws \Throwable
     */
    public function addSlugBox($screen, $object = null)
    {
        if (in_array($screen, config('packages.slug.general.supported'))) {
            Assets::addScriptsDirectly('vendor/core/packages/slug/js/slug.js');
            $prefix = Arr::get(config('packages.slug.general.prefixes', []), $screen, '');
            return view('packages/slug::partials.slug', compact('object', 'screen', 'prefix'))->render();
        }

        return null;
    }

    /**
     * @param Builder $data
     * @param Model $model
     * @param string $screen
     * @return mixed
     */
    public function getItemSlug($data, $model, $screen = null)
    {
        if (!empty($screen) &&
            in_array($screen, config('packages.slug.general.supported')) &&
            method_exists($model, 'getScreen') &&
            $screen == $model->getScreen()
        ) {
            $table = $model->getTable();
            $select = [$table . '.*'];
            /**
             * @var \Eloquent $data
             */
            $rawBindings = $data->getRawBindings();
            /**
             * @var \Eloquent $rawBindings
             */
            $query = $rawBindings->getQuery();
            if ($query instanceof Builder) {
                $querySelect = $data->getQuery()->columns;
                if (!empty($querySelect)) {
                    $select = $querySelect;
                }
            }

            foreach ($select as &$column) {
                if (strpos($column, '.') === false) {
                    $column = $table . '.' . $column;
                }
            }

            $select = array_merge($select, ['slugs.key']);

            return $data
                ->leftJoin('slugs', function (JoinClause $join) use ($table) {
                    $join->on('slugs.reference_id', '=', $table . '.id');
                })
                ->select($select)
                ->where('slugs.reference', '=', $model->getScreen());
        }

        return $data;
    }

    /**
     * @param FormAbstract $form
     * @param FormHelper $formHelper
     * @return FormAbstract
     */
    public function addCustomFormFields(FormAbstract $form, FormHelper $formHelper)
    {
        if (!$formHelper->hasCustomField('permalink') && config('packages.slug.general.supported')) {
            $form->addCustomField('permalink', PermalinkField::class);
        }

        return $form;
    }
}
