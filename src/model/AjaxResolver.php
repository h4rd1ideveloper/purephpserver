<?php


namespace App\model;


use App\assets\lib\Dao;
use App\assets\lib\Helpers;
use App\assets\lib\PDF\PDF;

/**
 * Class AjaxResolver
 * @package App\model
 */
class AjaxResolver
{
    /**
     *
     * @param $start
     * @param $end
     * @param $averbador
     * @param $nomeOuCpf
     * @param $condominioValue
     * @return array
     */
    public static function boletos($start, $end, $averbador, $nomeOuCpf, $condominioValue)
    {
        $_db = new Dao('177.184.16.56', 'rbm_yan', 'AolU+j*w', 'emprestascm_webscm6');
        $_db->connect();

        $de = $start; //Helpers::soNumero($start);
        $ate = $end; //Helpers::soNumero($end);
        $averbador = Helpers::soNumero($averbador);
        $fields = ' count(c.CODCRONOGRAMA) As QTDE_PARCEELAS ';
        $table = ' propostas p ';
        $joins = array(
            ' Inner Join rup r On r.CPFCNPJ = p.CPFCNPJ ',
            ' left Join empregador emp On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR ',
            ' Inner Join operacao o On p.CODPROPOSTA = o.CODPROPOSTA ',
            ' Inner Join cronograma c On c.CODOPERACAO = o.CODOPERACAO '
        );
        $where = "
            p.CODAVERBADOR = '$averbador' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And
            c.LIQUIDA is null And o.DATAACEITE > '2016-10-05' AND
            o.CODSTATUS = 7 And r.SITUACAOPESSOA = 'N'
            And r.DESLIGADO = 'N' And r.BLOQUEADO = 'N' And
            c.SITUACAOPESSOACRONO <> 'N'
        ";

        if (Helpers::stringIsOk($nomeOuCpf)) {
            $where .= (Helpers::isOnlyNumbers($nomeOuCpf)) ?
                " And r.CPFCNPJ = '$nomeOuCpf'" :
                " And r.NOME like '%$nomeOuCpf%'";
        }
        IF ($condominioValue != -1) {
            $where .= " And p.CODEMPREGADOR = '$condominioValue'";
        }

        IF (Helpers::stringIsOk($de) && Helpers::stringIsOk($ate)) {
            $where .= " And c.VENCIMENTO Between '$de' And '$ate'";
        }
        $order = " emp.DESCRICAO, r.NOME, c.VENCIMENTO";
        $_db->select(
            $table,
            $fields,
            $joins,
            $where,
            null,
            $order
        );
        $results = $_db->getResult();

        IF ($_db->getNumResults() > 0) {
            $qtde_parcelas = $results[0]['QTDE_PARCEELAS'];
        } Else {
            $qtde_parcelas = 0;
        }
        $response = array(
            'qtde_parcelas' => $qtde_parcelas
        );
        $_db->disconnect();

        $_db2 = new Dao('177.184.16.56', 'rbm_yan', 'AolU+j*w', 'emprestascm_webscm6');
        $_db2->connect();
        $table = 'propostas p';
        $fields = ' p.CODAVERBADOR, emp.DESCRICAO As EMPREGADOR, c.CODCRONOGRAMA, p.NPARCELAS, c.NDOC, r.CODEMPRESTA as MATRICULA, emp.CODEMPREGADOR, c.VL_FACE, p.CPFCNPJ, r.NOME, o.CODOPERACAO As CODEMPRESTA, p.VALORFINANCIADO, c.VENCIMENTO ';
        $joins = array(
            ' Inner Join rup r On r.CPFCNPJ = p.CPFCNPJ ',
            ' left Join empregador emp On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR ',
            ' Inner Join operacao o On p.CODPROPOSTA = o.CODPROPOSTA ',
            ' Inner Join cronograma c On c.CODOPERACAO = o.CODOPERACAO '
        );
        $where = "p.CODAVERBADOR = '$averbador' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And c.LIQUIDA is null
        And o.DATAACEITE > '2016-10-05' AND o.CODSTATUS = 7
        And r.SITUACAOPESSOA = 'N' And r.DESLIGADO = 'N' And r.BLOQUEADO = 'N'
        And c.SITUACAOPESSOACRONO = 'N'";

        if (Helpers::stringIsOk($nomeOuCpf)) {
            $where .= (Helpers::isOnlyNumbers($nomeOuCpf)) ?
                " And r.CPFCNPJ = '$nomeOuCpf'" :
                " And r.NOME like '%$nomeOuCpf%'";
        }

        IF ($condominioValue != -1) {
            $where .= " And p.CODEMPREGADOR = '$condominioValue'";
        }

        IF ($de != '' && $ate != '') {
            $where .= " And c.VENCIMENTO Between '$de' And '$ate'";
        }

        $group = " c.CODOPERACAO ";
        $order = " emp.DESCRICAO, r.NOME, c.VENCIMENTO ";
        $_db2->select(
            $table,
            $fields,
            $joins,
            $where,
            $group,
            $order
        );
        IF ($_db2->getNumResults() > 0) {
            $total = Helpers::Reducer(
                $_db2->getResult(),
                function ($initialValue, $value, $key) {
                    return (float)((float)$initialValue + (float)$value['VL_FACE']);
                },
                0
            );
            $total = number_format($total, 2, ',', '.');
            $response['total'] = $total;
            $response['table'] = Helpers::Map($_db2->getResult(), static function ($row, $key) {
                $contrato = explode('-', $row['CODEMPRESTA']);
                $contrato = $contrato[0];
                $parcela = str_pad($row['NDOC'], 3, '0', STR_PAD_LEFT);
                $contrato = $contrato . '-' . $parcela;
                return array(
                    $row['EMPREGADOR'],
                    $row['CPFCNPJ'],
                    $row['NOME'],
                    $contrato,
                    $row['VENCIMENTO'],
                    $row['VL_FACE']
                );
            });
        }

        $_db2->disconnect();
        return $response;
    }

    /**
     * @param $cpfcnpj
     * @param $campo1
     * @param $campo2
     * @return bool|string
     */
    public static function condominios($cpfcnpj, $campo1, $campo2)
    {
        /**
         * @param $tipo
         * @param $pesquisa
         * @return string
         */
        function joinResolver($tipo, $pesquisa)
        {
            $join = '';
            if ($tipo === 'a' || $pesquisa === 'a') {
                $join .= ' LEFT JOIN averbador a ON r.CPFCNPJAVERBADOR = a.CPFCNPJ ';
            }
            if ($tipo === 'c' || $pesquisa === 'c') {
                $join .= ' LEFT JOIN rup b ON c.CPFCNPJCOBAN = b.CPFCNPJ ';
            }
            if ($tipo === 'e' || $pesquisa === 'e') {
                $join .= ' LEFT JOIN empregador e ON r.CPFCNPJEMPREGADOR = e.CPFCNPJEMPREGADOR ';
            }
            return $join;
        }

        ;
        /**
         * @param $pesquisa
         * @param $cpfcnpj
         * @return string
         */
        function whereResolver($pesquisa, $cpfcnpj)
        {
            $sql = ' d.CODSTATUS = 7 AND i.LIQUIDA IS NULL ';
            if ($pesquisa !== 't') {
                $sql .= ' AND ' . $cpfcnpj;
            }
            return $sql;
        }

        ;
        if (isset($cpfcnpj, $campo1, $campo2)) {
            $_db = new Dao('177.184.16.56', 'rbm_yan', 'AolU+j*w', 'emprestascm_webscm6');
            $_db->connect();
            $tipo = substr($campo1, 0, 1);
            $pesquisa = substr($cpfcnpj, 0, 1);

            $join = 'INNER JOIN cronograma i ON d.CODOPERACAO = i.CODOPERACAO INNER JOIN clientes c ON
                    d.CODCLIENTE = c.CODCLIENTE INNER JOIN rup r ON c.CPFCNPJ = r.CPFCNPJ
                    ' . joinResolver($tipo, $pesquisa);

            $fields = sprintf(
                ' %s AS CPFCNPJ, LTRIM( %s ) AS NOME',
                $campo1,
                $campo2
            );
            $where = whereResolver($pesquisa, $cpfcnpj);
            $groupBy = $campo1;
            $_db->select(
                'operacao d',
                $fields,
                $join,
                $where,
                $groupBy,
                ' NOME'
            );
            $html = '<option value=-1>GERAL</option>';
            foreach ($_db->getResult() as $row) {
                if (Helpers::stringIsOk($row['CPFCNPJ']) && Helpers::stringIsOk($row['NOME'])) {
                    $html .= "<option value='" . $row['CPFCNPJ'] . "'>" . $row['NOME'] . '</option>';
                }
            }
            $_db->disconnect();
            return $html;
        }
        return false;
    }

    /**
     * @param $start
     * @param $end
     * @param $averbador
     * @param $nomeOuCpf
     * @param $condominioValue
     */
    public static function relatorio($start, $end, $averbador, $nomeOuCpf, $condominioValue)
    {
        $_db = new Dao('177.184.16.56', 'rbm_yan', 'AolU+j*w', 'emprestascm_webscm6');
        $_db->connect();
        $_db->select(
            'empresas',
            'RAZAOSOCIAL, CIDADE'
        );
        $qrempresa = $_db->getResult();
        $pdf = new PDF('P');
        $pdf->nomemp = $qrempresa[0]['RAZAOSOCIAL'];
        $pdf->ncidade = $qrempresa[0]['CIDADE'];
        $pdf->titulo = 'Relatório de Boletão - Período De: ' . $start . ' Até: ' . $end;
        $de = $start;
        $ate = $end;
        $pdf->AddPage();
        $pdf->SetFillColor(172, 196, 242);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.3);
        $fill = 0;
        IF ($_db->getResult() > 0) {
            $_db2 = new Dao('177.184.16.56', 'rbm_yan', 'AolU+j*w', 'emprestascm_webscm6');
            $_db2->connect();
            $_db2->select(
                'averbador',
                'NOME, CPFCNPJ',
                null,
                "CPFCNPJ = '$averbador'",
                null,
                'By NOME'
            );
            $qraverbador = $_db2->getResult();
            $qraverbador = $qraverbador[0];
            $fill = 1;
            $pdf->SetFont('Times', 'B', 8);
            $w = array(190);
            $pdf->Cell($w[0], 5, 'Averbador: ' . $qraverbador['CPFCNPJ'] . ' - ' . $qraverbador['NOME'], 1, 0, 'C', $fill);
            $pdf->Ln();
            $w = array(47, 30, 47, 26, 20, 20);
            $pdf->Cell($w[0], 5, 'Condomínio', 1, 0, 'C', $fill);
            $pdf->Cell($w[1], 5, 'CPF/CNPJ', 1, 0, 'C', $fill);
            $pdf->Cell($w[2], 5, 'Nome', 1, 0, 'C', $fill);
            $pdf->Cell($w[3], 5, 'Contrato', 1, 0, 'C', $fill);
            $pdf->Cell($w[4], 5, 'Vencimento', 1, 0, 'C', $fill);
            $pdf->Cell($w[5], 5, 'Valor', 1, 0, 'C', $fill);
            $pdf->Ln();
            $_db2->disconnect();
        }
        $_db->disconnect();
        $fill = 0;
        $totvalor = 0;
        $count = 0;
        $pdf->SetFont('Times', '', 8);
        $_db->disconnect();
        $_db->connect();

        $table = 'propostas p';
        $fields = ' p.CODAVERBADOR, emp.DESCRICAO As EMPREGADOR, c.CODCRONOGRAMA, p.NPARCELAS, c.NDOC, r.CODEMPRESTA as MATRICULA, emp.CODEMPREGADOR, c.VL_FACE, p.CPFCNPJ, r.NOME, o.CODOPERACAO As CODEMPRESTA, p.VALORFINANCIADO, c.VENCIMENTO ';
        $joins = array(
            ' Inner Join rup r On r.CPFCNPJ = p.CPFCNPJ ',
            ' left Join empregador emp On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR ',
            ' Inner Join operacao o On p.CODPROPOSTA = o.CODPROPOSTA ',
            ' Inner Join cronograma c On c.CODOPERACAO = o.CODOPERACAO '
        );
        $where = "p.CODAVERBADOR = '$averbador' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And c.LIQUIDA is null
        And o.DATAACEITE > '2016-10-05' AND o.CODSTATUS = 7
        And r.SITUACAOPESSOA = 'N' And r.DESLIGADO = 'N' And r.BLOQUEADO = 'N'
        And c.SITUACAOPESSOACRONO = 'N'";

        if (Helpers::stringIsOk($nomeOuCpf)) {
            $where .= (Helpers::isOnlyNumbers($nomeOuCpf)) ?
                " And r.CPFCNPJ = '$nomeOuCpf'" :
                " And r.NOME like '%$nomeOuCpf%'";
        }

        IF ($condominioValue != -1) {
            $where .= " And p.CODEMPREGADOR = '$condominioValue'";
        }

        IF ($de != '' && $ate != '') {
            $where .= " And c.VENCIMENTO Between '$de' And '$ate'";
        }

        $group = " c.CODOPERACAO ";
        $order = " emp.DESCRICAO, r.NOME, c.VENCIMENTO ";
        $_db->select(
            $table,
            $fields,
            $joins,
            $where,
            $group,
            $order
        );
        foreach ($_db->getResult() as $row) {
            $codEmpresta = explode('-', $row['CODEMPRESTA']);
            $codEmpresta = $codEmpresta[0];
            $parcela = str_pad($row['NDOC'], 3, '0', STR_PAD_LEFT);
            $pdf->Cell($w[0], 5, substr($row['EMPREGADOR'], 0, 25), 'B', 0, 'L', $fill);
            $pdf->Cell($w[1], 5, $row['CPFCNPJ'], 'B', 0, 'C', $fill);
            $pdf->Cell($w[2], 5, substr($row['NOME'], 0, 25), 'B', 0, 'L', $fill);
            $pdf->Cell($w[3], 5, $codEmpresta . '-' . $parcela, 'B', 0, 'C', $fill);
            $pdf->Cell($w[4], 5, $row['VENCIMENTO'], 'B', 0, 'C', $fill);
            $pdf->Cell($w[5], 5, number_format($row['VL_FACE'], 2, ',', '.'), 'B', 0, 'R', $fill);
            $pdf->Ln();
            $totvalor += $row['VL_FACE'];
            $count++;
        }
        $pdf->SetFont('Times', 'B', 8);
        $pdf->Cell($w[0], 5, 'Total: ' . $count, 0, 0, 'L', $fill);
        $pdf->Cell($w[1], 5, '', 0, 0, 'L', $fill);
        $pdf->Cell($w[2], 5, '', 0, 0, 'L', $fill);
        $pdf->Cell($w[3], 5, '', 0, 0, 'L', $fill);
        $pdf->Cell($w[4], 5, '', 0, 0, 'L', $fill);
        $pdf->Cell($w[5], 5, number_format($totvalor, 2, ',', '.'), 0, 0, 'R', $fill);
        $pdf->Ln();
        ob_clean();
        $pdf->Output('relBoletao.pdf', 'D');
    }
}
