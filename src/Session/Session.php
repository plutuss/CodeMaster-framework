<?php

namespace Plutuss\SauceCore\Session;

class Session implements SessionInterface
{

    private int|float $timeout;
    private ?array $session;


    public function __construct()
    {


        if (session_status() !== 1) {
            session_start();
        }
        if (!empty($_SESSION)) {
            $this->session = $_SESSION;
        }

    }


    /**
     * @param string $key
     * @param $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed|null
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->session[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function getFlash(string $key, $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->remove($key);

        return $value;
    }


    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->session[$key]);
    }

    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->session[$key]);
    }

    /**
     * @return void
     */
    public function destroy(): void
    {
        session_destroy();
    }

    /**
     * @return float|int
     */
    public function getTimeout(): float|int
    {

        $this->timeout = config('session.timeout');

        return (int)$this->timeout;
    }


    /**
     * @param int $timeout
     * @return void
     */
    public function sessionCheckTimeout(int $timeout = 0): void
    {

        $timeout = $timeout > 0 ? $timeout : $this->getTimeout();

        if ($this->has('timeout')) {
            $duration = time() - (int)$this->get('timeout');
            if ($duration > $timeout) {
                session_destroy();
            }
        }

        $this->set('timeout', time());

    }
}
