<?php

declare(strict_types=1);

namespace Plutuss\SauceCore\Http\Controllers;

use Plutuss\SauceCore\Auth\AuthInterface;
use Plutuss\SauceCore\Config\ConfigInterface;
use Plutuss\SauceCore\Database\DatabaseInterface;
use Plutuss\SauceCore\Http\Redirect\RedirectInterface;
use Plutuss\SauceCore\Http\Request\RequestInterface;
use Plutuss\SauceCore\Session\SessionInterface;
use Plutuss\SauceCore\View\ViewInterface;

abstract class BaseController
{

    private ViewInterface $view;
    private RequestInterface $request;
    private RedirectInterface $redirect;
    private SessionInterface $session;
    private ConfigInterface $config;
    private AuthInterface $auth;
    private DatabaseInterface $db;

    public function auth(): AuthInterface
    {
        return $this->auth;
    }

    public function setAuth(AuthInterface $auth): void
    {
        $this->auth = $auth;
    }

    public function config(): ConfigInterface
    {
        return $this->config;
    }

    public function setConfig(ConfigInterface $config): void
    {
        $this->config = $config;
    }

    public function db(): DatabaseInterface
    {
        return $this->db;
    }

    public function setDb(DatabaseInterface $db): void
    {
        $this->db = $db;
    }


    public function session(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function setRedirect(RedirectInterface $redirect): void
    {
        $this->redirect = $redirect;
    }

    public function redirect(): RedirectInterface
    {
        return $this->redirect;
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function view(string $name, array $data = []): void
    {
        $this->view->page($name, $data);
    }

    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

}