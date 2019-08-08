<?php

require_once __DIR__ . './../assets/lib/SimpleXLSX.php';
require_once __DIR__ . './../assets/lib/Helpers.php';
require_once  __DIR__ . './Dao.php';
require_once  __DIR__ . '../.env.php';

class XLSXToHtmlParse
{
    
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
        $db_ = new Dao(DB_USER_PRODUCTION, DB_PASS_PRODUCTION,DB_PASS_PRODUCTION, DB_TYPE_PRODUCTION, null, DB_HOST_PRODUCTION);
        $res = false;
        if($db_->connect()) {
            $res = $db_->insert($row['table'], $row['values'], $row['fields']);
            $res = ($res === true )? array("error" => false, "message" => $res) : array("error" => true, "message" => $res);
        }
        $db_->disconnect();
        return $res;
    }
}
