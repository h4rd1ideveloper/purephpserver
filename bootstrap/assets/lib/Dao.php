<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
define("DB_type", "mysql");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "crefazscm_webscm");
require_once './QueryBuilder.php';
class Dao extends QueryBuilder
{
    public function __destruct()
    {
       self::disconnect();
    }

    /**
     * @var \PDO $_db DB
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
    private $_db_type = DB_type;
    /**
     * @var string
     */
    private $_db_host = DB_HOST;
    /**
     * @var string
     */
    private $_db_user = DB_USER;
    /**
     * @var string
     */
    private $_db_pass = DB_PASS;
    /**
     * @var string
     */
    private $_db_name = DB_NAME;
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
     *
     * @return Dao Instancia
     */
    public function __construct($db_user = DB_USER, $db_pass = DB_PASS, $db_name = DB_NAME, $db_type = DB_type, $db_path = null, $db_host = DB_HOST)
    {
        $this->_db_host = $db_host;
        $this->_db_user = $db_user;
        $this->_db_pass = $db_pass;
        $this->_db_name = $db_name;
        $this->_db_path = $db_path;
        $this->_db_type = $db_type;

        switch ($this->_db_type) {
            case "mysql":
                $this->_connection_string = sprintf(/** @lang text */ "mysql:host=%s;dbname=%s", $db_host, $db_name);
                break;
            case "sqlite":
                $this->_connection_string = sprintf(/** @lang text */ "sqlite:%s", $db_path);
                break;
            case "oracle":
                $this->_connection_string = sprintf(/** @lang text */ "OCI:dbname=%s;charset=UTF-8", $db_name);
                break;
            case "dblib":
                $this->_connection_string = sprintf(/** @lang text */ "dblib:host=%s;dbname=%s", $db_host, $db_name);
                break;
            case "postgresql":
                $this->_connection_string = sprintf(/** @lang text */ "pgsql:host=%s dbname=%s", $db_host, $db_name);
                break;
            case "sqlsrv":
                $this->_connection_string = sprintf(/** @lang text */ "sqlsrv:Server=%s;Database=%s", $db_host, $db_name);
                break;
        }
        return $this;
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
                return $e->getMessage();
            }
        } else {
            return true;
        }
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
     * Select
     *
     * @param string $table tabela
     * @param string $columns colunas
     * @param string $join Junção
     * @param string $where Condicional
     * @param string $order Ordenação
     * @param integer $limit limit
     *
     * @return mixed True ou getMessage()
     */
    public function select($table, $columns = "*", $join = null, $where = null, $order = null, $limit = null)
    {
        $q = parent::querySelect($table, $columns, $join, $where, $order, $limit);
        $this->numResults = null;
        try {
            $sql = $this->_db->prepare($q);
            $sql->execute();
            $this->result = $sql->fetchAll(PDO::FETCH_ASSOC);
            $this->numResults = count($this->result);
            if ($this->numResults === 0) {
                $this->numResults = null;
            }
            return true;
        } catch (PDOException $e) {
            return $e->getMessage() . '' . $e->getTraceAsString() . '';
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
            $q = parent::queryUpdate($table, $value, $where);
            $this->numResults = null;
            try {
                $sql = $this->_db->prepare($q);
                $sql->execute();
                $this->result = $sql->fetchAll(PDO::FETCH_ASSOC);
                $this->numResults = count($this->result);
                $this->numResults === 0 ? $this->result = null : true;
                return true;
            } catch (PDOException $e) {
                return $e->getMessage() . '' . $e->getTraceAsString() . '';
            }
        }
        return false;
    }

    /**
     * @param $table
     * @return bool|string
     */
    private function tableExists($table)
    {
        $q = parent::querySelect($table);
        $this->numResults = null;
        try {
            $sql = $this->_db->prepare($q);
            $sql->execute();
            $this->result = $sql->fetchAll(PDO::FETCH_OBJ);
            $this->numResults = count($this->result);
            if ($this->numResults === 0) {
                $this->numResults = null;
            }
            return true;
        } catch (PDOException $e) {
            return sprintf("%s%s", $e->getMessage(), $e->getTraceAsString());
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
        $insert = parent::queryInsert($table, $fieldsAndValues);
        try {
            $ins = $this->_db->prepare($insert);
            $ins->execute();
            return true;
        } catch (PDOException $e) {
            return $e->getMessage() . '' . $e->getTraceAsString() . '';
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
        $deleteQ = parent::keyAndValue(
            sprintf(
            /**@lang text */
                'DELETE FROM %s',
                $table
            ),
            $where,
            'and',
            true
        );
        try {
            $del = $this->_db->prepare($deleteQ);
            $del->execute();
            return true;
        } catch (PDOException $e) {
            return $e->getMessage() . '' . $e->getTraceAsString() . '';
        }
    }
}
