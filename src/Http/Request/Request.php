<?php

declare(strict_types=1);


namespace  Plutuss\SauceCore\Http\Request;

use Plutuss\SauceCore\Session\SessionInterface;
use Plutuss\SauceCore\Validator\ValidatorInterface;

class Request implements RequestInterface
{

    private ValidatorInterface $validator;
    private SessionInterface $session;


    public function __construct(
        public readonly array $get,
        public readonly array $post,
        public readonly array $server,
        public readonly array $files,
        public readonly array $cookies,
    )
    {
    }

    /**
     * @return $this
     */
    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_SERVER,
            $_FILES,
            $_COOKIE,
        );
    }


    /**
     * @return false|string
     */
    public function url(): false|string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    /**
     * @return mixed
     */
    public function method(): mixed
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function input(string $key, $default = null): mixed
    {

        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }



    public function file(string $key): ?\Plutuss\SauceCore\Upload\UploadedFileInterface
    {
        if (!isset($this->files[$key])) {
            return null;
        }

        return new UploadedFile(
            $this->files[$key]['name'],
            $this->files[$key]['type'],
            $this->files[$key]['tmp_name'],
            $this->files[$key]['error'],
            $this->files[$key]['size'],
        );
    }

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
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
     * @param array $rules
     * @return bool
     */
    public function validator(array $rules): bool
    {
        $data = [];

        foreach ($rules as $key => $rule) {
            $data[$key] = $this->input($key);
        }

        return $this->validator->validate($data, $rules);
    }

    /**
     * @return array
     */
    public function validatedData(): array
    {
        $data = $this->validator->validatedData();

        if (array_key_exists('password_confirmation', $data)) {
            unset($data['password_confirmation']);
        }
        return $data;
    }


    /**
     * @return array
     */
    public function errors(): array
    {
        $errors = $this->validator->errors();
        $this->setErrorRequest($errors);
        return $errors;
    }

    /**
     * @param $errors
     * @return void
     */
    private function setErrorRequest($errors): void
    {
        foreach ($errors as $key => $error) {
            $this->session->set($key, $error);
        }

    }


}