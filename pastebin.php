<?php
namespace Grav\Plugin;

use \PDO;

use Grav\Common\Page\Page;
use Grav\Common\Page\Pages;
// use Grav\Common\User\User;
use Grav\Plugin\Login\Login;
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
            'onPluginsInitialized' => ['onPluginsInitialized', 1],
            'onTwigTemplatePaths'  => ['onTwigTemplatePaths', 0],
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
        $len = strlen($this->config->get('plugins.pastebin.route_view'));

        if ( 
            $uri->path() == $this->config->get('plugins.pastebin.route_new') 
            or $uri->path() == $this->config->get('plugins.pastebin.route_list')
            or substr($uri->path(), 0, $len) == $this->config->get('plugins.pastebin.route_view')
        ) {
            $this->enable([
                'onPageInitialized'    => ['onPageInitialized', 1]
            ]);
        }

        return;
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
        $query = $this->db->exec(file_get_contents(__DIR__ . "/build_db.sql"));
        // $query->execute();

        return;
    }

    public function onPageInitialized()
    {
        $uri  = $this->grav['uri'];
        $page = $this->grav['page'];

        if(!$page) {
            return;
        }

        // page merging should be done here

        $page = new Page;
        $len  = strlen($this->config->get('plugins.pastebin.route_view'));

        if( $uri->path() == $this->config->get('plugins.pastebin.route_new') ) {
            $page->init(new \SplFileInfo(__DIR__ . "/pages/new_paste.md"));
        }

        else if ( $uri->path() == $this->config->get('plugins.pastebin.route_list') ) {
            $page->init(new \SplFileInfo(__DIR__ . "/pages/pastebin.md"));
            
            $this->getPastes();
        }

        else if( substr($uri->path(), 0, $len) == $this->config->get('plugins.pastebin.route_view') ) {
            $page->init(new \SplFileInfo(__DIR__ . "/pages/paste.md"));

            $assets      = $this->grav['assets'];
            $prism_stuff = ['plugin://pastebin/js/prism.js', 'plugin://pastebin/css/prism.css'];
            $assets->registerCollection('prism', $prism_stuff);
            $assets->add('prism', 100);
            
            $uuid = substr($uri->path(), $len+1);
            $this->getPaste($uuid);
            $this->recordPasteView($uuid);
        }
        
        $page->slug(basename($uri->path()));
        unset($this->grav['page']);
        $this->grav['page'] = $page;
    }

    public function onTwigTemplatePaths()
    {
        $twig = $this->grav['twig'];
        $twig->twig_paths[] = __DIR__ . '/templates';
    }

    public function getPastes()
    {
        $this->grav['debugger']->addMessage('Getting pastes');

        $ret   = array();
        $query = "select pastes.uuid, pastes.title, pastes.author, pastes.lang, pastes.created, pastes.raw, count(*) as views from pastes join views on pastes.uuid = views.uuid group by pastes.uuid, pastes.title, pastes.author, pastes.lang, pastes.created, pastes.raw order by pastes.created desc";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->grav['twig']->twig_vars['pastes'] = $ret;
        $this->grav['debugger']->addMessage('Finished getting pastes');
    }

    public function getPaste($paste_uuid)
    {
        $this->grav['debugger']->addMessage('Getting paste: ' . $paste_uuid);

        $query = "select uuid, title, created, author, lang, raw, (select count(*) from views where views.uuid = pastes.uuid) as views from pastes where uuid = ?;";
        $stmt  = $this->db->prepare($query);

        $stmt->bindParam(1, $paste_uuid);
        $stmt->execute();
        
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->grav['twig']->twig_vars['paste'] = $ret;
    }

    public function recordPasteView($uuid)
    {
        $query = "insert into views(ip, uuid) values(?,?)";
        $stmt  = $this->db->prepare($query);

        $stmt->bindParam(1, $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(2, $uuid);
        $stmt->execute();
    }
    
    public function newPaste() 
    {
        $this->grav['debugger']->addMessage($_POST);
        $query = "insert into pastes(uuid, title, author, lang, raw) values(?,?,?,?,?)";
        $stmt  = $this->db->prepare($query);
        $uuid  = uniqid();

        $stmt->bindParam(1, $uuid);
        $stmt->bindParam(2, $_POST['title']);
        $stmt->bindParam(3, $_POST['author']);
        $stmt->bindParam(4, $_POST['lang']);
        $stmt->bindParam(5, $_POST['raw']);
        $stmt->execute();

        $this->grav->redirect('/pastebin/view/' . $uuid, 302);
    }
}
