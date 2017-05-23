<?php
namespace Grav\Plugin;

use \PDO;

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
    protected $db;

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
            'onFormProcessed'      => ['onFormProcessed', 0],
            'onTask.pastebin.new'  => ['newPaste', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if($this->isAdmin()) {
            return;
        }

        if(!$this->check_for_db()) {
            $this->build_new_db();
        }

        $uri = $this->grav['uri'];

        if ( $uri->path() == $this->config->get('plugins.pastebin.route_new') ) {
            $this->enable([
                'onPagesInitialized' => ['addNewPastePage', 0],
                'onPageInitialized'    => ['onPageInitialized', 0],
            ]);
            return;
        }
    }

    public function check_for_db()
    {
        if(!file_exists(DATA_DIR . "/pastebin.db"))
            return false;

        try {
            $this->db = new PDO('sqlite:' . DATA_DIR . 'pastebin.db');
        } catch(Exception $e) {
            $this->grav['debugger']->addMessage($e);
            return false;
        }

        return true;
    }

    public function build_new_db()
    {
        $this->grav['debugger']->addMessage('Pastebin database not found. Building a new one...');
        
        $this->db = new PDO('sqlite:' . DATA_DIR . 'pastebin.db');
        $query = $this->db->prepare(file_get_contents(__DIR__ . "/build_db.sql"));
        $query->execute();

        return;
    }

    public function onPageInitialized()
    {
        $page = $this->grav['page'];

        if(!$page) {
            return;
        }

        // page merging should be done here

        $page = new Page;
        $page->init(new \SplFileInfo(__DIR__ . "/pages/new_paste.md"));
        $page->slug(basename($this->grav['uri']->path()));
        unset($this->grav['page']);
        $this->grav['page'] = $page;
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
        }
    }

    public function onFormProcessed(Event $event)
    {
        $this->grav['debugger']->addMessage('Processing form!');
    }

    public function newPaste() 
    {
        $this->grav['debugger']->addMessage('In New Paste controller');
        $this->grav['debugger']->addMessage($_POST);

        $query = "insert into pastes(title, author, raw) values(?,?,?)";
        $stmt  = $this->db->prepare($query);

        $stmt->bindParam(1, $_POST['title']);
        $stmt->bindParam(2, $_POST['author']);
        $stmt->bindParam(3, $_POST['raw']);
        $stmt->execute();

        $this->grav->redirect('/', 302);
    }
}
