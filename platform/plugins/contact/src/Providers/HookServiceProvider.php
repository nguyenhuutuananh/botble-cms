<?php

namespace Botble\Contact\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;
use Botble\Contact\Repositories\Interfaces\ContactInterface;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function boot()
    {
        add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, [$this, 'registerTopHeaderNotification'], 120);
        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getUnReadCount'], 120, 2);
        add_filter(BASE_FILTER_AFTER_SETTING_EMAIL_CONTENT, [$this, 'addContactSetting'], 99, 1);

        if (function_exists('add_shortcode')) {
            add_shortcode('contact-form', __('Contact form'), __('Add contact form'), [$this, 'form']);
            shortcode()
                ->setAdminConfig('contact-form', view('plugins.contact::partials.short-code-admin-config')->render());
        }
    }

    /**
     * @param string $options
     * @return string
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function registerTopHeaderNotification($options)
    {
        if (Auth::user()->hasPermission('contacts.edit')) {
            $contacts = $this->app->make(ContactInterface::class)
                ->getUnread(['id', 'name', 'email', 'phone', 'created_at']);

            return $options . view('plugins.contact::partials.notification', compact('contacts'))->render();
        }
        return null;
    }

    /**
     * @param $number
     * @param $menu_id
     * @return string
     * @author Sang Nguyen
     */
    public function getUnReadCount($number, $menu_id)
    {
        if ($menu_id == 'cms-plugins-contact') {
            $unread = $this->app->make(ContactInterface::class)->countUnread();
            if ($unread > 0) {
                return '<span class="badge badge-success">' . $unread . '</span>';
            }
        }
        return $number;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function form($shortcode)
    {
        $view = 'plugins.contact::forms.contact';

        if ($shortcode->view && view()->exists($shortcode->view)) {
            $view = $shortcode->view;
        }
        return view($view, ['header' => $shortcode->header])->render();
    }

    /**
     * @param null $data
     * @return string
     * @throws \Throwable
     * @author Sang Nguyen
     */
    public function addContactSetting($data = null)
    {
        return $data . view('plugins.contact::setting')->render();
    }
}
