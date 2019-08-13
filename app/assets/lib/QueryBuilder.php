<?php

/**
 * Manager query String
 */
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
            "UPDATE %s SET",
            $table
        );

        if (!is_array($value)) {
            $q .= sprintf(
            /** @lang text */
                " controleArrec = '%s' ",
                $value
            );
        } else {
            $q = self::keyAndValue($q, $value, ',', true);
        }
        $q =
            /** @lang text */
            " WHERE " . self::keyAndValue($q, $where, 'and', true);
        return $q;
    }

    /**
     * Each key and Value to String
     * @param $q string current query string
     * @param $keyAndValue array to set key and values
     * @param null|string $delimiter delimiter each expression
     * @param null|bool $quote has quote '%s'
     * @return string
     */
    protected static function keyAndValue($q, $keyAndValue, $delimiter = 'and', $quote = false)
    {
        $i = 0;
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
    }

    /**
     * @param string $table string
     * @param null|string $columns string
     * @param null|string|array $join string
     * @param null|string|array $where array
     * @param null|string $order string
     * @param null|string $limit string
     * @return bool|string
     */
    public static function querySelect($table, $columns = "*", $join = null, $where = null, $order = null, $limit = null)
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
                $join && $q .= sprintf(" %s %s on ", $join['type'], $join['table']);
                if (!is_array($join['on'])) {
                    $q .= sprintf(" %s ", $join['on']);
                } else {
                    $q = self::keyAndValue($q, $join['on']);
                }
            }
        }
        if ($where != null) {
            $q .= "WHERE ";
            $q = self::keyAndValue($q, $where, 'and', true);
        }
        if ($order != null) {
            $q .= sprintf(" ORDER BY %s ", $order);
        }
        if ($limit != null) {
            $q .= sprintf(" limit %s ", (string)$limit);
        }
        return $q;
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
    private static function fillFieldsAndValues(& $values, & $fields, $fieldsAndValues): void
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