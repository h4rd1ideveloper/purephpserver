<?php
//srequire_once ('./vendor/autoload.php');
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
define("DB_type", "mysql");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "crefazscm_webscm");

class Dao
{
    private $_db;
    private $_connection_string = null;
    private $_db_path = null;
    private $_db_type = DB_type;
    private $_db_host = DB_HOST;
    private $_db_user = DB_USER;
    private $_db_pass = DB_PASS;
    private $_db_name = DB_NAME;
    private $_con = false;
    /**
     * Construtor
     * __construct e seus parametretros *opcionais
     * ("$db_user='root'","$db_pass='root'","$db_name='puro'","$db_type='mysql'","$db_path=''","$db_host='localhost'")
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
        switch($this->_db_type)
        {
            case "mysql":
                $this->_connection_string = sprintf("mysql:host=%s;dbname=%s", $db_host, $db_name);
                break;
            case "sqlite":
                $this->_connection_string = sprintf("sqlite:%s", $db_path);
                break;
            case "oracle":
                $this->_connection_string = sprintf("OCI:dbname=%s;charset=UTF-8", $db_name);
                break;
            case "dblib":
                $this->_connection_string = sprintf("dblib:host=%s;dbname=%s", $db_host, $db_name);
                break;
            case "postgresql":
                $this->_connection_string = sprintf("pgsql:host=%s dbname=%s", $db_host, $db_name);
                break;
            case "sqlsrv":
                $this->_connection_string = sprintf("sqlsrv:Server=%s;Database=%s", $db_host, $db_name);
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
                //echo sprintf("%s %s %s", $this->_connection_string, $this->_db_user, $this->_db_pass);
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
            unset($this->_db);$this->_con = false;
            return true;
        }
    }
    /**
     * @param $table
     * @return bool|string
     */
    private function tableExists($table) {

        $q = sprintf("SELECT * FROM %s", $table);
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
        }
        catch (PDOException $e) {
            return sprintf("%s%s", $e->getMessage(), $e->getTraceAsString());
        }
    }
    /**
     * Select
     *
     * @param string $table     tabela
     * @param string $columns   colunas
     * @param string $join      Junção
     * @param string $where     Condicional
     * @param string $order     Ordenação
     * @param integer $limit    limit
     *
     * @return mixed True ou getMessage()
     */
    public function select($table, $columns = "*", $join = null, $where = null, $order = null, $limit = null)
    {
        if ($this->tableExists($table)) {

            $q = sprintf("SELECT %s FROM %s ", $columns, $table);

            if($join != null){
                $q .= $join;
            }
            if ($where != null) {
                $q .= sprintf(" WHERE %s ", $where);
            }
            if ($order != null) {
                $q .= sprintf(" ORDER BY %s ", $order);
            }
            if($limit != null){
                $q .= sprintf(" limit %s ", (string) $limit);
            }
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
                return $e->getMessage().''.$e->getTraceAsString().'';
            }
        }
    }
    /**
     * Resultado da query
     * select ou insert??'';
     *
     * @return \vetor\ de associaçao
     */
    public function getResult()
    {
        return $this->result;
    }
    /**
     * Numero de Linhas
     * Afetadas pela Query Ou nº Resultados
     *
     * @return \vetor\ de associaçao
     */
    public function getNumResults()
    {
        return $this->numResults;
    }
    /**
     * Update
     * update("$table","$values","$rows = null")
     *
     * @param string $table  Tabela mysql
     * @param string $values Valores
     * @param string $rows   Linhas default NULL
     *
     * @return object $result OU FALSE
     */
    public function update($table, $rows = '*', $where = null, $value)
    {
        if ($this->tableExists($table)) {
            $q = 'SELECT '.$rows.' FROM '.$table;
            if ($where != null) {
                $q .= ' WHERE '.$where;
            }
            $this->numResults = null;
            try {
                $sql = $this->_db->prepare($q);
                $sql->execute();
                $this->result = $sql->fetchAll(PDO::FETCH_OBJ);
                $this->numResults = count($this->result);
                $this->numResults === 0 ? $this->result = null : true ;
                return true;
            } catch (PDOException $e) {
                return $e->getMessage().''.$e->getTraceAsString().'';
            }
        }
    }
    /**
     * Insert
     * insert("tabela", "valores", "linhas=NULL")
     *
     * @param string $table  Tabela_db
     * @param string|array $values Valores para inserção
     * @param string $rows   Linhas da inserção
     *
     * @return mixed True ou getMessage()
     */
    public function insert($table,$values,$rows = null)
    {
        $insert = 'INSERT INTO '.$table;
        if ($rows != null) {
            $insert .= sprintf("( %s )", $rows);// ' ('.$rows.')';
        }
        for ($i = 0; $i < count($values); $i++) {
            if (is_string($values[$i])) {
                $values[$i] = sprintf("'%s'", $values[$i]);  // '"'.$values[$i].'"';
            }
        }
        $values = implode(',', (array)$values);
        $insert .=sprintf("VALUES (%s)", $values);
        try {
            $ins = $this->_db->prepare($insert);
            $ins->execute();
            return true;
        }
        catch (PDOException $e) {
            return $e->getMessage().''.$e->getTraceAsString().'';
        };
    }
    /**
     * Deletar
     * delete("tabela", "valores", "linhas=NULL")
     * DELETE FROM `Users` WHERE `Users`.`id` = $id;
     *
     * @param string $table  Tabela_db
     * @param string $values Valores para inserção
     * @param string $rows   Linhas da inserção
     *
     * @return mixed True ou getMessage()
     */
    public function delete($table, $where)
    {
        $deletQ = 'DELETE FROM '.$table;
        $deletQ .= ' WHERE '.$table.'.id = '.$where;
        try {
            $del = $this->_db->prepare($deletQ);
            $del->execute();
            return true;
        }
        catch (PDOException $e) {
            return $e->getMessage().''.$e->getTraceAsString().'';
        }
    }
}
