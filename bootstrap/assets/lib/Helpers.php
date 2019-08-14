<?php

class Helpers
{
    public static function cors($timeLimit = 0)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        set_time_limit($timeLimit);
    }

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
            default :
            {
                return $value;
                break;
            }
        }
    }

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