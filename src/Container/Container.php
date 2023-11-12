<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Container;




use Plutuss\SauceCore\Auth\Auth;
use Plutuss\SauceCore\Builder\QueryBuilder;
use Plutuss\SauceCore\Config\Config;
use Plutuss\SauceCore\Database\Database;
use Plutuss\SauceCore\Http\Redirect\Redirect;
use Plutuss\SauceCore\Http\Request\Request;
use Plutuss\SauceCore\Model\Model;
use Plutuss\SauceCore\Routing\Router;
use Plutuss\SauceCore\Session\Session;
use Plutuss\SauceCore\Storage\Storage;
use Plutuss\SauceCore\Validator\Validator;
use Plutuss\SauceCore\View\View;
use Plutuss\SauceCore\View\ViewBladeDirective;
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
        $this->viewBladeDirective = new ViewBladeDirective($this->blade);
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
        $this->dotenv = new Dotenv();
        $this->dotenv->loadEnv(APP_DIR . '/../.env');
    }


    private function setBlade(): void
    {
        $views = $this->config->get('view.path_view');
        $cache = $this->config->get('view.path_cache');
        $this->blade = new Blade(APP_DIR . "/../" . $views, APP_DIR . "/../" . $cache);
    }
}