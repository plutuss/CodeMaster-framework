<?php

declare(strict_types=1);

namespace  Plutuss\CodeMaster\Http\Redirect;

use Plutuss\CodeMaster\Http\Request\RequestInterface;

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

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(self::$request);
        }
        return static::$instance;
    }

    /**
     * @param string $url
     * @return void
     */
    public function to(string $url)
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * @return void
     */
    public function back(): void
    {
        $url = self::$request->url();
        $this->to($url);
    }
}