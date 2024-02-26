<?php

declare(strict_types=1);

namespace Plutuss\CodeMaster\Http\Controllers;

use Plutuss\CodeMaster\Auth\AuthInterface;
use Plutuss\CodeMaster\Config\ConfigInterface;
use Plutuss\CodeMaster\Database\DatabaseInterface;
use Plutuss\CodeMaster\Http\Redirect\RedirectInterface;
use Plutuss\CodeMaster\Http\Request\RequestInterface;
use Plutuss\CodeMaster\Session\SessionInterface;
use Plutuss\CodeMaster\View\ViewInterface;

abstract class BaseController
{

    private ViewInterface $view;
    private RequestInterface $request;
    private RedirectInterface $redirect;
    private SessionInterface $session;
    private ConfigInterface $config;
    private AuthInterface $auth;
    private DatabaseInterface $db;

    /**
     * @return AuthInterface
     */
    public function auth(): AuthInterface
    {
        return $this->auth;
    }

    /**
     * @param AuthInterface $auth
     * @return void
     */
    public function setAuth(AuthInterface $auth): void
    {
        $this->auth = $auth;
    }

    /**
     * @return ConfigInterface
     */
    public function config(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * @param ConfigInterface $config
     * @return void
     */
    public function setConfig(ConfigInterface $config): void
    {
        $this->config = $config;
    }

    /**
     * @return DatabaseInterface
     */
    public function db(): DatabaseInterface
    {
        return $this->db;
    }

    /**
     * @param DatabaseInterface $db
     * @return void
     */
    public function setDb(DatabaseInterface $db): void
    {
        $this->db = $db;
    }

    /**
     * @return SessionInterface
     */
    public function session(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param SessionInterface $session
     * @return void
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * @param RedirectInterface $redirect
     * @return void
     */
    public function setRedirect(RedirectInterface $redirect): void
    {
        $this->redirect = $redirect;
    }

    /**
     * @return RedirectInterface
     */
    public function redirect(): RedirectInterface
    {
        return $this->redirect;
    }

    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     * @return void
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * @param string $name
     * @param array $data
     * @return void
     */
    public function view(string $name, array $data = []): void
    {
        $this->view->page($name, $data);
    }

    /**
     * @param ViewInterface $view
     * @return void
     */
    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

}