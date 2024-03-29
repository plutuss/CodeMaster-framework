<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\View;

use Plutuss\CodeMaster\Config\ConfigInterface;
use RyanChandler\Blade\Blade;
use App\View\Directive;

class ViewBladeDirective implements ViewBladeDirectiveInterface
{

    public function __construct(
        private Blade           $blade,
        private ConfigInterface $config,
    )
    {
    }


    private function getAllDirectiveFromArray(): array
    {
        return $this->config->get('view.directive');
    }

    public function handler(): void
    {
        $directive = $this->getAllDirectiveFromArray();

        if (!empty($directive)) {
            foreach ($directive as $class) {
                call_user_func([new $class($this->blade, $this->config), 'handler']);
            }
        }


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