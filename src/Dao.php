<?php

namespace App;
use PDO;
/**
 * Class Dao
 */
class Dao extends QueryBuilder
{
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
     * @var array|object|bool
     */
    private $result = false;

    /**
     * Construtor
     * __construct e seus parametretros *opcionais
     *
     * @param string $db_user Usuario
     * @param string $db_pass Senha
     * @param string $db_name Nome
     * @param string $db_type Tipo
     * @param string $db_path Path
     * @param string $db_host Host
     */
    public function __construct($db_host, $db_user, $db_pass, $db_name, $db_type = 'mysql', $db_path = null)
    {
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
     * Disconecta ($con==false)
     *
     * @return bool
     */
    public function disconnect()
    {
        if ($this->_con) {
            unset($this->_db);
            $this->_con = false;
            return true;
        }
        return !$this->_con;
    }

    /**
     * Connect
     *
     * @return bool True ou False
     */
    public function connect()
    {
        if (!$this->_con) {
            try {
                $this->_db = new PDO($this->_connection_string, $this->_db_user, $this->_db_pass);

                $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->_con = true;
                return $this->_con;
            } catch (PDOException $e) {
                return $e->getMessage() . $e->getTraceAsString();
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
     * @param null $group
     * @param string $order Ordenação
     * @param integer $limit limit
     *
     * @param bool $bind
     * @return bool
     */
    public function select($table, $columns = "*", $join = null, $where = null, $group = null, $order = null, $limit = null, $bind = true)
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
            print_r($e->getMessage() . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Resultado da query
     * select ou insert??'';
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Numero de Linhas
     * Afetadas pela Query Ou nº Resultados
     *
     * @return int
     */
    public function getNumResults()
    {
        return $this->numResults;
    }

    /**
     * Update
     * update("$table","$values","$rows = null")
     *
     * @param string $table
     * @param $where
     * @param $value
     * @return bool|array $result OU FALSE
     */
    public function update($table, $where, $value)
    {
        if ($this->tableExists($table)) {
            $q = self::queryUpdate($table, $value, $where);
            $this->numResults = null;
            //exit($q);
            try {
                return $this->_db->prepare($q)->execute();
            } catch (PDOException $e) {
                return $e->getMessage() . $e->getTraceAsString();
            }
        }
        return false;
    }

    /**
     * @param $table
     * @return bool|string
     */
    public function tableExists($table)
    {
        $this->numResults = null;
        try {
            switch ($this->_db_type) {
                case 'mysql': {
                        $sql = self::querySelect(
                            $this->getDB(),
                            'information_schema.tables',
                            "if (count( table_name) = 0, 'false', 'true') as exist ",
                            null,
                            "table_schema = '$this->_db_name' and TABLE_NAME = '$table'",
                            null,
                            null,
                            '1'
                        );
                        break;
                    }
                case 'sqlite': {
                        $sql = self::querySelect(
                            $this->getDB(),
                            'sqlite_master',
                            'name',
                            null,
                            "type='table' AND name='$table'"
                        );
                        break;
                    }
                case 'oracle': {
                        $sql = self::querySelect(
                            self::getDB(),
                            'tab',
                            'tname',
                            null,
                            "tname = '$table'"
                        );
                        break;
                    }
                default: {
                        return false;
                    }
            }
            $sql->execute();
            $this->result = $sql->fetchAll(PDO::FETCH_ASSOC);
            $this->numResults = count($this->result) === 0 ?: null;
            return true;
        } catch (PDOException $e) {
            return sprintf('%s%s', $e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     * @return PDO
     */
    public function getDB()
    {
        return $this->_db;
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
            return $e->getMessage() . $e->getTraceAsString();
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
            return $e->getMessage() . $e->getTraceAsString();
        }
    }
}
