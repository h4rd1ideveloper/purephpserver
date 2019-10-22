<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);

include('inc/conecta.php');
//Glauco2
if ($_POST['hf_relatorio'] != 1) include('inc/script.php');

include('inc/funcoes.php');

require_once("rpcl/rpcl.inc.php");
require_once('inc/fpdf/fpdf.php');
//Includes
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("dbtables.inc.php");
use_unit("styles.inc.php");
use_unit("dbctrls.inc.php");
use_unit("db.inc.php");

//Class definition
class PDF extends FPDF
{
    public $nomemp;
    public $ncidade;
    public $titulo;

    function Header()
    {
        $this->SetFont('Arial', 'B', 8);
        $this->Image('img/logoempresa.jpg', 10, 10, 21, 11, 'JPEG');

        $w = array(30, 130, 30);
        $this->Cell($w[0], 5, '', 0, 0, 'L');
        $this->Cell($w[1], 5, 'WEBAGENTE - Sistema de Gest�o para MICROCR�DITO - ONLINE', 0, 0, 'L');
        $this->Cell($w[2], 5, 'Pag.: ' . $this->PageNo(), 0, 0, 'R');
        $this->Ln();

        $this->Cell($w[0], 5, '', 0, 0, 'L');
        $this->Cell($w[1], 5, $this->titulo, 0, 0, 'L');
        $this->Cell($w[2], 5, 'Emiss�o: ' . date("d/m/Y - H:i:s"), 0, 0, 'R');
        $this->Ln();

        $w = array(30, 160);
        $this->Cell($w[0], 5, '', 'B', 0, 'L');
        $this->Cell($w[1], 5, 'Empresa: ' . $this->nomemp . ' - ' . $this->ncidade, 'B', 0, 'L');
        $this->Ln(10);
    }
}

class gerarremessaadmboletao extends Page
{
    public $HiddenField2 = null;
    public $Button6 = null;
    public $qrempresa = null;
    public $Label25 = null;
    public $Label24 = null;
    public $Label23 = null;
    public $ComboBox3 = null;
    public $Label21 = null;
    public $Label19 = null;
    public $Label18 = null;
    public $ComboBox2 = null;
    public $Label8 = null;
    public $hf_relatorio = null;
    public $HiddenField1 = null;
    public $qrboletao = null;
    public $Label17 = null;
    public $Edit3 = null;
    public $qrpesquisa = null;
    public $Label16 = null;
    public $Label15 = null;
    public $Button2 = null;
    public $Edit2 = null;
    public $Edit1 = null;
    public $Label14 = null;
    public $dspesquisa = null;
    public $qraverbador = null;
    public $qrproposta = null;
    public $qrinserir = null;
    public $Label13 = null;
    public $Label12 = null;
    public $Label11 = null;
    public $Label10 = null;
    public $Label9 = null;
    public $Label7 = null;
    public $Label6 = null;
    public $Label5 = null;
    public $Label4 = null;
    public $Label3 = null;
    public $DBRepeater1 = null;
    public $Shape1 = null;
    public $Label2 = null;
    public $Button1 = null;
    public $ComboBox1 = null;
    public $Label1 = null;
    public $hf_titulo = null;
    public $StyleSheet1 = null;
    public $database = null;
    public $Button3 = null;
    public $Button4 = null;
    public $lb_contrato = null;
    public $Button5 = null;
    public $qratualizar = null;
    public $Label20 = null;
    public $Edit4 = null;
    public $Label22 = null;
    public $ComboBox4 = null;

    function Button1Click($sender, $params)
    {
        $de = gravaData($this->Edit1->Text);
        $ate = gravaData($this->Edit2->Text);
        $vencimento = gravaData($this->Edit3->Text);

        $dataatual = date('Y-m-d');

        IF ($vencimento >= $dataatual) {
            IF ($this->ComboBox1->ItemIndex <> -1) {
                $averbador = $this->ComboBox1->ItemIndex;
                $sql = "Select emp.DESCRICAO As EMPREGADOR, c.CODCRONOGRAMA, o.CODOPERACAO, p.NPARCELAS, c.NDOC, r.CODEMPRESTA as MATRICULA, emp.CODEMPREGADOR, c.VL_FACE, p.CPFCNPJ, r.NOME, o.CODOPERACAO As CODEMPRESTA, p.VALORFINANCIADO, c.VENCIMENTO From propostas p
                Inner Join rup r
                On r.CPFCNPJ = p.CPFCNPJ
                left Join empregador emp
                On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR
                Inner Join operacao o
                On o.CODPROPOSTA = p.CODPROPOSTA
                Inner Join cronograma c
                On c.CODOPERACAO = o.CODOPERACAO
                Where p.CODAVERBADOR = '" . $averbador . "' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And c.LIQUIDA is null
                And r.DESLIGADO = 'N'
                And r.BLOQUEADO = 'N'
                And r.SITUACAOPESSOA = 'N'
                And c.SITUACAOPESSOACRONO = 'N'";

                IF ($this->Edit4->Text <> "") {

                    $cliente = $this->Edit4->Text;
                    IF ($this->ComboBox3->ItemIndex == 1) {
                        $sql .= " And r.CPFCNPJ = '$cliente'";
                    }

                    IF ($this->ComboBox3->ItemIndex == 2) {
                        $sql .= " And r.NOME like '%$cliente%'";
                    }
                }

                IF ($this->ComboBox2->ItemIndex <> -1) {
                    $empregador = $this->ComboBox2->ItemIndex;
                    $sql .= " And p.CODEMPREGADOR = '" . $empregador . "'";
                }

                IF ($de != '' And $ate != '') {
                    $sql .= " And c.VENCIMENTO Between '" . $de . "' And '" . $ate . "'";
                }

                $sql .= " Group By c.CODOPERACAO
                Order By emp.DESCRICAO, r.NOME, c.VENCIMENTO";
                $this->qrpesquisa->close();
                $this->qrpesquisa->SQL = $sql;
                $this->qrpesquisa->open();

                IF (($this->qrpesquisa->RecordCount > 0) And (!$erro)) {
                    $this->DBRepeater1->DataSource = 'dspesquisa';

                    $sql = "Select NOSSONUM_SEQ from empresas
                    Where CODEMPRESA = 1";
                    $query = mysql_query($sql);
                    $campos = mysql_fetch_array($query);
                    $nossonum1 = $campos['NOSSONUM_SEQ'];

                    $nossonum = str_pad($nossonum1, 11, "0", STR_PAD_LEFT);

                    $cnpj_cond = "Null";
                    IF (ISSET($_POST['ComboBox2']) And $_POST['ComboBox2'] <> -1) {
                        $cnpj_cond = "'" . $_POST['ComboBox2'] . "'";
                    }

                    $sql = "Insert Into boletao (DTHR_INCLUSAO, USU_INCLUSAO, VENCIMENTO_ENVIO, CNPJ_ADM, NOSSONUMERO, CNPJ_COND)
                    Values (NOW(), '" . $_SESSION["cpf_global"] . "', '" . gravaData($_POST['Edit3']) . "', '" . $_POST['ComboBox1'] . "', '$nossonum', $cnpj_cond)";
                    mysql_query($sql) or die($sql);

                    $sql = "Select last_insert_id () As last_id from boletao";
                    $query = mysql_query($sql);
                    $campos = mysql_fetch_array($query);
                    $this->HiddenField1->Value = $campos['last_id'];
                    $codboletao = $campos['last_id'];

                    $sql = "Insert into icrononossonumero (NOSSONUMERO, CODCRONOGRAMA, TIPO_BOLETO) Values
                    ('$nossonum', $codboletao, 'B')";
                    mysql_query($sql);

                    $sql = "Update empresas Set NOSSONUM_SEQ = NOSSONUM_SEQ + 1
                    Where CODEMPRESA = 1 ";
                    mysql_query($sql);
                } Else {
                    $this->DBRepeater1->DataSource = '';
                }

                $total = 0;

                While (!$this->qrpesquisa->EOF) {

                    $contrato = $this->qrpesquisa->CODOPERACAO;
                    $parcela = str_pad($this->qrpesquisa->NDOC, 3, '0', STR_PAD_LEFT);
                    $contrato = $contrato . '-' . $parcela;

                    $codcronograma = $this->qrpesquisa->CODCRONOGRAMA;

                    $valor = $this->qrpesquisa->VL_FACE;

                    $total += $valor;

                    $sql = "Insert Into iboletao (CODBOLETAO, CONTRATO, VALOR, CODCRONOGRAMA)
                    Values ('" . $campos['last_id'] . "', '" . $contrato . "', '" . $valor . "', " . $codcronograma . ")";
                    $this->qrboletao->close();
                    $this->qrboletao->SQL = $sql;
                    $this->qrboletao->open();
                    $this->qrboletao->close();

                    $this->qrpesquisa->next();
                }

                IF (($this->qrpesquisa->RecordCount > 0) And (!$erro)) {
                    $this->Label16->Caption = number_format($total, 2, ',', '.');
                    // echo "<script>alerta('Aten��o!', 'O vencimento do boleto tem que ser maior ou igual a data atual.', 'erro');</script>";
                }
            }
        } Else {
            echo "<script>alerta('Aten��o!', 'O vencimento do boleto deve ser maior ou igual a data atual.', 'erro');</script>";
        }
    }

    function ComboBox1BeforeShow($sender, $params)
    {
        $sql = "SELECT a.NOME, a.CPFCNPJ FROM operacao d
            INNER JOIN cronograma i ON d.CODOPERACAO = i.CODOPERACAO
            INNER JOIN clientes c ON d.CODCLIENTE = c.CODCLIENTE
            INNER JOIN rup r ON c.CPFCNPJ = r.CPFCNPJ
            LEFT JOIN averbador a ON r.CPFCNPJAVERBADOR = a.CPFCNPJ
            WHERE	d.CODSTATUS = 7 AND i.LIQUIDA IS NULL GROUP BY a.CPFCNPJ ORDER BY NOME";
        $this->qraverbador->close();
        $this->qraverbador->SQL = $sql;
        $this->qraverbador->open();

        $items[-1] = 'Selecione...';

        While (!$this->qraverbador->EOF) {
            $items[$this->qraverbador->CPFCNPJ] = $this->qraverbador->NOME;
            $this->qraverbador->next();
        }
        $this->ComboBox1->Items = $items;
    }

    function Label13BeforeShow($sender, $params)
    {
        $codcronograma = $this->qrpesquisa->CODCRONOGRAMA;
        $sql = "SELECT CONTROLE FROM iboletao WHERE CODCRONOGRAMA =" . $codcronograma . "
         Order By CONTROLE Desc Limit 0,1";
        $qry = mysql_query($sql);
        $ft = mysql_fetch_object($qry);
        $cod = $ft->CONTROLE;
        $codEmpresta = $this->qrpesquisa->CODEMPRESTA;
        $codEmpresta = explode('-', $codEmpresta);
        $codEmpresta = $codEmpresta[0];
        $codEmpresta .= '-' . str_pad($this->qrpesquisa->NDOC, 3, '0', STR_PAD_LEFT);
        $parametro = $cod . '/' . $codEmpresta;
        $this->Label13->Caption = '<i class="fa fa-ban"><span class = "hidden">' . $parametro . '</span></i>';
    }

    function Button2Click($sender, $params)
    {
        $this->HiddenField2->Value = $this->ComboBox2->ItemIndex;

        $de = gravaData($this->Edit1->Text);
        $ate = gravaData($this->Edit2->Text);
        $averbador = $this->ComboBox1->ItemIndex;

        ### Pacelas fora do bolet�o no perpiodo
        $sql = "Select count(c.CODCRONOGRAMA) As QTDE_PARCEELAS From propostas p
        Inner Join rup r
        On r.CPFCNPJ = p.CPFCNPJ
        left Join empregador emp
        On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR
        Inner Join operacao o
        On p.CODPROPOSTA = o.CODPROPOSTA
        Inner Join cronograma c
        On c.CODOPERACAO = o.CODOPERACAO
        Where p.CODAVERBADOR = '" . $averbador . "' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And c.LIQUIDA is null
        And o.DATAACEITE > '2016-10-05' AND o.CODSTATUS = 7
        And r.SITUACAOPESSOA = 'N' And r.DESLIGADO = 'N' And r.BLOQUEADO = 'N'
        And c.SITUACAOPESSOACRONO <> 'N'";

        IF ($this->Edit4->Text <> "") {

            $cliente = $this->Edit4->Text;
            IF ($this->ComboBox3->ItemIndex == 1) {
                $sql .= " And r.CPFCNPJ = '$cliente'";
            }

            IF ($this->ComboBox3->ItemIndex == 2) {
                $sql .= " And r.NOME like '%$cliente%'";
            }
        }

        IF ($this->ComboBox2->ItemIndex <> -1) {
            $empregador = $this->ComboBox2->ItemIndex;
            $sql .= " And p.CODEMPREGADOR = '" . $empregador . "'";
        }

        IF ($de != '' And $ate != '') {
            $sql .= " And c.VENCIMENTO Between '" . $de . "' And '" . $ate . "'";
        }

        $sql .= " Order By emp.DESCRICAO, r.NOME, c.VENCIMENTO";
        $this->qrpesquisa->Close();
        $this->qrpesquisa->SQL = $sql;
        $this->qrpesquisa->Open();

        IF ($this->qrpesquisa->RecordCount > 0) {
            $qtde_parcelas = $this->qrpesquisa->QTDE_PARCEELAS;
            $this->Label25->Style = "bt_icone amarelo popup";
        } Else {
            $qtde_parcelas = 0;
            $this->Label25->Style = "bt_icone verde popup";
        }

        $this->Label25->Hint = "Parcelas Fora do Bolet�o no Per�odo ($qtde_parcelas)";
        ### Fim Pacelas fora do bolet�o no perpiodo


        $sql = "Select p.CODAVERBADOR, emp.DESCRICAO As EMPREGADOR, c.CODCRONOGRAMA, p.NPARCELAS, c.NDOC, r.CODEMPRESTA as MATRICULA, emp.CODEMPREGADOR, c.VL_FACE, p.CPFCNPJ, r.NOME, o.CODOPERACAO As CODEMPRESTA, p.VALORFINANCIADO, c.VENCIMENTO From propostas p
        Inner Join rup r
        On r.CPFCNPJ = p.CPFCNPJ
        left Join empregador emp
        On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR
        Inner Join operacao o
        On p.CODPROPOSTA = o.CODPROPOSTA
        Inner Join cronograma c
        On c.CODOPERACAO = o.CODOPERACAO
        Where p.CODAVERBADOR = '" . $averbador . "' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And c.LIQUIDA is null
        And o.DATAACEITE > '2016-10-05' AND o.CODSTATUS = 7
        And r.SITUACAOPESSOA = 'N' And r.DESLIGADO = 'N' And r.BLOQUEADO = 'N'
        And c.SITUACAOPESSOACRONO = 'N'";

        IF ($this->Edit4->Text <> "") {

            $cliente = $this->Edit4->Text;
            IF ($this->ComboBox3->ItemIndex == 1) {
                $sql .= " And r.CPFCNPJ = '$cliente'";
            }

            IF ($this->ComboBox3->ItemIndex == 2) {
                $sql .= " And r.NOME like '%$cliente%'";
            }
        }

        IF ($this->ComboBox2->ItemIndex <> -1) {
            $empregador = $this->ComboBox2->ItemIndex;
            $sql .= " And p.CODEMPREGADOR = '" . $empregador . "'";
        }

        IF ($de != '' And $ate != '') {
            $sql .= " And c.VENCIMENTO Between '" . $de . "' And '" . $ate . "'";
        }

        $sql .= " Group By c.CODOPERACAO
        Order By emp.DESCRICAO, r.NOME, c.VENCIMENTO";
        $this->qrpesquisa->close();
        $this->qrpesquisa->SQL = $sql;
        $this->qrpesquisa->open();

        $total = 0;
        IF ($this->qrpesquisa->RecordCount > 0) {
            $this->DBRepeater1->DataSource = 'dspesquisa';

            While (!$this->qrpesquisa->EOF) {
                $total += $this->qrpesquisa->VL_FACE;
                $this->qrpesquisa->next();
            }
        } Else {
            $this->DBRepeater1->DataSource = '';
        }

        IF (($this->qrpesquisa->RecordCount > 0) And (!$erro)) {
            $this->Label16->Caption = number_format($total, 2, ',', '.');
        }
    }

    function Label11BeforeShow($sender, $params)
    {
        $this->Label11->Caption = number_format($this->qrpesquisa->VL_FACE, 2, ',', '.');
    }

    function Label9BeforeShow($sender, $params)
    {
        $this->Label9->Caption = mostraData($this->qrpesquisa->VENCIMENTO);
    }

    function Button3Click($sender, $params)
    {
        if ($this->qrpesquisa->RecordCount > 0) {
            $nomeAverbador = removeEspeciaisAlterado($this->ComboBox1->Items[$this->ComboBox1->ItemIndex]);
            $caminho = "remessa/";

            $data = date('d/m/Y');
            $dia = substr($data, 0, 2);
            $mes = substr($data, 3, 2);
            $ano = substr($data, 6, 4);

            if (!file_exists($caminho)) {
                mkdir("$caminho", 0777);
            }

            if (file_exists($caminho . $nomeAverbador . $ano . $mes . $dia . '.txt')) {
                unlink($caminho . $nomeAverbador . $ano . $mes . $dia . '.txt');
            }

            $handle = fopen($caminho . $nomeAverbador . $ano . $mes . $dia . '.txt', "a");

            $this->qrpesquisa->first();
            while (!$this->qrpesquisa->EOF) {
                $mesAno = $this->qrpesquisa->VENCIMENTO;
                $mesAno = explode('-', $mesAno);
                $mesAno = $mesAno[1] . '/' . $mesAno[0];
                $nDoc = (strlen($this->qrpesquisa->NDOC)) == 3 ? substr($this->qrpesquisa->NDOC, 1, 2) : str_pad($this->qrpesquisa->NDOC, 2, '0', STR_PAD_LEFT);
                $nParcelaMaximoParcela = $nDoc . ',' . $this->qrpesquisa->NPARCELAS;

                fwrite($handle, str_pad($this->qrpesquisa->CODEMPREGADOR, 4, "0", STR_PAD_LEFT)); // identifica��o do condominio
                fwrite($handle, '10000'); //Fixo
                fwrite($handle, str_pad($this->qrpesquisa->MATRICULA, 5, '0', STR_PAD_LEFT)); //Matricula do funcionario
                fwrite($handle, $mesAno); //M�s ano
                fwrite($handle, str_pad('003048500000000001I', 29, ' ', STR_PAD_RIGHT)); //Fixo
                fwrite($handle, str_pad($nParcelaMaximoParcela, 13, ' ', STR_PAD_RIGHT)); //Numero da parcela,Total de parcelas + 8 espa�os em branco fixo
                fwrite($handle, number_format($this->qrpesquisa->VL_FACE, 2, ',', '.') . "\r\n"); //Valor Parcela

                $this->qrpesquisa->next();
            }
            fclose($handle);
        }
    }

    function Button4Click($sender, $params)
    {
        header('Location: remessa/index.php');
    }

    function Button1JSClick($sender, $params)
    {
        ?>
        //begin js
        $('#gerarremessaadmboletao').attr('target', '_self');
        $('#Button2SubmitEvent').val('');
        $('#Button6SubmitEvent').val('');
        $('#Button5SubmitEvent').val('');

        if($('#Edit3').val() == ''){
        alerta('Aten��o!', 'Favor inserir a data de vencimento do boleto.', 'erro');
        return false;
        }

        if($('#ComboBox4').val() != 'N'){
        alerta('Aten��o!', 'Favor selecionar situa��o NORMAL.', 'erro');
        return false;
        }
        return true;
        //end
        <?php
    }

    function lb_contratoBeforeShow($sender, $params)
    {
        $codEmpresta = explode('-', $this->qrpesquisa->CODEMPRESTA);
        $codEmpresta = $codEmpresta[0];
        $parcela = str_pad($this->qrpesquisa->NDOC, 3, '0', STR_PAD_LEFT);
        $this->lb_contrato->Caption = $codEmpresta . '-' . $parcela;
    }

    function Button5JSClick($sender, $params)
    {
        ?>
        //begin js
        $('#Button2SubmitEvent').val('');
        $('#Button6SubmitEvent').val('');
        $('#Button1SubmitEvent').val('');

        var cod = $('#HiddenField1').val();
        if(cod != '') {
        window.open('boleto_bradesco.php?cod='+cod, '_blank');
        }

        else {
        alerta('Aten��o!', 'Favor atualizar os contratos.', 'erro');
        }
        return false;
        //end
        <?php
    }

    function ComboBox2BeforeShow($sender, $params)
    {
        $sql = "Select e.DESCRICAO, e.CPFCNPJEMPREGADOR from averbador a
      Inner join empregador e
      On a.CODAVERBADOR = e.CODAVERBADOR
      Order By e.DESCRICAO";
        $this->qraverbador->close();
        $this->qraverbador->SQL = $sql;
        $this->qraverbador->open();

        $items[-1] = "Selecione...";

        while (!$this->qraverbador->EOF) {
            $items[$this->qraverbador->CPFCNPJEMPREGADOR] = $this->qraverbador->DESCRICAO;
            $this->qraverbador->next();
        }

        $this->ComboBox2->Items = $items;
    }

    function gerarremessaadmboletaoBeforeShow($sender, $params)
    {
        IF ($this->Edit1->Text == '' AND $this->Edit2->Text == '') {
            $this->Edit1->Text = date('d/m/Y');
            $this->Edit2->Text = date('d/m/Y');

            $this->ComboBox4->ItemIndex = 'N';
            $this->HiddenField2->Value = $this->ComboBox2->ItemIndex;
        }
    }

    function ComboBox4BeforeShow($sender, $params)
    {
        $sql = "Select * from situacaodapessoa
        Order By DESCRICAO";
        $this->qraverbador->close();
        $this->qraverbador->SQL = $sql;
        $this->qraverbador->open();

        While (!$this->qraverbador->EOF) {
            $items[$this->qraverbador->COD] = $this->qraverbador->DESCRICAO;
            $this->qraverbador->Next();
        }

        $this->ComboBox4->Items = $items;
    }

    function Label25BeforeShow($sender, $params)
    {
        $de = gravaData($this->Edit1->Text);
        $ate = gravaData($this->Edit2->Text);
        $averbador = $this->ComboBox1->ItemIndex;

        IF ($this->ComboBox2->ItemIndex <> -1) {
            $empregador = $this->ComboBox2->ItemIndex;
        }

        $this->Label25->Link = "enttitforaboletao.php?de=$de&ate=$ate&averbador=$averbador&empregador=$empregador";
    }

    function Button6Click($sender, $params)
    {
        $sql = " Select RAZAOSOCIAL, CIDADE from empresas";
        $this->qrempresa->Close();
        $this->qrempresa->SQL = $sql;
        $this->qrempresa->Open();
        $pdf = new PDF('P');
        $pdf->nomemp = $this->qrempresa->RAZAOSOCIAL;
        $pdf->ncidade = $this->qrempresa->CIDADE;
        $pdf->titulo = 'Relatório de Boletão - Período De: ' . $this->Edit1->Text . ' Até: ' . $this->Edit2->Text;
        $pdf->AddPage();
        $pdf->SetFillColor(172, 196, 242);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(.3);
        $fill = 0;
        $this->qrpesquisa->First();

        IF ($this->qrpesquisa->RecordCount > 0) {
            $codaverbador = $this->qrpesquisa->CODAVERBADOR;
            $sql = "Select NOME, CPFCNPJ from averbador
            Where CPFCNPJ = '$codaverbador'
            Order By NOME";
            $this->qraverbador->Close();
            $this->qraverbador->SQL = $sql;
            $this->qraverbador->Open();

            $fill = 1;

            $pdf->SetFont('Arial', 'B', 8);
            $w = array(190);
            $pdf->Cell($w[0], 5, 'Averbador: ' . $this->qraverbador->CPFCNPJ . ' - ' . $this->qraverbador->NOME, 1, 0, 'C', $fill);
            $pdf->Ln();

            $w = array(47, 30, 47, 26, 20, 20);
            $pdf->Cell($w[0], 5, 'Condom�nio', 1, 0, 'C', $fill);
            $pdf->Cell($w[1], 5, 'CPF/CNPJ', 1, 0, 'C', $fill);
            $pdf->Cell($w[2], 5, 'Nome', 1, 0, 'C', $fill);
            $pdf->Cell($w[3], 5, 'Contrato', 1, 0, 'C', $fill);
            $pdf->Cell($w[4], 5, 'Vencimento', 1, 0, 'C', $fill);
            $pdf->Cell($w[5], 5, 'Valor', 1, 0, 'C', $fill);
            $pdf->Ln();

            $this->qraverbador->Close();
        }

        $fill = 0;
        $totvalor = 0;
        $count = 0;
        $pdf->SetFont('Arial', '', 8);

        While (!$this->qrpesquisa->EOF) {

            $codEmpresta = explode('-', $this->qrpesquisa->CODEMPRESTA);
            $codEmpresta = $codEmpresta[0];
            $parcela = str_pad($this->qrpesquisa->NDOC, 3, '0', STR_PAD_LEFT);

            $pdf->Cell($w[0], 5, substr($this->qrpesquisa->EMPREGADOR, 0, 25), 'B', 0, 'L', $fill);
            $pdf->Cell($w[1], 5, $this->qrpesquisa->CPFCNPJ, 'B', 0, 'C', $fill);
            $pdf->Cell($w[2], 5, substr($this->qrpesquisa->NOME, 0, 25), 'B', 0, 'L', $fill);
            $pdf->Cell($w[3], 5, $codEmpresta . '-' . $parcela, 'B', 0, 'C', $fill);
            $pdf->Cell($w[4], 5, mostraData($this->qrpesquisa->VENCIMENTO), 'B', 0, 'C', $fill);
            $pdf->Cell($w[5], 5, number_format($this->qrpesquisa->VL_FACE, 2, ',', '.'), 'B', 0, 'R', $fill);
            $pdf->Ln();

            $totvalor += $this->qrpesquisa->VL_FACE;
            $count++;

            $this->qrpesquisa->Next();
        }

        $pdf->SetFont('Arial', 'B', 8);
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

    function Button2JSClick($sender, $params)
    {
        ?>
        //begin js
        $('#gerarremessaadmboletao').attr('target', '_self');
        $('#Button6SubmitEvent').val('');
        $('#Button1SubmitEvent').val('');
        $('#Button5SubmitEvent').val('');

        if($('#ComboBox1').val() == -1){
        alerta('Aten��o!', 'Favor selecionar o Averbador.', 'erro');
        return false;
        }
        return true;
        //end
        <?php
    }

    function Button6JSClick($sender, $params)
    {
        ?>
        //begin js
        $('#gerarremessaadmboletao').attr('target', '_blank');
        $('#Button2SubmitEvent').val('');
        $('#Button1SubmitEvent').val('');
        $('#Button5SubmitEvent').val('');
        return true;
        //end
        <?php
    }

    function ComboBox1JSChange($sender, $params)
    {
        ?>
        //begin js
        $('#ComboBox2').html('
        <option>Carregando...</option>');
        var cpfcnpj = 'a.CPFCNPJ = ' + document.getElementById('ComboBox1').value; //buscando empregador atraves do averbador.
        var campo1 = "e.CPFCNPJEMPREGADOR";
        var campo2 = "e.DESCRICAO";
        $.ajax({
        method: "POST",
        url: "ajx/vincativos.php",
        data: { cpfcnpj: cpfcnpj, campo1: campo1, campo2: campo2 }
        }).done(function( data ) { $('#ComboBox2').html(data); });
        //end
        <?php
    }

    function gerarremessaadmboletaoJSLoad($sender, $params)
    {
        ?>
        //begin js
        $('#ComboBox2').html('
        <option>Carregando...</option>');
        var cpfcnpj = 'a.CPFCNPJ = ' + document.getElementById('ComboBox1').value; //buscando empregador atraves do averbador.
        var campo1 = "e.CPFCNPJEMPREGADOR";
        var campo2 = "e.DESCRICAO";
        $.ajax({
        method: "POST",
        url: "ajx/vincativos.php",
        data: { cpfcnpj: cpfcnpj, campo1: campo1, campo2: campo2 }
        }).done(function( data ) {
        $('#ComboBox2').html(data);
        $('#ComboBox2').val($('#HiddenField2').val());
        });
        //end
        <?php
    }
}

function removeEspeciaisAlterado($string)
{

    // matriz de entrada
    $what = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', ' ', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', '�', '�', '.');

    // matriz de sa�da
    $by = array('A', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

    // devolver a string
    return strtolower(str_replace($what, $by, $string));
}

global $application;

global $gerarremessaadmboletao;

//Creates the form
$gerarremessaadmboletao = new gerarremessaadmboletao($application);

//Read from resource file
$gerarremessaadmboletao->loadResource(__FILE__);

$gerarremessaadmboletao->database->Connected = false;
$gerarremessaadmboletao->database->DatabaseName = $DbName;
$gerarremessaadmboletao->database->Host = $Host;
$gerarremessaadmboletao->database->UserName = $dbUserName;
$gerarremessaadmboletao->database->UserPassword = $dbPass;
$gerarremessaadmboletao->database->Connected = true;

//Shows the form
$gerarremessaadmboletao->show();

?>
<script>
    //begin js

    $(function () {

        $('#dialog-confirm p', window.parent.document).remove();
        $('#dialog-confirm', window.parent.document).removeClass();
        $('#dialog-confirm', window.parent.document).attr('class', 'ui-dialog-content ui-widget-content');
        //$('#dialog-confirm', window.parent.document).html("<label class='lb-control'>Status</label><select id='motivoDelete' class='form-control<option value='A'>Afastado</option><option value='D'>Demission�rio</option><option value='I'>Inativo</option><label class='lb-control'>Data Demiss�o:</label><input class='form-control data calendario' id='data'></input>");
        $('#dialog-confirm', window.parent.document).html("<label class='lb-control'>Status</label>" +
            "<select id='motivoDelete' class='form-control'>" +
            "<option value='A'>Afastado</option>" +
            "<option value='D'>Demission�rio</option>" +
            "<option value='I'>Inativo</option></select><br>" +
            "<label class='lb-control'>Data Demiss�o:</label>" +
            "<input class='form-control data calendario' id='data'></input>");

        $('.btexcluir').click(function () {
            var cod = $(this).find('span').html();
            var linha = $(this).parent().parent().parent().parent().parent();
            var valorParcela = $(this).parent().parent().parent().parent().find('tr td div #Label11').html();
            valorParcela = valorParcela.replace('.', '');
            valorParcela = valorParcela.replace(',', '.');
            valorParcela = parseFloat(valorParcela);
            var valorTotal = $('#Label16').html();
            var cpf = $('#Label4').html();
            valorTotal = valorTotal.replace('.', '');
            valorTotal = valorTotal.replace(',', '.');
            valorTotal = parseFloat(valorTotal);

            console.log(valorParcela + ' - ' + valorTotal);
            $('#dialog-confirm', window.parent.document).dialog({
                resizable: false,
                position: {my: "center", at: "center", of: window.parent},
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Excluir": function () {
                        var motivo = $('#motivoDelete', window.parent.document).val();
                        var data = $('#dialog-confirm #data', window.parent.document).val();
                        if (motivo != '') {
                            $.ajax({
                                type: "POST",
                                url: "ajx/excluircronogramaremessaadmboletao.php",
                                data: 'cod=' + cod + '&motivo=' + motivo + '&data=' + data + '&cpf=' + cpf,
                                success: function (data) {
                                    data = data.trim();
                                    if (data == "sucesso") {
                                        linha.fadeOut();
                                    } else {
                                        alerta('Aten��o!', 'N�o foi poss�vel excluir', 'erro');
                                    }
                                }
                            });
                            $(this).dialog("close");
                            $('#dialog-confirm #motivoDelete', window.parent.document).val('');
                            var valorTotalAtualizado = (valorTotal - valorParcela).toFixed(2);
                            valorTotalAtualizado = number_format(valorTotalAtualizado, 2, ',', '.');
                            $('#Label16').html(valorTotalAtualizado);
                        } else {
                            alerta('Aten��o!', 'Favor inserir o motivo.', 'erro');
                        }
                    },
                    "Cancelar": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });

    });


    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    //end
</script>

