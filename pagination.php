<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Component\EventDispatcher\Event;

class PaginationPlugin extends Plugin
{
    /**
     * @var PaginationHelper
     */
    protected $pagination;

    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return [
            'onAfterTwigTemplatesPaths' => ['onAfterTwigTemplatesPaths', 0],
            'onAfterGetPage' => ['onAfterGetPage', 0],
        ];
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onAfterTwigTemplatesPaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Enable pagination if page has params.pagination = true.
     */
    public function onAfterGetPage()
    {
        /** @var Page $page */
        $page = $this->grav['page'];

        if ($page && $page->value('header.pagination')) {
            $this->enable([
                'onAfterCollectionProcessed' => ['onAfterCollectionProcessed', 0],
                'onAfterTwigSiteVars' => ['onAfterTwigSiteVars', 0]
            ]);
        }
    }

    /**
     * Create pagination object for the page.
     *
     * @param Event $event
     */
    public function onAfterCollectionProcessed(Event $event)
    {
        /** @var Collection $collection */
        $collection = $event['collection'];
        $params = $collection->params();

        // Only add pagination if it has been enabled for the collection.
        if (empty($params['pagination'])) {
            return;
        }

        if (!empty($params['limit']) && $collection->count() > $params['limit']) {
            require_once __DIR__ . '/classes/paginationhelper.php';
            $this->pagination = new PaginationHelper($collection);
            $collection->setParams(['pagination' => $this->pagination]);
        }
    }

    /**
     * Set needed variables to display pagination.
     */
    public function onAfterTwigSiteVars()
    {
        if ($this->config->get('plugins.pagination.built_in_css')) {
            $this->grav['assets']->add('@plugin/pagination/css:pagination.css');
        }
    }
}
