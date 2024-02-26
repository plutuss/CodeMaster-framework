<?php

declare(strict_types=1);

namespace Plutuss\CodeMaster\Model;

use Plutuss\CodeMaster\Builder\QueryBuilderInterface;


class Model implements ModelInterface
{

    protected static string|null $table;
    protected static QueryBuilderInterface $builder;
    protected array $data;
    protected static ?Model $instance = null;

    public function __construct(
        QueryBuilderInterface $builder
    )
    {
        self::$builder = $builder;
    }

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(self::$builder);
        }
        return static::$instance;
    }


    /**
     * @return QueryBuilderInterface
     */
    public static function query(): QueryBuilderInterface
    {

        return static::$builder->setTable(
            self::getInstance()
                ->getTable()
        );
    }

    /**
     * @return string|null
     */
    private function getTable(): ?string
    {
        return static::$table ?? strtolower(getPluralWort(class_basename(static::class), 2));
    }


    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data = []): static
    {
        $this->data = $data;
        return static::getInstance();
    }


    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->data)) {
            return null;
        }
        return $this->data[$name];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {

        return static::query()
            ->$name(...$arguments);
    }
}