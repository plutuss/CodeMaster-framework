<?php

namespace  Plutuss\SauceCore\Model;

use Plutuss\SauceCore\Builder\QueryBuilderInterface;

interface ModelInterface
{
    public static function query(): QueryBuilderInterface;

    public function setData(array $data = []): static;

}