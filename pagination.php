<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Page\Collection;
use Grav\Common\Page\Interfaces\PageInterface;
use Grav\Common\Plugin;
use Grav\Plugin\Pagination\PaginationHelper;
use Grav\Plugin\Pagination\PaginationPage;
use RocketTheme\Toolbox\Event\Event;
use Twig\TwigFunction;

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
            'onPluginsInitialized' => [
                ['autoload', 100001],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * [onPluginsInitialized:100000] Composer autoload.
     *
     * @return ClassLoader
     */
    public function autoload()
    {
        return require __DIR__ . '/vendor/autoload.php';
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

        class_alias(PaginationHelper::class, 'Grav\\Plugin\\PaginationHelper');
        class_alias(PaginationPage::class, 'Grav\\Plugin\\PaginationPage');

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0]
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
     * Add Twig Extensions
     */
    public function onTwigExtensions()
    {
         // Add Twig functions
        $this->grav['twig']->twig()->addFunction(
            new TwigFunction('paginate', [$this, 'paginateTwigFunction'])
        );
    }

    /**
     * Enable pagination if page has params.pagination = true.
     */
    public function onPageInitialized()
    {
        /** @var PageInterface $page */
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

    /**
     * pagination
     *
     * @param Collection $collection
     * @param int $limit
     * @param array $ignore_param_array      url parameters to be ignored in page links
     */
    public function paginateCollection($collection, $limit, $ignore_param_array = [])
    {
        $collection->setParams(['pagination' => 'true']);
        $collection->setParams(['limit' => $limit]);
        $collection->setParams(['ignore_params' => $ignore_param_array]);

        if ($collection->count() > $limit) {
            $this->pagination = new PaginationHelper($collection);
            $collection->setParams(['pagination' => $this->pagination]);

            $uri = $this->grav['uri'];
            $start = ($uri->currentPage() - 1) * $limit;

            if ($limit && $collection->count() > $limit) {
                $collection->slice($start, $limit);
            }
        }
    }

    public function paginateTwigFunction($collection, $limit, $ignore_url_param_array = [])
    {
        $this->paginateCollection($collection, $limit, $ignore_url_param_array);
    }
}
