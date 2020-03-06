<?php

namespace Botble\SeoHelper\Contracts;

use Botble\SeoHelper\Contracts\Entities\DescriptionContract;
use Botble\SeoHelper\Contracts\Entities\MiscTagsContract;
use Botble\SeoHelper\Contracts\Entities\TitleContract;
use Botble\SeoHelper\Contracts\Entities\WebmastersContract;

interface SeoMetaContract extends RenderableContract
{

    /**
     * Set the Title instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\TitleContract $title
     *
     * @return self
     */
    public function title(TitleContract $title);

    /**
     * Set the Description instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\DescriptionContract $description
     *
     * @return self
     */
    public function description(DescriptionContract $description);

    /**
     * Set the MiscTags instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\MiscTagsContract $misc
     *
     * @return self
     */
    public function misc(MiscTagsContract $misc);

    /**
     * Set the Webmasters instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\WebmastersContract $webmasters
     *
     * @return self
     */
    public function webmasters(WebmastersContract $webmasters);

    /**
     * Set the title.
     *
     * @param  string $title
     * @param  string $siteName
     * @param  string $separator
     *
     * @return self
     */
    public function setTitle($title, $siteName = null, $separator = null);

    /**
     * Set the description content.
     *
     * @param  string $content
     *
     * @return self
     */
    public function setDescription($content);

    /**
     * Add a webmaster tool site verifier.
     *
     * @param  string $webmaster
     * @param  string $content
     *
     * @return self
     */
    public function addWebmaster($webmaster, $content);

    /**
     * Set the current URL.
     *
     * @param  string $url
     *
     * @return self
     */
    public function setUrl($url);

    /**
     * Set the Google Analytics code.
     *
     * @param  string $code
     *
     * @return self
     */
    public function setGoogleAnalytics($code);

    /**
     * Add a meta tag.
     *
     * @param  string $name
     * @param  string $content
     *
     * @return self
     */
    public function addMeta($name, $content);

    /**
     * Add many meta tags.
     *
     * @param  array $meta
     *
     * @return self
     */
    public function addMetas(array $meta);
}
