<?php

namespace App\model;

use Exception;
use Lib\Factory;
use PDO;
use PDOException;

/**
 * Class Dao
 */
class Dao extends QueryBuilder
{
    protected $logger;
    /**
     * @var PDO $_db DB
     */
    private $_db;
    /**
     * @var string|null
     */
    private $_connection_string = null;
    /**
     * @var string|null
     */
    private $_db_path = null;
    /**
     * @var string
     */
    private $_db_type;
    /**
     * @var string
     */
    private $_db_host;
    /**
     * @var string
     */
    private $_db_user;
    /**
     * @var string
     */
    private $_db_pass;
    /**
     * @var string
     */
    private $_db_name;
    /**
     * @var bool
     */
    private $_con = false;
    /**
     * @var bool|int
     */
    private $numResults = false;
    /**
     * @var array
     */
    private $result = false;

    /**
     *
     * @param string $db_user Username
     * @param string $db_pass Password
     * @param string $db_name Collection Name
     * @param string $db_type Type
     * @param string $db_path Path
     * @param string $db_host Host
     * @throws Exception
     */
    public function __construct(string $db_host, string $db_user, string $db_pass, string $db_name, string $db_type = 'mysql', string $db_path = null)
    {
        $this->logger = Factory::errorFactory('Dao');
        $this->_db_host = $db_host;
        $this->_db_user = $db_user;
        $this->_db_pass = $db_pass;
        $this->_db_name = $db_name;
        $this->_db_path = $db_path;
        $this->_db_type = $db_type;

        switch ($this->_db_type) {
            case "mysql":
                $this->_connection_string = sprintf(
                /** @lang text */
                    'mysql:host=%s;dbname=%s',
                    $db_host,
                    $db_name
                );
                break;
            case "sqlite":
                $this->_connection_string = sprintf(
                /** @lang text */
                    'sqlite:%s',
                    $db_path
                );
                break;
            case "oracle":
                $this->_connection_string = sprintf(
                /** @lang text */
                    'OCI:dbname=%s;charset=UTF-8',
                    $db_name
                );
                break;
            case "dblib":
                $this->_connection_string = sprintf(
                /** @lang text */
                    'dblib:host=%s;dbname=%s',
                    $db_host,
                    $db_name
                );
                break;
            case "postgresql":
                $this->_connection_string = sprintf(
                /** @lang text */
                    'pgsql:host=%s dbname=%s',
                    $db_host,
                    $db_name
                );
                break;
            case "sqlsrv":
                $this->_connection_string = sprintf(
                /** @lang text */
                    'sqlsrv:Server=%s;Database=%s',
                    $db_host,
                    $db_name
                );
                break;
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * disconnect ($con==false)
     *
     * @return bool
     */
    public function disconnect(): bool
    {
        if ($this->_con) {
            unset($this->_db);
            $this->_con = false;
            return true;
        }
        return !$this->_con;
    }

    /**
     * @return PDO
     */
    public function getDB(): PDO
    {
        return $this->_db;
    }

    /**
     * Connect
     *
     * @return bool True ou False
     */
    public function connect(): bool
    {
        if (!$this->_con) {
            try {
                $this->_db = new PDO($this->_connection_string, $this->_db_user, $this->_db_pass);

                $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->_con = true;
                return $this->_con;
            } catch (PDOException $e) {
                $this->logger->critical($e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(), [$this->_connection_string, $this->_db_user, $this->_db_pass, $this->_db]);
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Select
     *
     * @param string $table tabela
     * @param string $columns colunas
     * @param string|array $join Junção
     * @param array $where Condicional
     * @param string $group
     * @param string $order Ordenação
     * @param integer|string $limit limit
     *
     * @param bool $bind
     * @return bool
     */
    public function select(string $table, string $columns = "*", $join = null, $where = null, string $group = null, string $order = null, $limit = null, bool $bind = true): bool
    {
        $this->numResults = null;
        try {
            $sql = self::querySelect($this->_db, $table, $columns, $join, $where, $group, $order, $limit, $bind);
            $sql->execute();
            $this->result = $sql->fetchAll(PDO::FETCH_ASSOC);
            $this->numResults = count($this->result);
            if ($this->numResults === 0) {
                $this->numResults = null;
            }
            return true;
        } catch (PDOException $e) {
            $this->logger->register(400, $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(), [$this->_db, $table, $columns, $join, $where, $group, $order, $limit, $bind]);
            return false;
        }
    }

    /**
     * Query result
     * select ou insert??'';
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Number of lines by query affected
     * @return int
     */
    public function getNumResults(): int
    {
        return $this->numResults;
    }

    /**
     * Update
     * update("$table","$values","$rows = null")
     *
     * @param string $table
     * @param array $where
     * @param string|array $value
     * @return bool|array $result OU FALSE
     */
    public function update(string $table, array $where, $value): bool
    {
        if ($this->tableExists($table)) {
            $q = self::queryUpdate($table, $value, $where);
            $this->numResults = null;
            //exit($q);
            try {
                return $this->_db->prepare($q)->execute();
            } catch (PDOException $e) {
                $this->logger->register(500, $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(), [$q, $table, $value, $where]);
                return false;
            }
        }
        return false;
    }

    /**
     * @param $table
     * @return bool|string
     */
    private function tableExists(string $table): bool
    {
        $this->numResults = null;
        try {
            $sql = self::querySelect(
                $this->_db,
                'information_schema.tables',
                'count( table_name) as ok',
                null,
                "table_schema = '$this->_db_name' and TABLE_NAME = '$table'",
                null,
                null,
                '1'
            );
            $sql->execute();
            $this->result = $sql->fetchAll(PDO::FETCH_OBJ);
            $this->numResults = count($this->result);
            if ($this->numResults === 0) {
                $this->numResults = null;
            }
            return true;
        } catch (PDOException $e) {
            $this->logger->register(400,
                $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(),
                [
                    $this->_db,
                    'information_schema.tables',
                    'count( table_name) as ok',
                    null,
                    "table_schema = '$this->_db_name' and TABLE_NAME = '$table'",
                    null,
                    null,
                    '1'
                ]
            );
            return false;
        }
    }

    /**
     * Insert
     * @param string $table
     * @param $fieldsAndValues
     * @return mixed True ou getMessage()
     */
    public function insert($table, $fieldsAndValues)
    {
        $insert = self::queryInsert($table, $fieldsAndValues);
        try {
            return $this->_db->prepare($insert)->execute();
        } catch (PDOException $e) {
            $this->logger->register(500, $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(), [$insert, $table, $fieldsAndValues]);
            return false;
        }
    }

    /**
     * Delete
     * @param string $table
     * @param $where array
     * @return mixed True ou getMessage()
     */
    public function delete($table, $where)
    {
        $deleteQ = self::keyAndValue(
            sprintf(
            /**@lang text */
                'DELETE FROM %s WHERE ',
                $table
            ),
            $where,
            'and',
            true
        );
        try {
            return $this->_db->prepare($deleteQ)->execute();
        } catch (PDOException $e) {
            $this->logger->register(400,
                $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL . $e->getCode() . PHP_EOL . $e->getLine(),
                [
                    $deleteQ,
                    sprintf(/**@lang text */ 'DELETE FROM %s WHERE ', $table),
                    $where
                ]
            );
            return false;
        }
    }
}
