<?php

namespace Botble\SeoHelper\Contracts\Entities;

use Botble\SeoHelper\Contracts\RenderableContract;

interface KeywordsContract extends RenderableContract
{
    /**
     * Get content.
     *
     * @return array
     * @author ARCANEDEV
     */
    public function getContent();

    /**
     * Set description content.
     *
     * @param  array|string $content
     *
     * @return self
     * @author ARCANEDEV
     */
    public function set($content);

    /**
     * Make Keywords instance.
     *
     * @param  array|string $keywords
     *
     * @return self
     * @author ARCANEDEV
     */
    public static function make($keywords);

    /**
     * Add a keyword to the content.
     *
     * @param  string $keyword
     *
     * @return self
     * @author ARCANEDEV
     */
    public function add($keyword);

    /**
     * Add many keywords to the content.
     *
     * @param  array $keywords
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addMany(array $keywords);
}
