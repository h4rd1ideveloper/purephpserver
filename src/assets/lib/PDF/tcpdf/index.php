<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once('tcpdf_include.php');

$numcontrato="1234567-000";

$titulo ='Carimbo do tempo contrato:'.$numcontrato;

$palavraschaves = "";
$logoempresa="logo.svg";
$nomearquivo='CCB-COMPLETA-012103.pdf';

$codigodocumento = '#6e8a416a-c9e5-4207-bd72-0418914e8b30';
$assinaturacliente="assinatura-temp.png";

$clientenome="Teste Cliente da Silva moura fransisco ";
$clientedocumento = "000.222.333-32";

$codigodocumento = "#". hash_file('md5', $nomearquivo);
$docsha256= hash_file('sha256', $nomearquivo);
$docsha512 = hash_file('sha512', $nomearquivo);


$linhadotempo='20 Mar 2017, 17:20:20<br>
Documento n�mero 6e8a416a-c9e5-4207-bd72-0418914e8b30 criado por RPW SOCIEDADE DE CREDITO AO MICROEMPREENDEDOr . Email :contato@rbmweb.com.br. CNPJ: 00.000.000/000-00
<br><br>
20 Mar 2017, 17:22:08<br>
Lista de assinatura iniciada por RPW SOCIEDADE DE CREDITO AO MICROEMPREENDEDOr.
Email :contato@rbmweb.com.br. CNPJ: 00.000.000/000-00<br><br>

20 Mar 2017, 17:23:43<br>
RICARDO ASSAF Assinou (Conta #0100ee94-33db-4f60-9c99-e088886c06d8). Email:ricardo@rbmweb.com.br. IP: 200.141.148.154 (200-141-148-154.user.veloxzone.com.br). Geolocaliza��o:
-21.531803 -42.6411027. Documento de identifica��o informado: 998.338.916-91.<br><br>

20 Mar 2017, 17:25:15<br>
Pedro Pereira Sousa Assinou (Conta #56cdbc74-a43a-4541-af8d-0a04a02afa11). Email: pedro@hotmail.com.br. IP:
177.92.62.53 (53.62.92.177.dynamic.copel.net). Documento de identifica��o informado: 05692669986.';



// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// set bacground image
		$img_file = 'fundo.svg';
		//$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		$this->ImageSVG($img_file, 0, 0, 210, 297, '', '', '', 0, false);

		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		//$this->setPageMark();
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Certificado e assinatura digital
$dir= 'file://'.realpath(dirname(__FILE__));

$info = array(
	'Name' => 'CARLOS EDUARDO NAVARRO RIBEIRO:04741738957',
	'Location' => 'MARINGA, PR, BRASIL',
	'Reason' => 'CCB EMITIDA PELA CREFAZ SCM',
	'ContactInfo' => 'http://www.crefaz.com.br',
	);

$certificate = $dir.'/cadu.crt';
$chave= $dir.'/cadu.key';
//echo $chave; exit;
//setSignature( $signing_cert = '', $private_key = '', $private_key_password = '', $extracerts = '', $cert_type = 2, $info = array(), $approval = '' )
$pdf->setSignature($certificate, $chave, 'crefaz3013', '', 1, $info);
/*
$certificate = $dir.'/danilo.crt';
$chave= $dir.'/danilo.key';
//setSignature( $signing_cert = '', $private_key = '', $private_key_password = '', $extracerts = '', $cert_type = 2, $info = array(), $approval = '' )
$pdf->setSignature($certificate, $chave, 'crefaz9706', '', 1, $info);
*/

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('CREFAZ SCM');
$pdf->SetTitle($titulo);
$pdf->SetSubject($titulo);
$pdf->SetKeywords($palavraschaves);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/bra.php')) {
	require_once(dirname(__FILE__) . '/lang/bra.php');
	$pdf->setLanguageArray($l);
}



// ---------------------------------------------------------
$pdf->SetFont('helvetica', '', 9);


//Adiciona Logomarca



// add a page
$pdf->AddPage();
 $pdf->SetTextColor(50,50,50);
$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)));
$pdf->SetFillColor(255,255,128);

$pdf->ImageSVG($file=$logoempresa, $x=20, $y=20, $w='48', $h='11', $link='', $align='', $palign='', $border=0, $fitonpage=false);

// NOME: EMPRESTA SCM
//CPF/CNPJ: RPW SCM EPP S/A. CNPJ: 06.249.129/0001-14

$data = date('d/m/Y H:i-s');
$pdf->SetX(70);
$pdf->SetY(20);
$txt = utf8_encode("Datas e hor�rios baseados em Bras�lia, Brasil<br>
Sincronizado com o NTP.br e Observat�rio Nacional (ON)<br>
Certificado de assinaturas gerado em $data");

$pdf->Cell(55, 15, "", 0, 0, 'R', 0);

$pdf->writeHTMLCell(92, 0, '', '', $txt, 0, 0, 0, true, 'R', true);
// - - - - - - - - - - - - - - - - - - -
$pdf->Ln();

$pdf->writeHTMLCell(190, 11,'', '', '', 0, 0, 0, true, 'R', true);
$pdf->Ln();

 $pdf->SetTextColor(40,59,155);
 $pdf->SetFont('helvetica', '', 17);
 $pdf->writeHTMLCell(180, 0, '', '', $nomearquivo, 0, 0, 0, true, 'C', true);
 $pdf->Ln();
 $pdf->SetTextColor(50,50,50);
 $pdf->SetFont('helvetica', '', 9);
 $pdf->writeHTMLCell(180, 17, '', '', utf8_encode('C�digo do documento '.$codigodocumento), 0, 0, 0, true, 'C', true);
 $pdf->Ln();

$pdf->SetTextColor(50,50,50);
$pdf->SetFont('helvetica', '', 9);

$txt = utf8_encode("<b>".$clientenome."</b><br>".$clientedocumento);

$pdf->Cell(55, 32, "", 0, 0, 'R', 0);
  $pdf->Ln();
$pdf->writeHTMLCell(87, 0, '', '', '<b>RPW SCM EPP S/A</b><br>06.249.129/0001-14', 0, 0, 0, true, 'C', true);
$pdf->writeHTMLCell(87, 0, 106, '', $txt, 0, 0, 0, true, 'C', true);


$pdf->Cell(55, 28, "", 0, 0, 'R', 0);
$pdf->Ln();
$pdf->writeHTMLCell(171, 0, 19, '', utf8_encode($linhadotempo), 0, 0, 0, true, 'L', true);
$pdf->Ln();
$pdf->writeHTMLCell(171, 10, 19, '', '', 'B', 0, 0, true, 'C', true);

$pdf->Ln();

$pdf->Cell(55, 6, "", 0, 0, 'R', 0);
$pdf->Ln();

$pdf->writeHTMLCell(171, 0, 19, '', utf8_encode('Hash do documento original<br>(SHA256):'.$docsha256.'<br>(SHA512):'.$docsha512.'<br><br>Esse log pertence �nica e exclusivamente aos documentos de HASH acima'), 0, 0, 0, true, 'L', true);


$pdf->writeHTMLCell(171, 0, 19, 267, utf8_encode('Esse documento est� assinado e certificado pela RPW SCMEPP'), 0, 0, 0, true, 'C', true);

///Assiaturas

//$pdf->Image('assinatura-ricardo.png', 20, 65, 75, 0, '', '', '', true, 300);
//$pdf->Image('assinatura-ricardo.png', 17, 65, 85, 0, 'PNG');
 /////
 //////////  CROP na assinatura e ajuste pela largura e altura

///**********
/*
$im = imagecreatefrompng($assinaturacliente);

imagepng($im, "assinatura-temp.png");
imagedestroy($im);


////
 $tam = getimagesize('assinatura-temp.png');

if($tam[0]< ($tam[1]*3)){
	$pdf->Image('assinatura-temp.png', 113, 74, 0, 25, '', '', 1, true, 300);
}else{
	$pdf->Image('assinatura-temp.png', 113, 77, 68, 0, '', '', 1, true, 300);
}
 unlink('assinatura-temp.png');

 ***********
 */
//$pdf->Image('assinatura-temp.png', 113, 77, 68, 0, '', '', 1, true, 300);


 $pdf->setSignatureAppearance(20, 65, 75, 35);
 //$pdf->addEmptySignatureAppearance(20, 65, 75, 35);


//Close and output PDF document
//$pdf->Output('example_051.pdf', 'I');

$pdf->Output('example_cer.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+


