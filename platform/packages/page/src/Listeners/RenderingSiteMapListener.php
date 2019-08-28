<?php

namespace Botble\Page\Listeners;

use Botble\Page\Repositories\Interfaces\PageInterface;
use SiteMapManager;

class RenderingSiteMapListener
{
    /**
     * @var PageInterface
     */
    protected $pageRepository;

    /**
     * RenderingSiteMapListener constructor.
     * @param PageInterface $pageRepository
     */
    public function __construct(PageInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Handle the event.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function handle()
    {
        $pages = $this->pageRepository->getDataSiteMap();

        foreach ($pages as $page) {
            SiteMapManager::add(route('public.single', $page->slug), $page->updated_at, '0.8', 'daily');
        }
    }
}
