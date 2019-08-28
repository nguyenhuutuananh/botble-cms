<?php

namespace Botble\Widget\Factories;

use Botble\Widget\Misc\InvalidWidgetClassException;
use Exception;

class WidgetFactory extends AbstractWidgetFactory
{

    /**
     * @var array
     */
    protected $widgets = [];

    /**
     * @param $widget
     * @return $this
     * @author Sang Nguyen
     */
    public function registerWidget($widget)
    {
        $this->widgets[] = new $widget;
        return $this;
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * Run widget without magic method.
     *
     * @return mixed
     * @author Sang Nguyen
     */
    public function run()
    {
        $args = func_get_args();
        try {
            $this->instantiateWidget($args);
        } catch (InvalidWidgetClassException $exception) {
            if (config('app.debug') == true) {
                return $exception->getMessage();
            }
            return null;
        } catch (Exception $exception) {
            if (config('app.debug') == true) {
                return $exception->getMessage();
            }
            return null;
        }

        $content = $this->getContentFromCache($args);

        if ($timeout = (float)$this->getReloadTimeout()) {
            $content .= $this->javascriptFactory->getReloader($timeout);
            $content = $this->wrapContentInContainer($content);
        }

        return $this->convertToViewExpression($content);
    }

    /**
     * Get widget reload timeout or false if it's not reloadable.
     *
     * @return bool|float|int
     * @author Sang Nguyen
     */
    protected function getReloadTimeout()
    {
        return isset($this->widget) && $this->widget->reloadTimeout ? $this->widget->reloadTimeout : false;
    }

    /**
     * Get widget cache time or false if it's not meant to be cached.
     *
     * @return bool|float|int
     * @author Sang Nguyen
     */
    protected function getCacheTime()
    {
        return isset($this->widget) && $this->widget->cacheTime ? $this->widget->cacheTime : false;
    }

    /**
     * Make call and get return widget content.
     *
     * @return mixed
     * @author Sang Nguyen
     */
    protected function getContent()
    {
        $content = $this->app->call([$this->widget, 'run'], $this->widgetParams);

        return is_object($content) ? $content->__toString() : $content;
    }

    /**
     * Gets content from cache if it's turned on.
     * Runs widget class otherwise.
     *
     * @param $args
     * @return mixed
     * @author Sang Nguyen
     */
    protected function getContentFromCache($args)
    {
        if ($cacheTime = (float)$this->getCacheTime()) {
            return $this->app->cache($this->widget->cacheKey($args), $cacheTime, function () {
                return $this->getContent();
            });
        }

        return $this->getContent();
    }
}
