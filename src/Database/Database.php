<?php

declare(strict_types=1);


namespace Plutuss\SauceCore\Database;




use Plutuss\SauceCore\Config\ConfigInterface;

class Database implements DatabaseInterface
{
    protected \PDO $pdo;

    public function __construct(
        private ConfigInterface $config
    )
    {
        $this->connect();
    }

    /**
     * @return void
     */
    private function connect()
    {
        $driver = $this->config->get('database.driver');
        $host = $this->config->get('database.host');
        $port = $this->config->get('database.port');
        $database = $this->config->get('database.database');
        $username = $this->config->get('database.username');
        $password = $this->config->get('database.password');
        $charset = $this->config->get('database.charset');

        try {
            $this->pdo = new \PDO("$driver:host=$host;port=$port;dbname=$database;charset=$charset",
                $username,
                $password
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            exit("Database connection failed: " . $e->getMessage());
        }
    }

}