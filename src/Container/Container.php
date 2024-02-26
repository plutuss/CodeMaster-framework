<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\Container;


use Plutuss\CodeMaster\Auth\Auth;
use Plutuss\CodeMaster\Builder\QueryBuilder;
use Plutuss\CodeMaster\Config\Config;
use Plutuss\CodeMaster\Database\Database;
use Plutuss\CodeMaster\Http\Redirect\Redirect;
use Plutuss\CodeMaster\Http\Request\Request;
use Plutuss\CodeMaster\Model\Model;
use Plutuss\CodeMaster\Routing\Router;
use Plutuss\CodeMaster\Session\Session;
use Plutuss\CodeMaster\Storage\Storage;
use Plutuss\CodeMaster\Validator\Validator;
use Plutuss\CodeMaster\View\View;
use Plutuss\CodeMaster\View\ViewBladeDirective;
use RyanChandler\Blade\Blade;
use Symfony\Component\Dotenv\Dotenv;

readonly class Container
{

    public Router $router;
    public Request $request;
    public View $view;
    public Validator $validator;
    public Redirect $redirect;
    public Session $session;
    public Config $config;
    public Database $database;
    public Auth $auth;
    public Storage $storage;
    public Model $model;
    public QueryBuilder $query;
    public Dotenv $dotenv;
    public Blade $blade;
    public ViewBladeDirective $viewBladeDirective;

    public function __construct()
    {
        $this->registerServices();
    }

    /**
     * @return void
     */
    private function registerServices(): void
    {
        $this->config = new Config();
        $this->configureEnvironment();
        $this->setBlade();
        $this->request = Request::createFromGlobals();
        $this->session = new Session();
        $this->redirect = new Redirect($this->request);
        $this->validator = new Validator();
        $this->request->setValidator($this->validator);
        $this->request->setSession($this->session);
        $this->viewBladeDirective = new ViewBladeDirective($this->blade, $this->config);
        $this->view = new View($this->session, $this->blade, $this->viewBladeDirective);
        $this->database = new Database($this->config);
        $this->auth = new Auth($this->session, $this->config);
        $this->storage = new Storage($this->config);
        $this->query = new QueryBuilder($this->config);
        $this->model = new Model($this->query);
        $this->router = new Router(
            $this->view,
            $this->request,
            $this->redirect,
            $this->session,
            $this->config,
            $this->auth
        );


    }

    private function configureEnvironment(): void
    {
        $path = root_dir() . '/../.env';
        if (!file_exists($path)) {
            die('Not found .env');
        }
        $this->dotenv = new Dotenv();
        $this->dotenv->loadEnv($path);
    }


    private function setBlade(): void
    {
        $views = $this->config->get('view.path_view');
        $cache = $this->config->get('view.path_cache');
        $this->blade = new Blade(root_dir() . "/../" . $views, root_dir() . "/../" . $cache);
    }
}