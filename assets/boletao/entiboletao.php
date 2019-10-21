<?php
include('inc/conecta.php');
include('inc/script.php');
include('inc/funcoes.php');

require_once("rpcl/rpcl.inc.php");
//Includes
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("dbtables.inc.php");
use_unit("styles.inc.php");
use_unit("dbctrls.inc.php");
use_unit("db.inc.php");

//Class definition
class entiboletao extends Page
{
    public $Label7 = null;
    public $Label6 = null;
    public $HiddenField1 = null;
    public $Button3 = null;
    public $Label14 = null;
    public $Label12 = null;
    public $dspesquisa = null;
    public $qrpesquisa = null;
    public $Label9 = null;
    public $Label8 = null;
    public $Label5 = null;
    public $Label4 = null;
    public $Label3 = null;
    public $Label2 = null;
    public $DBRepeater1 = null;
    public $Shape1 = null;
    public $Label1 = null;
    public $hf_titulo = null;
    public $StyleSheet1 = null;
    public $database = null;

    function entiboletaoBeforeShow($sender, $params)
    {
      if(isset($_GET['cod'])){
        $this->HiddenField1->Value = $_GET['cod'];
        $sql = "Select i.*, s.DESCRICAO, (Select SUM(b.VALOR) From iboletao b

        Where b.CODBOLETAO = '".$_GET['cod']."' And b.CANCELADO = 'N') as TOTAL
        From iboletao i
        Left Join situacaodapessoa s
        On i.MOTIVO = s.COD
        Where i.CODBOLETAO = '".$_GET['cod']."'";
        $this->qrpesquisa->close();
        $this->qrpesquisa->SQL = $sql;
        $this->qrpesquisa->open();

        if($this->qrpesquisa->RecordCount > 0){
          $this->DBRepeater1->DataSource = 'dspesquisa';
          $this->Label7->Caption = number_format($this->qrpesquisa->TOTAL,2,',','.');
        }else{
          $this->DBRepeater1->DataSource = '';
          $this->Label7->Caption = '0,00';
        }
        $this->hf_titulo->Value = "Títulos referentes ao Boletão: ".$_GET['cod']."";
      }
    }
    function Label5BeforeShow($sender, $params)
    {
      $status = $this->qrpesquisa->CANCELADO == 'S' ? 'Cancelado' : 'Normal';
      $classe = $this->qrpesquisa->CANCELADO == 'S' ? 'st_reprovado' : 'st_analise';
      $this->Label5->Caption = $status;
      $this->Label5->Style = $classe;
    }
    function Label14BeforeShow($sender, $params)
    {
      $this->Label14->Caption = number_format($this->qrpesquisa->VALOR,2,',','.');
    }
    function Button3JSClick($sender, $params)
    {
        ?>
        //begin js
            var cod = $('#HiddenField1').val();
            window.open('boleto_bradesco.php?cod='+cod, '_blank');
            return false;
        //end
        <?php
    }
}

global $application;

global $entiboletao;

//Creates the form
$entiboletao=new entiboletao($application);

//Read from resource file
$entiboletao->loadResource(__FILE__);

$entiboletao->database->Connected = false;
$entiboletao->database->DatabaseName = $DbName ;
$entiboletao->database->Host = $Host;
$entiboletao->database->UserName = $dbUserName;
$entiboletao->database->UserPassword = $dbPass;
$entiboletao->database->Connected = true;

//Shows the form
$entiboletao->show();

?>