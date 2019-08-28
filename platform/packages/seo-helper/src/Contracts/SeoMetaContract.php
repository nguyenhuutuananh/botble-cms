<?php

namespace Botble\SeoHelper\Contracts;

use Botble\SeoHelper\Contracts\Entities\DescriptionContract;
use Botble\SeoHelper\Contracts\Entities\KeywordsContract;
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
     * @author ARCANEDEV
     */
    public function title(TitleContract $title);

    /**
     * Set the Description instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\DescriptionContract $description
     *
     * @return self
     * @author ARCANEDEV
     */
    public function description(DescriptionContract $description);

    /**
     * Set the Keywords instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\KeywordsContract $keywords
     *
     * @return self
     * @author ARCANEDEV
     */
    public function keywords(KeywordsContract $keywords);

    /**
     * Set the MiscTags instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\MiscTagsContract $misc
     *
     * @return self
     * @author ARCANEDEV
     */
    public function misc(MiscTagsContract $misc);

    /**
     * Set the Webmasters instance.
     *
     * @param  \Botble\SeoHelper\Contracts\Entities\WebmastersContract $webmasters
     *
     * @return self
     * @author ARCANEDEV
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
     * @author ARCANEDEV
     */
    public function setTitle($title, $siteName = null, $separator = null);

    /**
     * Set the description content.
     *
     * @param  string $content
     *
     * @return self
     * @author ARCANEDEV
     */
    public function setDescription($content);

    /**
     * Set the keywords content.
     *
     * @param  array|string $content
     *
     * @return self
     * @author ARCANEDEV
     */
    public function setKeywords($content);

    /**
     * Add a keyword.
     *
     * @param  string $keyword
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addKeyword($keyword);

    /**
     * Add many keywords.
     *
     * @param  array $keywords
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addKeywords(array $keywords);

    /**
     * Add a webmaster tool site verifier.
     *
     * @param  string $webmaster
     * @param  string $content
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addWebmaster($webmaster, $content);

    /**
     * Set the current URL.
     *
     * @param  string $url
     *
     * @return self
     * @author ARCANEDEV
     */
    public function setUrl($url);

    /**
     * Set the Google Analytics code.
     *
     * @param  string $code
     *
     * @return self
     * @author ARCANEDEV
     */
    public function setGoogleAnalytics($code);

    /**
     * Add a meta tag.
     *
     * @param  string $name
     * @param  string $content
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addMeta($name, $content);

    /**
     * Add many meta tags.
     *
     * @param  array $meta
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addMetas(array $meta);
}
