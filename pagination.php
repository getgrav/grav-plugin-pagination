<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

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
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0],
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
        }
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        if (!$this->active) return;

        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Enable pagination if page has params.pagination = true.
     */
    public function onPageInitialized()
    {
        if (!$this->active) return;

        /** @var Page $page */
        $page = $this->grav['page'];

        if ($page && $page->value('header.pagination')) {
            $this->enable([
                'onCollectionProcessed' => ['onCollectionProcessed', 0],
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
            ]);
        }
    }

    /**
     * Create pagination object for the page.
     *
     * @param Event $event
     */
    public function onCollectionProcessed(Event $event)
    {
        if (!$this->active) return;

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
    public function onTwigSiteVariables()
    {
        if (!$this->active) return;

        if ($this->config->get('plugins.pagination.built_in_css')) {
            $this->grav['assets']->add('plugin://pagination/css/pagination.css');
        }
    }
}
