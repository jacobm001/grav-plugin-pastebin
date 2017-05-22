<?php
namespace Grav\Plugin;

use Grav\Common\Page\Page;
use Grav\Common\Page\Pages;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class PastebinPlugin
 * @package Grav\Plugin
 */
class PastebinPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onTwigTemplatePaths'  => ['onTwigTemplatePaths', 0],
            'onFormProcessed'      => ['onFormProcessed', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        $uri = $this->grav['uri'];

        if ( $uri->path() == $this->config->get('plugins.pastebin.route_new') ) {
            $this->grav['debugger']->addMessage('Building NewPaste page.');

            $this->enable([
                'onPagesInitialized' => ['addNewPastePage', 0],
            ]);

            return;
        }

        // $this->enable([
        //     'onPageContentRaw' => ['onPageContentRaw', 0]
        // ]);
    }

    public function onTwigTemplatePaths()
    {
        $twig = $this->grav['twig'];
        $twig->twig_paths[] = __DIR__ . '/templates';
    }

    public function addNewPastePage() 
    {
        $this->grav['debugger']->addMessage('Building new paste page');

        $route = $this->config->get('plugins.pastebin.route_new');
        $pages = $this->grav['pages'];
        $page = $pages->dispatch($route);

        if (!$page) {
            $page = new Page;
            $page->init(new \SplFileInfo(__DIR__ . "/pages/new_paste.md"));
            $page->slug(basename($route));

            $pages->addPage($page, $route);
            unset($this->grav['page']);
            $this->grav['page'] = $page;
        }
    }

    public function onFormProcessed(Event $event)
    {
        $this->grav['debugger']->addMessage('Processing form!');
    }
}
