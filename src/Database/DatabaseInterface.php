<?php

namespace Plutuss\SauceCore\Database;

interface DatabaseInterface
{


//    /**
//     * @param string $table
//     * @param array $data
//     * @return int|false
//     */
//    public function insert(string $table, array $data): int|false;
//
//    /**
//     * @param string $table
//     * @param array $conditions
//     * @return array|null
//     */
//    public function first(string $table, array $conditions = []): ?array;
//
//    /**
//     * @param string $table
//     * @param array $conditions
//     * @param array $order
//     * @param int $limit
//     * @return array
//     */
//    public function get(string $table, array $conditions = [], array $order = [], int $limit = -1): array;
//
//    /**
//     * @param string $table
//     * @param array $conditions
//     * @return void
//     */
//    public function delete(string $table, array $conditions = []): void;
//
//    /**
//     * @param string $table
//     * @param array $data
//     * @param array $conditions
//     * @return void
//     */
//    public function update(string $table, array $data, array $conditions = []): void;
//
//    /**
//     * @param string $table
//     * @param string $join
//     * @param array $joinColumn
//     * @param array $select
//     * @return false|array
//     */
//    public function joinInner(string $table, string $join, array $joinColumn = [], array $select = ['*']): false|array;
}