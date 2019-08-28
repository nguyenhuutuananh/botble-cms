<?php

namespace Botble\Widget\Factories;

use Botble\Widget\AbstractWidget;
use Botble\Widget\Contracts\ApplicationWrapperContract;
use Botble\Widget\Misc\InvalidWidgetClassException;
use Botble\Widget\Misc\ViewExpressionTrait;
use Botble\Widget\WidgetId;
use Exception;
use Illuminate\Support\Str;

abstract class AbstractWidgetFactory
{
    use ViewExpressionTrait;

    /**
     * Widget object to work with.
     *
     * @var AbstractWidget
     */
    protected $widget;

    /**
     * Widget configuration array.
     *
     * @var array
     */
    protected $widgetConfig;

    /**
     * The name of the widget being called.
     *
     * @var string
     */
    public $widgetName;

    /**
     * Array of widget parameters excluding the first one (config).
     *
     * @var array
     */
    public $widgetParams;

    /**
     * Array of widget parameters including the first one (config).
     *
     * @var array
     */
    public $widgetFullParams;

    /**
     * Laravel application wrapper for better testability.
     *
     * @var ApplicationWrapperContract;
     */
    public $app;

    /**
     * Another factory that produces some javascript.
     *
     * @var JavascriptFactory
     */
    protected $javascriptFactory;

    /**
     * The flag for not wrapping content in a special container.
     *
     * @var bool
     */
    public static $skipWidgetContainer = false;

    /**
     * Constructor.
     *
     * @param ApplicationWrapperContract $app
     * @author Sang Nguyen
     */
    public function __construct(ApplicationWrapperContract $app)
    {
        $this->app = $app;

        $this->javascriptFactory = new JavascriptFactory($this);
    }

    /**
     * Magic method that catches all widget calls.
     *
     * @param string $widgetName
     * @param array $params
     * @return mixed
     * @author Sang Nguyen
     */
    public function __call($widgetName, array $params = [])
    {
        array_unshift($params, $widgetName);

        return call_user_func_array([$this, 'run'], $params);
    }

    /**
     * Set class properties and instantiate a widget object.
     *
     * @param $params
     *
     * @throws InvalidWidgetClassException
     * @throws Exception
     * @author Sang Nguyen
     */
    protected function instantiateWidget(array $params = [])
    {
        WidgetId::increment();

        $this->widgetName = $this->parseFullWidgetNameFromString(array_shift($params));
        $this->widgetFullParams = $params;
        $this->widgetConfig = (array)array_shift($params);
        $this->widgetParams = $params;

        $rootNamespace = $this->app->config('packages.widget.general.default_namespace', $this->app->getNamespace() . 'Widgets');

        $fqcn = $rootNamespace . '\\' . $this->widgetName;
        $widgetClass = class_exists($fqcn) ? $fqcn : $this->widgetName;

        if (!class_exists($widgetClass, false)) {
            throw new Exception($widgetClass . ' is not exists');
        }

        if (!is_subclass_of($widgetClass, AbstractWidget::class)) {
            throw new InvalidWidgetClassException('Class "' . $widgetClass . '" must extend "' . AbstractWidget::class . '" class');
        }

        $this->widget = new $widgetClass($this->widgetConfig);
    }

    /**
     * Convert stuff like 'profile.feedWidget' to 'Profile\FeedWidget'.
     *
     * @param $widgetName
     * @return string
     * @author Sang Nguyen
     */
    protected function parseFullWidgetNameFromString($widgetName)
    {
        return Str::studly(str_replace('.', '\\', $widgetName));
    }

    /**
     * Wrap the given content in a container if it's not an ajax call.
     *
     * @param $content
     * @return string
     * @author Sang Nguyen
     */
    protected function wrapContentInContainer($content)
    {
        if (self::$skipWidgetContainer) {
            return $content;
        }

        $container = $this->widget->container();
        if (empty($container['element'])) {
            $container['element'] = 'div';
        }

        return '<' . $container['element'] . ' id="' . $this->javascriptFactory->getContainerId() . '" ' . $container['attributes'] . '>' . $content . '</' . $container['element'] . '>';
    }

    /**
     * Encrypt widget params to be transported via HTTP.
     *
     * @param array $params
     * @return string
     * @author Sang Nguyen
     */
    public function encryptWidgetParams($params)
    {
        return $this->app->make('encrypter')->encrypt(json_encode($params));
    }

    /**
     * Decrypt widget params that were transported via HTTP.
     *
     * @param string $params
     * @return array
     * @author Sang Nguyen
     */
    public function decryptWidgetParams($params)
    {
        $params = json_decode($this->app->make('encrypter')->decrypt($params), true);

        return $params ? $params : [];
    }
}
