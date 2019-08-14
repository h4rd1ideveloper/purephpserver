<?php

require_once __DIR__ . './../assets/lib/SimpleXLSX.php';
require_once __DIR__ . './../assets/lib/Helpers.php';
require_once __DIR__ . './../assets/lib/Dao.php';
require_once __DIR__ . './../assets/config/const.php';

class XLSXToHtmlParse extends Dao
{
    public function __construct($production = false)
    {
        if ($production) {
            parent::__construct(
                PRODUCTION_DB_USER,
                PRODUCTION_DB_PASS,
                PRODUCTION_DB_NAME,
                PRODUCTION_DB_TYPE,
                null,
                PRODUCTION_DB_HOST
            );
        } else {
            parent::__construct();
        }

        parent::connect();
    }

    public function __destruct()
    {
        parent::disconnect();
    }

    /**
     * $source = array_filter($params, function ($value) {
     * return is_array($value) ?
     * array_filter($value, function ($inside) {
     * return !($inside == "");
     * }) : false;
     * });
     */
    public function checkInTable($params): array
    {
        /**
         * Setup
         */
        $params['source'] = json_decode($params['source'], true);//Decode string json to array
        $source = $params['source'];
        //container
        $formatted_source = [];
        //Ids results
        $finds = [];
        $notFinds = [];
        //Insert Header to the front of array
        $arrFind = [$params['source'][0]];
        $arrNotFind = [$params['source'][0]];
        //Remove any row that not is a array
        $source = array_filter($source, function ($value) {
            return is_array($value);
        });
        //Remove dont used fields
        $fields = array_filter($params, function ($value) {
            return !is_array($value) ? !($value === 'Não utilizado' || $value === "") : false;
        });
        //Each source
        for ($k = 0; $k < count($source); $k++) {
            //Skip Header
            if ($k != 0) {
                //Fix keys and values from source to formatted_source
                for ($i = 0; $i < count($source[0]); $i++) {
                    if ($source[0][$i] != "" && $source[$k][$i] != "") { // skip empty
                        $formatted_source[$k][$source[0][$i]] = $source[$k][$i];
                    }
                }
            }
        }
        for ($index = 1; $index <= count($formatted_source); $index++) {
            //Fix key and values of the array to verify
            foreach ($fields as $key => $value) {
                isset($formatted_source[$index][$value]) && $toVerify[($index - 1)][$key] = Helpers::formatValueByKey($formatted_source[$index][$value], $key);
            }
            //Check ir current row exist in Database
            parent::select('enel_arrecadacao', '*', null, $toVerify[($index - 1)]);
            //put result on correct array
            if (parent::getNumResults()) {
                $finds[] = $index;
            } else {
                $notFinds[] = $index;
            }
        }
        //Insert Another rows
        foreach ($params['source'] as $i => $row) {
            if (in_array($i, $finds)) {
                $arrFind[] = $row;
            } elseif ($i) {
                $arrNotFind[] = $row;
            }
        }
        return [
            'find' => $arrFind,
            'notFind' => $arrNotFind
        ];
    }

    public function listTable()
    {
        // parent::select('divergencias');
        parent::select('enel_arrecadacao', '*', null, null, null, 1);
        return parent::getResult();
    }

    /**
     * XLSXToHtmlParse constructor.
     * @param SimpleXLSX | null
     * @return array | SimpleXLSX
     */
    public function XLSXtoJSON($file): array
    {
        parent::select('enel_arrecadacao', '*', null, null, null, 1);
        $fields = array();
        foreach (parent::getResult()[0] as $key => $value) {
            $fields[] = $key;
        }
        $formatted = SimpleXLSX::parse($file);
        return !isset($file) ?
            array("error" => true, "message" => "miss file", "debug" => array($_GET, $_FILES))
            : ['xlsx' => $formatted->rows(), 'fields' => $fields];
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