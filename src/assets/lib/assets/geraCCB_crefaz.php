<?php
setlocale(LC_ALL, "pt_BR");
error_reporting(E_ALL);
ini_set('display_errors', 1);
//ini_set('display_errors',1);ini_set('display_startup_erros',1);error_reporting(E_ALL);
$debug = 1;


//require_once("../../webagente+/conectaapp.php");  //oficial
require_once("../../webagente+/inc/funcoes.php"); // oficial

require_once("../../webagente+/inc/conectaapp.php"); // teste


include('../fpdf/fpdf.php');


require_once('../tcpdf/config/tcpdf_config_alt.php');
// Include the main TCPDF library (search the library on the following directories).
$tcpdf_include_dirs = array(realpath('../tcpdf/TCPDF.php'));
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
    if (@file_exists($tcpdf_include_path)) {
        require_once($tcpdf_include_path);
        break;
    }
}


//Class definition
class MYPDF extends TCPDF
{
    public $nomemp;
    public $ncidade;
    public $nuf;
    public $data1;
    public $data2;
    public $titulo;
    public $oper;
    public $nomecli;
    public $cpfcnpj;
    public $Via = 0;
    public $assinatura;
    public $assinaturaempresa;
    public $proposta;

    function Header()
    {

    }
}

$codProposta = isset($_GET['p']) ? $_GET['p'] : $_GET['codop'];
//$codProposta = '379756';
if (isset($codProposta)) {

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'ISO-8859-1', false);
    $pdf->SetMargins(10, 15, PDF_MARGIN_RIGHT, 0);//10 20
    $pdf->AddPage();

    //Colors, line width and bold font
    $pdf->SetFillColor(255, 255, 225);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(.3);
    $fill = 0;


    $codProposta = isset($_GET['p']) ? $_GET['p'] : $_GET['codop'];
    $sql = "Select CODOPERACAO from operacao Where CODPROPOSTA = $codProposta ";//$cod

    $query_op = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($query_op) > 0) {
        $campos_op = mysql_fetch_array($query_op);
        //$codop = (int)$campos_op['CODOPERACAO'];
        $codop = $campos_op['CODOPERACAO'];
    }


    include("relfichacadastral.php");
    include("relccbnegociavel.php");
    include("relccbnaonegociavel.php");
    include("relnotificacaocessao.php");

    ob_clean();

    if (file_exists($pdf->assinatura)) {

        $dir = 'file:///var/www/html/home/crefaz/certificados/';
        //$dir= 'file://'.realpath(dirname(__FILE__)).'/certificado/teste/';
        //echo $dir; exit;
        $info = array(
            'Name' => 'CARLOS EDUARDO NAVARRO RIBEIRO:04741738957',
            'Location' => 'MARINGÁ, PR, BRASIL',
            'Reason' => 'CCB EMITIDA PELA CREFAZ SCM',
            'ContactInfo' => 'http://www.crefaz.com.br',
        );

        // *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(180, 280, 15, 15);

        $certificate = $dir . 'cadu.crt';
        $chave = $dir . 'cadu.key';
        //setSignature( $signing_cert = '', $private_key = '', $private_key_password = '', $extracerts = '', $cert_type = 2, $info = array(), $approval = '' )
        $pdf->setSignature($certificate, $chave, 'crefaz3013', '', 1, $info);
        $nomeccb = 'CCB_' . $codop . '_cert.pdf';
    }
    $targetDir = "../../adm-CP/propostas/" . $codProposta . "/";

    $pdf->Output($targetDir . $nomeccb, 'F');

    chmod($targetDir . $nomeccb, 0775);

    $data = date("Y-m-d");
    $hora = date("H:i:s");

    $sql = "DELETE From propostasdocs Where CODPROPOSTA = '" . $codProposta . "' And CATEGORIA = 'CCB FINAL'";
    mysql_query($sql);

    $sql = "Select * From propostasdocs Where CODPROPOSTA = '" . $codProposta . "' And NOMEARQUIVO = '.$nomeccb.'";
    $query_verifica_contrato = mysql_query($sql);


    if (mysql_num_rows($query_verifica_contrato) == 0) {
        mysql_query("INSERT INTO propostasdocs (CODPROPOSTA,NOMEARQUIVO,DATA,HORA,CATEGORIA) VALUES ('" . $codProposta . "', 'CCB_" . $codop . "_cert.pdf', '" . $data . "', '" . $hora . "', 'CCB FINAL')");


        $url = "https://www.crefaz.com.br/teste/mobile/consignado/carimbo/carimbo.php?p=" . $codProposta;
        $header = array();
        $cabecalho = apache_request_headers();
        array_push($header, "ip:" . $cabecalho['ip']);
        requestApi('POST', $url, array(), $header);


        return '{"retorno":"Sucesso",mensagem:"CCB gerada com sucesso"}';
    }
    return '{"retorno":"erro",mensagem:"A CCB não pode ser gerada"}';
}

function requestApi($method, $url, $data, $header)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($ch);
    return $response;
}


?>
