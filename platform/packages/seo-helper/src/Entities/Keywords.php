<?php

namespace Botble\SeoHelper\Entities;

use Botble\SeoHelper\Contracts\Entities\KeywordsContract;
use Botble\SeoHelper\Helpers\Meta;

class Keywords implements KeywordsContract
{
    /**
     * The meta name.
     *
     * @var string
     */
    protected $name = 'keywords';

    /**
     * The meta content.
     *
     * @var array
     */
    protected $content = [];

    /**
     * Make Description instance.
     * @author ARCANEDEV
     */
    public function __construct()
    {
        $this->set((array)setting('seo_keywords', []));
    }

    /**
     * Get raw keywords content.
     *
     * @return array
     * @author ARCANEDEV
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get keywords content.
     *
     * @return string
     * @author ARCANEDEV
     */
    public function get()
    {
        return implode(', ', $this->getContent());
    }

    /**
     * Set keywords content.
     *
     * @param  array|string $content
     *
     * @return self
     * @author ARCANEDEV
     */
    public function set($content)
    {
        if (is_string($content)) {
            $content = explode(',', $content);
        }

        if (!is_array($content)) {
            $content = (array)$content;
        }

        $this->content = array_map(function ($keyword) {
            return $this->clean($keyword);
        }, $content);

        return $this;
    }

    /**
     * Make Keywords instance.
     *
     * @param  array|string $keywords
     *
     * @return self
     * @author ARCANEDEV
     */
    public static function make($keywords)
    {
        return new self();
    }

    /**
     * Add a keyword to the content.
     *
     * @param  string $keyword
     *
     * @return self
     * @author ARCANEDEV
     */
    public function add($keyword)
    {
        $this->content[] = $this->clean($keyword);

        return $this;
    }

    /**
     * Add many keywords to the content.
     *
     * @param  array $keywords
     *
     * @return self
     * @author ARCANEDEV
     */
    public function addMany(array $keywords)
    {
        foreach ($keywords as $keyword) {
            $this->add($keyword);
        }

        return $this;
    }

    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV
     */
    public function render()
    {
        if (!$this->hasContent()) {
            return '';
        }

        return Meta::make($this->name, $this->get())->render();
    }

    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Check if keywords has content.
     *
     * @return bool
     * @author ARCANEDEV
     */
    protected function hasContent()
    {
        return !empty($this->getContent());
    }

    /**
     * Clean the string.
     *
     * @param  string $value
     *
     * @return string
     * @author ARCANEDEV
     */
    protected function clean($value)
    {
        return trim(strip_tags($value));
    }
}
