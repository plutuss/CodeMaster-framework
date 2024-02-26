<?php

namespace Plutuss\CodeMaster\Builder;



use Plutuss\CodeMaster\Model\Model;

interface QueryBuilderInterface
{
    public function insert(array $data): int|false;

    public function find(int $id): Model;
    public function first(): Model;

    public function get(): array;

    public function delete(): void;

    public function update(array $data): void;

    public function where(array $where): self;

    public function select(string ...$select): self;

    public function limit(int $limit): self;

    public function orderBy(string ...$order): self;

    public function innerJoin(string ...$join): self;

    public function leftJoin(string ...$join): self;

    public function from(string $table, ?string $alias = null): self;

    public function setTable(string $table): static;
}