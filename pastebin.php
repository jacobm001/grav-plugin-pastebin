<?php
namespace Grav\Plugin;

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
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
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

    public function addNewPastePage() 
    {
        // $pages = $this->
        $this->grav['debugger']->addMessage('BAM!');
    }
}
