<?php

namespace Plutuss\CodeMaster\View;

interface ViewInterface
{
    public function page(string $name, array $data = []);

    public function components(string $name, array $data = []);

}