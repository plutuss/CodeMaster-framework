<?php

declare(strict_types=1);


namespace  Plutuss\SauceCore\View;

use RyanChandler\Blade\Blade;
use App\View\Directive;
class ViewBladeDirective implements ViewBladeDirectiveInterface
{

    public function __construct(
        private Blade $blade,
    )
    {
    }

    public function handler()
    {
        (new Directive($this->blade))->handler();
    }


    /**
     * @param string $name
     * @param callable $callback
     * @return void
     */
    protected function directive(string $name, callable $callback): void
    {
        $this->blade->directive($name, fn($expression) => $callback($expression));
    }

    protected function directiveClose(string $name, callable $callback): void
    {
        $this->blade->directive($name, fn() => $callback());
    }

}