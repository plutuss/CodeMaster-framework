<?php

declare(strict_types=1);

namespace  Plutuss\SauceCore\Http\Redirect;

use Plutuss\SauceCore\Http\Request\RequestInterface;

class Redirect implements RedirectInterface
{


    private static RequestInterface $request;
    private static ?Redirect $instance = null;


    public function __construct(
        RequestInterface $request,
    )
    {
        self::$request = $request;
    }

    public static function getInstance(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(self::$request);
        }
        return static::$instance;
    }

    public function to(string $url)
    {
        header("Location: {$url}");
        exit();
    }

    public function back(): void
    {
        $url = self::$request->url();
        $this->to($url);
    }
}