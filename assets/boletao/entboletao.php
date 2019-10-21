<?php
include('inc/conecta.php');
include('inc/script.php');
include('inc/funcoes.php');
require_once("rpcl/rpcl.inc.php");
use_unit("forms.inc.php");
use_unit("extctrls.inc.php");
use_unit("stdctrls.inc.php");
use_unit("dbtables.inc.php");
use_unit("styles.inc.php");
use_unit("dbctrls.inc.php");
use_unit("db.inc.php");

//Class definition
class entboletao extends Page
{
    public $qraverbador = null;
   public $HiddenField2 = null;
   public $Label14 = null;
   public $Label12 = null;
   public $dspesquisa = null;
   public $qrpesquisa = null;
   public $Label13 = null;
   public $Label11 = null;
   public $Label10 = null;
   public $Label9 = null;
   public $Label8 = null;
   public $Label7 = null;
   public $Label6 = null;
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
   public $Label15 = null;
   public $Label20 = null;
   public $Edit1 = null;
   public $Label21 = null;
   public $Edit2 = null;
   public $Button2 = null;
   public $ComboBox1 = null;
   public $Label16 = null;
   public $ComboBox2 = null;
   public $Label17 = null;

   function entboletaoBeforeShow($sender, $params)
   {
      $data = getdate();
      if($this->Edit1->Text == '' && $this->Edit2->Text == '')
      {
         $this->Edit1->Text = date('d/m/Y', $data[0]);
         $this->Edit2->Text = date('d/m/Y', $data[0]);
         $this->HiddenField2->Value = $this->ComboBox2->ItemIndex;
      }
   }

   function Label5BeforeShow($sender, $params)
   {
      $data = $this->qrpesquisa->DTHR_INCLUSAO;
      $data = explode(' ', $data);
      $data = $data[0];
      $data = mostraData($data);

      $this->Label5->Caption = $data;
   }

   function Label9BeforeShow($sender, $params)
   {
      $this->Label9->Caption = mostraData($this->qrpesquisa->VENCIMENTO_ENVIO);
   }

   function Label11BeforeShow($sender, $params)
   {
      $this->Label11->Caption = number_format($this->qrpesquisa->TOTAL, 2, ',', '.');
   }

   function Label13BeforeShow($sender, $params)
   {
      $cod = $this->qrpesquisa->CODBOLETAO;
      $this->Label13->Link = "entiboletao.php?cod=$cod";
   }

   function Label7BeforeShow($sender, $params)
   {
      $this->Label7->Caption = abreviaNome($this->qrpesquisa->USUARIO);
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

      While( ! $this->qraverbador->EOF)
      {
         $items[$this->qraverbador->CPFCNPJ] = $this->qraverbador->NOME;
         $this->qraverbador->next();
      }
      $this->ComboBox1->Items = $items;
   }

   function ComboBox1JSChange($sender, $params)
   {
      ?>
        //begin js
        $('#ComboBox2').html('<option>Carregando...</option>');
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
   function Button2Click($sender, $params)
   {
      $this->HiddenField2->Value = $this->ComboBox2->ItemIndex;

      $de = gravaData($this->Edit1->Text);
      $ate = gravaData($this->Edit2->Text);
      $sql = "Select  (Select SUM(j.VALOR) From iboletao j Where j.CODBOLETAO = i.CODBOLETAO And j.CANCELADO = 'N') as TOTAL,
        b.*, a.NOME as ADM, r.NOME as USUARIO From boletao b
        Inner Join rup r
        On r.CPFCNPJ = b.USU_INCLUSAO
        Inner Join averbador a
        On a.CPFCNPJ = b.CNPJ_ADM
        Inner Join iboletao i
        On i.CODBOLETAO = b.CODBOLETAO
        Where b.TIPO_BOLETO Is Null AND VENCIMENTO_ENVIO >= '$de' AND VENCIMENTO_ENVIO <= '$ate' ";

      if($this->ComboBox1->ItemIndex != -1) //filtro de averbador
      {
         $cpfcnpj = $this->ComboBox1->ItemIndex;
         $sql .= " AND CNPJ_ADM = '$cpfcnpj' ";
      }

      if($this->ComboBox2->ItemIndex != -1) //filtro de empregador/condominio
      {
         $cpfcnpj = $this->ComboBox2->ItemIndex;
         $sql .= " AND CNPJ_COND = '$cpfcnpj' ";
      }



      $sql .= " Group By b.CODBOLETAO Order By CODBOLETAO desc ";

      $this->qrpesquisa->close();
      $this->qrpesquisa->SQL = $sql;
      $this->qrpesquisa->open();

      if($this->qrpesquisa->RecordCount > 0)
      {
         $this->DBRepeater1->DataSource = 'dspesquisa';
      }
      else
      {
         $this->DBRepeater1->DataSource = '';
      }
   }

    function entboletaoJSLoad($sender, $params)
    {
        ?>
        //begin js
        $('#ComboBox2').html('<option>Carregando...</option>');
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

global $application;

global $entboletao;

//Creates the form
$entboletao = new entboletao($application);

//Read from resource file
$entboletao->loadResource(__FILE__);

$entboletao->database->Connected = false;
$entboletao->database->DatabaseName = $DbName;
$entboletao->database->Host = $Host;
$entboletao->database->UserName = $dbUserName;
$entboletao->database->UserPassword = $dbPass;
$entboletao->database->Connected = true;

//Shows the form
$entboletao->show();

?>