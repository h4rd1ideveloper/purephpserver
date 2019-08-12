<?php


require_once __DIR__ . './../assets/lib/SimpleXLSX.php';
require_once __DIR__ . './../assets/lib/Helpers.php';
require_once __DIR__ . './../assets/lib/Dao.php';
class XLSXToHtmlParse extends Dao
{
    public function __construct($db_user = DB_USER, $db_pass = DB_PASS, $db_name = DB_NAME, $db_type = DB_type, $db_path = null, $db_host = DB_HOST)
    {
        parent::__construct($db_user, $db_pass, $db_name, $db_type, $db_path, $db_host);
        parent::connect();
    }

    public function __destruct()
    {
        parent::disconnect();
    }
    public function listTable(){
        parent::select('divergencias');
        return parent::getResult();
    }
    /**
     * XLSXToHtmlParse constructor.
     * @param SimpleXLSX | null
     * @return array | SimpleXLSX
     */
    public function XLSXtoJSON($file)
    {
        $formatted = SimpleXLSX::parse($file);
        return !isset($file) ? array("error" => true, "message" => "miss file", "debug" => array($_GET, $_FILES)) : $formatted->rows();
    }

    /**
     * Insert XLSX row .
     * @param array
     * @return array
     */
    public function XLSXinsert($row)
    {
        $res = parent::insert($row['table'], $row['fieldsAndValues']);
        return ($res === true) ? array("error" => false, "raw" => array($row['table'], $row['fieldsAndValues'])) : array("error" => true, "message" => $res, "raw" => array($row['table'], $row['fieldsAndValues']));
    }
}