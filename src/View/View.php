<?php

declare(strict_types=1);


namespace Plutuss\CodeMaster\View;

use Plutuss\CodeMaster\Session\SessionInterface;
use RyanChandler\Blade\Blade;

readonly class View implements ViewInterface
{


    public function __construct(
        public SessionInterface             $session,
        private Blade                       $blade,
        private ViewBladeDirectiveInterface $bladeDirective,
    )
    {
        $bladeDirective->handler();
    }


    public function page(string $name, array $data = []): void
    {

        extract(
            $this->setExtract($data)
        );

        echo $this->blade
            ->make($name, $data)
            ->render();


//        dd($this->blade);

//        $name = str_replace('.', '/', $name);
//        $viewPath = APP_DIR . "/../resources/views/{$name}.php";
//        if (!file_exists($viewPath)) {
//            throw new ViewNotFountException("view {$name} not found");
//        }
//        extract(
//            $this->setExtract($data)
//        );
//        include $viewPath;

    }

    public function components(string $name, array $data = []): void
    {
        echo $this->blade->make($name, $data)->render();

//        $name = str_replace('.', '/', $name);
//        $componentPath = APP_DIR . "/../resources/views/components/{$name}.php";
//        if (!file_exists($componentPath)) {
//            echo "component $name not found";
//            return;
//        }
//
//        include $componentPath;

    }

    private function setExtract(array $data = []): array
    {

        return [
            'view' => $this,
            'session' => $this->session,
            ...$data
        ];
    }


}