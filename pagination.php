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
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0]
        ]);
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Enable pagination if page has params.pagination = true.
     */
    public function onPageInitialized()
    {
        /** @var Page $page */
        $page = $this->grav['page'];

        if ($page && ($page->value('header.content.pagination') || $page->value('header.pagination'))) {
            $this->enable([
                'onCollectionProcessed' => ['onCollectionProcessed', 0],
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
            ]);

            $template = $this->grav['uri']->param('tmpl');
            if ($template) {
                $page->template($template);
            }
        }
    }

    /**
     * Create pagination object for the page.
     *
     * @param Event $event
     */
    public function onCollectionProcessed(Event $event)
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
    public function onTwigSiteVariables()
    {
        if ($this->config->get('plugins.pagination.built_in_css')) {
            $this->grav['assets']->add('plugin://pagination/css/pagination.css');
        }
    }
}
