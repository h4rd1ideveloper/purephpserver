<?php

/**
 * Class Helpers
 * @author Yan Santos Policarpo
 * @version 1.1.0
 * @todo  Explain every methods
 */
class Helpers
{
    /**
     * @param $toJson
     * @return string
     */
    public static function toJson($toJson): string
    {
        return json_encode($toJson, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $OBJ
     * @return array
     */
    public static function objectKeys(array $OBJ): array
    {
        $arr = [];
        foreach ($OBJ as $key => $valueNotUsedHer) {
            self::insertIfNotExist($key, $arr);
        }
        return $arr;
    }

    /**
     * @param $value
     * @param $arr
     */
    public static function insertIfNotExist($value, & $arr)
    {
        if (!in_array($value, $arr)) {
            $arr[] = $value;
        }
    }

    /**
     * @param array $ids
     * @param $arr
     * @return array
     */
    public static function getRowsById(array $ids, $arr): array
    {
        $source = [];
        foreach ($ids as $id) {
           isset($arr[$id]) && $source[] = $arr[$id];
        }
        return $source;
    }

    /**
     * @param array $ids
     * @param array $headers
     * @param array $source
     * @return array
     */
    public static function countDates(array $ids, array $headers, array $source)
    {
        $label = '';
        foreach ($headers as $header) {
            if (
                $header === 'dataBaixaPagamento' ||
                $header === 'dataOcorrencia' ||
                $header === 'dataPagamento'
            ) {
                $label = $header;
                break;
            }
            continue;
        }
        $dates =[];
        foreach ($ids as $id) {
            $dates[$source[$id][$label]] = ($dates[$source[$id][$label]] ?? 0) + 1;
        }
        return $dates;
    }

    /**
     * Init Headers
     * @param int $timeLimit
     */
    public static function cors()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }

    /**
     * formatValueByKey
     * @param $value
     * @param $key
     * @return mixed|string
     */
    public static function formatValueByKey($value, $key)
    {
        switch ($key) {
            case 'correlativoDocumento':
            {
                return str_pad($value, 3, "0", STR_PAD_LEFT);
                break;
            }
            case 'valor':
            {
                return str_replace(',', '.', $value);
                break;
            }
            case 'dataOcorrencia':
            case 'dataBaixaPagamento':
            case 'dataPagamento':
            {
                $value = str_replace('/', '-', $value);
                $date = explode('-', $value);
                $y = (strlen($date[0]) === 4) ? $date[0] : str_pad($date[0], 4, "20", STR_PAD_LEFT);
                $mm = (strlen($date[1]) === 2) ? $date[1] : str_pad($date[1], 2, "0", STR_PAD_LEFT);
                $dd = (strlen($date[2]) === 2) ? $date[2] : str_pad($date[2], 2, "0", STR_PAD_LEFT);
                return sprintf('%s-%s-%s', $y, $mm, $dd);
                break;
            }
            default :
            {
                return $value;
                break;
            }
        }
    }

    /**
     * @param $string_date
     * @param int $start_cut
     * @param int $end_cut
     * @param string $search
     * @param string $replace
     * @deprecated not in used old context
     * @return false|string
     */
    public static function cut_and_format_date_from_XLSX_to_Ymd($string_date, $start_cut = 0, $end_cut = 10, $search = "/", $replace = "-")
    {
        /*
         * Por algum motivo não consegui utilizar o str_replace na cadeia de funções
         * exemplo quando utilizado o replace não foi possivel verificar qual a data mais antiga
         * Se possivel implementar essa função
         * */
        return date("Y-m-d", strtotime(substr($string_date, $start_cut, $end_cut)));
    }

    /**
     * choose_column_and_insert_into_array
     * @param $key integer index column of the XLSX row
     * @param $ref_criticas array to store required content of the row of  XLSX
     * @param $ref_coluna
     * @return void
     * @version 1.1.0
     * @see https://www.php.net/manual/pt_BR/language.references.pass.php
     * @deprecated not in used old context
     * @author Yan P santos
     */
    public static function choose_column_and_insert_into_array($key, & $ref_criticas, & $ref_coluna)
    {
        switch ($key) {
            case 3:
            {
                $ref_criticas["nc"][] = $ref_coluna;
                break;
            }
            case 4 :
            {
                $ref_criticas["dv"][] = $ref_coluna;
                break;
            }
            case 9:
            {
                $ref_criticas["dtb"][] = cut_and_format_date_from_XLSX_to_Ymd($ref_coluna);
                break;
            }
            case 13:
            {
                $ref_criticas["c"][] = $ref_coluna;
                break;
            }
            case 14:
            {
                $ref_criticas["td"][] = $ref_coluna;
                break;
            }
            default:
            {
                break;
            }
        }
    }
}