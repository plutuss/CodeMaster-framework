<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Auth;

use Plutuss\SauceCore\Config\ConfigInterface;
use Plutuss\SauceCore\Session\SessionInterface;

readonly class Auth implements AuthInterface
{

    public function __construct(
        private SessionInterface $session,
        private ConfigInterface  $config

    )
    {
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function attempt(string $email, string $password): bool
    {
        $user = \App\Models\User::query()->where(['username' => $email])->first();

        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            $this->session->set('user_id', $user->id);
            return true;
        }
        return false;

    }

    public function check(): bool
    {
        return $this->session->has(
            $this->sessionField()
        );
    }

    public function user(): \Plutuss\SauceCore\Model\Model
    {
        $id = $this->session->get(
            $this->sessionField()
        );
        return \App\Models\User::query()->find($id);
    }

    public function logout(): void
    {
        $this->session->remove($this->sessionField());
    }

    public function table(): string
    {
        return $this->config->get('auth.table', 'users');
    }

    public function username(): string
    {
        return $this->config->get('auth.username', 'email');
    }

    public function password(): string
    {
        return $this->config->get('auth.password', 'password');
    }

    public function sessionField(): string
    {
        return $this->config->get('auth.session_field', 'user_id');
    }

    public function id(): ?int
    {
        return $this->session->get($this->sessionField());
    }
}