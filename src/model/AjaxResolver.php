<?php


namespace App\model;


/**
 * Class AjaxResolver
 * @package App\model
 */
class AjaxResolver
{
    /**
     * @param $cpfcnpj
     * @param $campo1
     * @param $campo2
     * @return bool|string
     */
    public static function condominios($cpfcnpj, $campo1, $campo2)
    {
        $Host = '177.184.16.56';
        $dbUserName = 'rbm_yan';
        $dbPass = 'AolU+j*w';
        $DbName = 'emprestascm_webscm5Digite';
        $db = mysqli_connect('177.184.16.56', $dbUserName, $dbPass) or die ('Could not connect');
        mysqli_select_db($db, $DbName);

        $sql = "";
        if (isset($cpfcnpj, $campo1, $campo2)) {
            $tipo = substr($campo1, 0, 1);
            $pesquisa = substr($cpfcnpj, 0, 1);
            $sql = "SELECT 
                    $campo1 AS CPFCNPJ,
                    LTRIM( $campo2 ) AS NOME
                    FROM operacao d INNER JOIN cronograma i ON
                    d.CODOPERACAO = i.CODOPERACAO 
                    INNER JOIN clientes c ON d.CODCLIENTE = c.CODCLIENTE INNER JOIN rup r ON c.CPFCNPJ = r.CPFCNPJ
                    ";

            if ($tipo == 'a' || $pesquisa == 'a') {
                $sql .= ' LEFT JOIN averbador a ON r.CPFCNPJAVERBADOR = a.CPFCNPJ ';
            }
            if ($tipo == 'c' || $pesquisa == 'c') {
                $sql .= ' LEFT JOIN rup b ON c.CPFCNPJCOBAN = b.CPFCNPJ ';
            }
            if ($tipo == 'e' || $pesquisa == 'e') {
                $sql .= ' LEFT JOIN empregador e ON r.CPFCNPJEMPREGADOR = e.CPFCNPJEMPREGADOR ';
            }

            $sql .= ' WHERE d.CODSTATUS = 7 AND i.LIQUIDA IS NULL ';

            if ($pesquisa != 't') {
                $sql .= ' AND ' . $cpfcnpj;
            }
            $sql .= ' GROUP BY ' . $campo1 . ' ORDER BY NOME ';
            return $sql;
            $query = mysqli_query($db, $sql) or die(mysqli_error($db));
            $html = '<option value=-1>Selecione...</option>';
            while ($row = mysqli_fetch_array($query)) {
                $html .= "<option value='" . $row['CPFCNPJ'] . "'>" . $row['NOME'] . '</option>';
            }
            return $html;
        }
        return false;
    }
}