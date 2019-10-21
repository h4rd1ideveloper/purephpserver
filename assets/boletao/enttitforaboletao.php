<?php

//HELTON - 11/01/2017 13:49 - CRIACAO DA TELA

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

//Includes
require_once("vcl/vcl.inc.php");
include("inc/funcoes.php");
include("inc/script.php");
require_once("inc/CalcDataHora.php");

use_unit("db.inc.php");
use_unit("dbtables.inc.php");
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("platinumgrid/jtplatinumgrid.inc.php");
use_unit("styles.inc.php");
use_unit("components4phpfull/jtsitetheme.inc.php");
use_unit("dbctrls.inc.php");

require_once("inc/conecta.php");

//Class definition
class enttitforaboletao extends Page
{
    public $Label8 = null;
    public $Label4 = null;
    public $lb_contrato = null;
    public $Label9 = null;
    public $Label6 = null;
    public $Label19 = null;
    public $Label11 = null;
    public $Label13 = null;
    public $DBRepeater1 = null;
    public $Label1 = null;
    public $Label15 = null;
    public $Label16 = null;
    public $dspesquisa = null;
    public $Label2 = null;
    public $Label5 = null;
    public $Label7 = null;
    public $Label10 = null;
    public $Label12 = null;
    public $Label18 = null;
    public $rownum = null;
    public $HiddenField1 = null;
    public $hf_titulo = null;
    public $Shape2 = null;
    public $Label3 = null;
    public $StyleSheet1 = null;
    public $qrpesquisa = null;
    public $qroperacoes = null;
    public $qrhistorico = null;
    public $dshistorico = null;
    public $database = null;

    function enttitforaboletaoBeforeShow($sender, $params)
    {
        IF(ISSET($_GET['averbador']) And (($_GET['averbador']) != '')){
            $averbador = $_GET['averbador'];

            $sql = "Select sit.CLASSE, sit.DESCRICAO As SITUACAO, emp.DESCRICAO As EMPREGADOR, c.CODCRONOGRAMA, p.NPARCELAS, c.NDOC, r.CODEMPRESTA as MATRICULA, emp.CODEMPREGADOR, c.VL_FACE, p.CPFCNPJ, r.NOME, o.CODOPERACAO As CODEMPRESTA, p.VALORFINANCIADO, c.VENCIMENTO From propostas p
            Inner Join rup r
            On r.CPFCNPJ = p.CPFCNPJ
            left Join empregador emp
            On emp.CPFCNPJEMPREGADOR = p.CODEMPREGADOR
            Inner Join operacao o
            On p.CODPROPOSTA = o.CODPROPOSTA
            Inner Join cronograma c
            On c.CODOPERACAO = o.CODOPERACAO
            left Join situacaodapessoa sit
            On c.SITUACAOPESSOACRONO = sit.COD
            Where p.CODAVERBADOR = '".$averbador."' And (p.CODSTATUS = 11 Or p.CODSTATUS = 12) And c.LIQUIDA is null
            And o.DATAACEITE > '2016-10-05' AND o.CODSTATUS = 7
            And r.SITUACAOPESSOA = 'N' And r.DESLIGADO = 'N' And r.BLOQUEADO = 'N'
            And c.SITUACAOPESSOACRONO <> 'N'";

            IF((ISSET($_GET['empregador'])) And ($_GET['empregador'] != '')) {
                $empregador = $_GET['empregador'];
                $sql .= " And p.CODEMPREGADOR = '".$empregador."'";
            }

            IF((ISSET($_GET['de']) And ($_GET['de']) != '') And (ISSET($_GET['ate']) And ($_GET['ate']) != '')) {
                $de  = $_GET['de'];
                $ate = $_GET['ate'];
                $sql .= " And c.VENCIMENTO Between '".$de."' And '".$ate."'";
            }

            $sql .= " Group By c.CODOPERACAO
            Order By emp.DESCRICAO, r.NOME, c.VENCIMENTO";
            $this->qrpesquisa->close();
            $this->qrpesquisa->SQL = $sql;
            $this->qrpesquisa->open();

            $total = 0;
            IF($this->qrpesquisa->RecordCount > 0) {
                $this->DBRepeater1->DataSource = 'dspesquisa';

                While(!$this->qrpesquisa->EOF) {
                    $total += $this->qrpesquisa->VL_FACE;
                    $this->qrpesquisa->next();
                }
            }

            Else {
                $this->DBRepeater1->DataSource = '';
            }

            IF(($this->qrpesquisa->RecordCount > 0) And (!$erro)) {
                $this->Label16->Caption = number_format($total,2,',','.');
            }
        }
    }

    function lb_contratoBeforeShow($sender, $params)
    {
        $codEmpresta = explode('-',$this->qrpesquisa->CODEMPRESTA);
        $codEmpresta = $codEmpresta[0];
        $parcela = str_pad($this->qrpesquisa->NDOC,3,'0',STR_PAD_LEFT);
        $this->lb_contrato->Caption = $codEmpresta.'-'.$parcela;
    }

    function Label9BeforeShow($sender, $params)
    {
        $this->Label9->Caption = mostraData($this->qrpesquisa->VENCIMENTO);
    }

    function Label11BeforeShow($sender, $params)
    {
        $this->Label11->Caption = number_format($this->qrpesquisa->VL_FACE,2,',','.');
    }

    function Label13BeforeShow($sender, $params)
    {
        $codcronograma = $this->qrpesquisa->CODCRONOGRAMA;
        $sql = "SELECT CONTROLE FROM iboletao WHERE CODCRONOGRAMA =".$codcronograma."
         Order By CONTROLE Desc Limit 0,1";
        $qry = mysql_query($sql);
        $ft= mysql_fetch_object($qry);
        $cod =$ft->CONTROLE;
        $codEmpresta = $this->qrpesquisa->CODEMPRESTA;
        $codEmpresta = explode('-', $codEmpresta);
        $codEmpresta = $codEmpresta[0];
        $codEmpresta .= '-'.str_pad($this->qrpesquisa->NDOC,3,'0',STR_PAD_LEFT);
        $parametro = $cod.'/'.$codEmpresta;
        $this->Label13->Caption = '<i class="fa fa-ban"><span class = "hidden">'.$parametro.'</span></i>';
    }

    function Label8BeforeShow($sender, $params)
    {
        $this->Label8->Style = $this->qrpesquisa->CLASSE;
    }
}

global $application;

global $enttitforaboletao;

//Creates the form
$enttitforaboletao = new enttitforaboletao($application);

//Read from resource file
$enttitforaboletao->loadResource(__FILE__);

$enttitforaboletao->database->Connected = false;
$enttitforaboletao->database->DatabaseName = $DbName;
$enttitforaboletao->database->Host = $Host;
$enttitforaboletao->database->UserName = $dbUserName;
$enttitforaboletao->database->UserPassword = $dbPass;
$enttitforaboletao->database->Connected = true;

//Shows the form
$enttitforaboletao->show();
?>