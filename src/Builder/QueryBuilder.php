<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Builder;


use Plutuss\SauceCore\Database\Database;
use Plutuss\SauceCore\Exceptions\NotFoundException;
use Plutuss\SauceCore\Model\Model;

class QueryBuilder extends Database implements QueryBuilderInterface
{

    /**
     * @var array<string>
     */
    private array $fields = [];


    private array $conditions = [];

    /**
     * @var array<string>
     */
    private array $order = [];

    /**
     * @var array<string>
     */
    private array $from = [];

    /**
     * @var array<string>
     */
    private array $innerJoin = [];

    /**
     * @var array<string>
     */
    private array $leftJoin = [];

    /**
     * @var int|null
     */
    private ?int $limit;

    private string $table;


    /**
     * @param string $table
     * @return $this
     */
    public function setTable(string $table): static
    {
        $this->table = $table;
        return $this;
    }


    /**
     * @param array $data
     * @return int|false
     */
    public function insert(array $data): int|false
    {
        $fields = array_keys($data);

        $columns = implode(', ', $fields);
        $binds = implode(', ', array_map(fn($field) => ":$field", $fields));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($binds)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($data);
        } catch (\PDOException $exception) {

            return false;
        }

        return (int)$this->pdo->lastInsertId();

    }

    /**
     * @param int $id
     * @return Model
     * @throws NotFoundException
     */
    public function find(int $id): Model
    {
        $where = '';
        $conditions = ['id' => $id];

        if ($id) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($conditions)));
        }
        $sql = "SELECT * FROM $this->table $where LIMIT 1";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($conditions);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            throw new NotFoundException('No element in database');
        }

        return Model::getInstance()->setData($result);
    }


    /**
     * @return Model
     * @throws NotFoundException
     */
    public function first(): Model
    {
        $where = '';

        if ($this->conditions) {
            $where = 'WHERE ' . implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($this->conditions)));
        }
        $sql = "SELECT * FROM $this->table $where LIMIT 1";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($this->conditions);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            throw new NotFoundException('No element in database');
        }

        return Model::getInstance()->setData($result);
    }

    /**
     * @return Model[]
     */
    public function get(): array
    {
        if (!empty($this->from)) {
            $this->table = implode(', ', $this->from);
        }
        if (empty($this->fields)) $this->fields = ['*'];

        $query = 'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . $this->table
            . (empty($this->leftJoin)
                ? ''
                : ' LEFT JOIN ' . implode(
                    ' LEFT JOIN ', $this->leftJoin
                ))
            . (empty($this->innerJoin)
                ? ''
                : ' INNER JOIN ' . implode(
                    ' INNER JOIN ', $this->innerJoin
                ))
            . (empty($this->conditions)
                ? ''
                : ' WHERE ' . implode(
                    ' AND ', array_map(fn($field) => "$field = :$field", array_keys($this->conditions))
                ))
            . (empty($this->order)
                ? ''
                : ' ORDER BY ' . implode(
                    ', ', $this->order
                ))
            . (empty($this->limit) ? '' : ' LIMIT ' . $this->limit);

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($this->conditions);

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($data as $key => $item) {
            $data[$key] = Model::getInstance()->setData($item);
        }
        return $data;
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $sql = 'DELETE FROM ' . $this->table . ($this->conditions === [] ? ''
                : ' WHERE ' . implode(' AND ', $this->conditions));

        $statement = $this->pdo->prepare($sql);

        $statement->execute($this->conditions);

        $statement->closeCursor();
    }

    /**
     * @param array $data
     * @return void
     */
    public function update(array $data): void
    {
        $fields = array_keys($data);

        $set = implode(', ', array_map(fn($field) => "$field = :$field", $fields));
        $where = '';
        if (count($this->conditions) > 0) {

            $where = implode(' AND ', array_map(fn($field) => "$field = :$field", array_keys($this->conditions)));
        }

        $sql = "UPDATE $this->table SET $set WHERE $where";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(array_merge($data, $this->conditions));
        $stmt->closeCursor();
    }

    /**
     * @param array $where
     * @return $this
     */
    public function where(array $where): self
    {
        $this->conditions = $where;
        return $this;
    }

    /**
     * @param string ...$select
     * @return $this
     */
    public function select(string ...$select): self
    {
        foreach ($select as $arg) {
            $this->fields[] = $arg;
        }
        return $this;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param string ...$order
     *
     * @return $this
     */
    public function orderBy(string ...$order): self
    {
        foreach ($order as $arg) {
            $this->order[] = $arg;
        }
        return $this;
    }

    /**
     * @param string ...$join
     *
     * @return $this
     */
    public function innerJoin(string ...$join): self
    {
        $this->leftJoin = [];
        foreach ($join as $arg) {
            $this->innerJoin[] = $arg;
        }
        return $this;
    }

    /**
     * @param string ...$join
     *
     * @return $this
     */
    public function leftJoin(string ...$join): self
    {
        $this->innerJoin = [];
        foreach ($join as $arg) {
            $this->leftJoin[] = $arg;
        }
        return $this;
    }

    /**
     * @param string $table
     * @param string|null $alias
     * @return $this
     */
    public function from(string $table, ?string $alias = null): self
    {
        $this->from[] = $alias === null ? $table : "${table} AS ${alias}";
        return $this;
    }
}