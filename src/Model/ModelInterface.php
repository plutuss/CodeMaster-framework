<?php

namespace  Plutuss\CodeMaster\Model;

use Plutuss\CodeMaster\Builder\QueryBuilderInterface;

interface ModelInterface
{
    public static function query(): QueryBuilderInterface;

    public function setData(array $data = []): static;

}