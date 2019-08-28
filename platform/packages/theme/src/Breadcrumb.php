<?php

namespace Botble\Theme;

use URL;

class Breadcrumb
{
    /**
     * Crumbs
     *
     * @var array
     */
    public $crumbs = [];

    /**
     * Add breadcrumb to array.
     *
     * @param  mixed $label
     * @param  string $url
     * @return Breadcrumb
     */
    public function add($label, $url = '')
    {
        if (is_array($label)) {
            if (count($label) > 0) {
                foreach ($label as $crumb) {
                    $defaults = [
                        'label' => '',
                        'url'   => '',
                    ];
                    $crumb = array_merge($defaults, $crumb);
                    $this->add($crumb['label'], $crumb['url']);
                }
            }
        } else {
            $label = trim(strip_tags($label, '<i><b><strong>'));
            if (!preg_match('|^http(s)?|', $url)) {
                $url = URL::to($url);
            }
            $this->crumbs[] = ['label' => $label, 'url' => $url];
        }

        return $this;
    }

    /**
     * Get crumbs.
     *
     * @return array
     */
    public function getCrumbs()
    {
        return $this->crumbs;
    }

    /**
     * Render breadcrumbs.
     *
     * @return string
     * @author Sang Nguyen
     * @throws \Throwable
     */
    public function render()
    {
        $crumbs = $this->getCrumbs();

        return view('packages.theme::partials.breadcrumb', compact('crumbs'))->render();
    }
}
