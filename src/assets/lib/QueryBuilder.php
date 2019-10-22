<?php

namespace App\assets\lib;

use PDO;
use PDOStatement;

class QueryBuilder
{
    /**
     * @param $table    string
     * @param $value    string|array
     * @param $where    array
     * @return bool|string
     */
    public static function queryUpdate($table, $value, $where)
    {
        if (!$table) {
            return false;
        }
        $q = sprintf(
        /** @lang text */
            "UPDATE %s SET ",
            $table
        );

        if (!is_array($value)) {
            $q .= sprintf(
            /** @lang text */
                "%s",
                $value
            );
        } else {
            $q = self::keyAndValue($q, $value, ',', true);
        }
        $q =
            /** @lang text */
            self::keyAndValue($q . " WHERE ", $where, 'and', true);
        return $q;
    }

    /**
     * Each key and Value to String
     * @param $q string current query string
     * @param $keyAndValue array to set key and values
     * @param null|string $delimiter delimiter each expression
     * @param null|bool $quote has quote '%s'
     * @param bool $bind
     * @return array|string;
     */
    protected static function keyAndValue($q, $keyAndValue, $delimiter = 'and', $quote = false, $bind = false)
    {
        $i = 0;
        if ($bind === false) {
            if ($quote) {
                foreach ($keyAndValue as $key => $value) {
                    $q .= ($i++ == 0) ? sprintf(" %s = '%s' ", $key, $value) : sprintf(" %s %s = '%s' ", $delimiter, $key, $value);
                }
            } else {
                foreach ($keyAndValue as $key => $value) {
                    $q .= ($i++ == 0) ? sprintf(" %s = %s ", $key, $value) : sprintf(" %s %s = %s ", $delimiter, $key, $value);
                }
            }
            return $q;
        } else {
            $paramsToBind = array();
            foreach ($keyAndValue as $key => $value) {
                $q .= ($i++ == 0) ? sprintf(" %s = :%s ", $key, $key) : sprintf(" %s %s = :%s ", $delimiter, $key, $key);
                $paramsToBind[":" . $key] = $value;
            }
            return array($q, $paramsToBind);
        }
    }

    /**
     * @param $PDO PDO
     * @param string $table string
     * @param null|string $columns string
     * @param null|string|array $join string
     * @param null|array|string $where array
     * @param null $group
     * @param null|string $order string
     * @param null|string $limit string
     * @param $bind bool
     * @return bool|PDOStatement
     */
    public static function querySelect($PDO, $table, $columns = "*", $join = null, $where = null, $group = null, $order = null, $limit = null, $bind = false)
    {
        if (!$table) {
            return false;
        }
        $q = sprintf(
        /** @lang text */
            "SELECT %s FROM %s ",
            $columns,
            $table
        );
        if ($join !== null) {
            if (!is_array($join)) {
                $q .= $join;
            } else {
                foreach ($join as $currentJoin) {
                    $q .= $currentJoin . " ";
                }
            }
        }
        if ($where != null) {
            $q .= " WHERE ";
            if (!is_string($where) && is_array($where)) {
                if ($bind) {
                    $aux = self::keyAndValue($q, $where, 'and', true, $bind);
                    $q = $aux[0];
                    $params = $aux[1];
                } else {
                    $q = self::keyAndValue($q, $where, 'and', true);
                }
            } elseif (is_string($where) && Helpers::stringIsOk($where)) {
                $q .= $where;
            }
        }
        if ($group != null) {
            $q .= sprintf(" GROUP BY %s ", $group);
        }
        if ($order != null) {
            $q .= sprintf(" ORDER BY %s ", $order);
        }
        if ($limit != null) {
            $q .= sprintf(" limit %s ", (string)$limit);
        }
        $count = 1;
        //exit(var_dump($q));
        $sql = $PDO->prepare($q);
        if (isset($params) && count($params)) {
            foreach ($params as $key => &$param) {
                $sql->bindParam($key, $param);
                //print_r("<br/>" . $key . " " . $param);
            }
        }
        return $sql;
    }

    /**
     * Query to insert
     * @param $table
     * @param $fieldsAndValues
     * @return bool|string
     */
    public static function queryInsert($table, $fieldsAndValues)
    {
        if (!$table) {
            return false;
        }
        $insert = sprintf(
        /**@lang text */
            'INSERT INTO %s ',
            $table
        );
        $values = '';
        $fields = '';
        self::fillFieldsAndValues($values, $fields, $fieldsAndValues);
        $insert .= sprintf("(%s) VALUES (%s)", $fields, $values);
        return $insert;
    }

    /**
     * @param $values
     * @param $fields
     * @param $fieldsAndValues
     */
    private static function fillFieldsAndValues(&$values, &$fields, $fieldsAndValues)
    {
        $i = 0;
        foreach ($fieldsAndValues as $field => $value) {
            if ((count($fieldsAndValues) - 1) != $i++) {
                $fields .= sprintf(' %s, ', $field);
                $values .= sprintf(" '%s', ", $value);
            } else {
                $fields .= sprintf(' %s ', $field);
                $values .= sprintf(" '%s' ", $value);
            }
        }
    }
}
