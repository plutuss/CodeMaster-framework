<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Auth;

use Plutuss\SauceCore\Config\ConfigInterface;
use Plutuss\SauceCore\Model\Model;
use Plutuss\SauceCore\Session\SessionInterface;

class Auth implements AuthInterface
{

    public function __construct(
        private SessionInterface $session,
        private ConfigInterface  $config

    )
    {
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function attempt(string $username, string $password): bool
    {
        $user = \App\Models\User::query()
            ->where([$this->username() => $username])
            ->first();

        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            $this->session->set('user_id', $user->id);
            return true;
        }
        return false;

    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->session->has(
            $this->sessionField()
        );
    }

    /**
     * @return Model
     */
    public function user(): \Plutuss\SauceCore\Model\Model
    {
        $id = $this->session->get(
            $this->sessionField()
        );
        return \App\Models\User::query()->find($id);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->session->remove($this->sessionField());
    }

    /**
     * @return string
     */
    private function table(): string
    {
        return $this->config->get('auth.table', 'users');
    }

    /**
     * @return string
     */
    private function username(): string
    {
        return $this->config->get('auth.username', 'email');
    }

    /**
     * @return string
     */
    private function password(): string
    {
        return $this->config->get('auth.password', 'password');
    }

    /**
     * @return string
     */
    private function sessionField(): string
    {
        return $this->config->get('auth.session_field', 'user_id');
    }

    /**
     * @return int|null
     */
    public function id(): ?int
    {
        return $this->session->get($this->sessionField());
    }
}