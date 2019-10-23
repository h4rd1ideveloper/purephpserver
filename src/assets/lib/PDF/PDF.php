<?php


namespace App\assets\lib\PDF;


use TCPDF;

require_once 'fpdf/fpdf.php';
require_once 'tcpdf/config/tcpdf_config_alt.php';
require_once 'tcpdf/TCPDF.php';

class PDF extends TCPDF
{
    public $nomemp;
    public $ncidade;
    public $titulo;

    function Header()
    {
        $this->SetFont('Times', 'B', 8);
        $this->Image('img/logoempresa.jpg', 10, 10, 21, 11, 'JPEG');

        $w = array(30, 130, 30);
        $this->Cell($w[0], 5, '', 0, 0, 'L');
        $this->Cell($w[1], 5, 'WEBAGENTE - Sistema de Gestão para MICROCRÉDITO - ONLINE', 0, 0, 'L');
        $this->Cell($w[2], 5, 'Pag.: ' . $this->PageNo(), 0, 0, 'R');
        $this->Ln();

        $this->Cell($w[0], 5, '', 0, 0, 'L');
        $this->Cell($w[1], 5, $this->titulo, 0, 0, 'L');
        $this->Cell($w[2], 5, 'Emissão: ' . date('d/m/Y - H:i:s'), 0, 0, 'R');
        $this->Ln();

        $w = array(30, 160);
        $this->Cell($w[0], 5, '', 'B', 0, 'L');
        $this->Cell($w[1], 5, 'Empresa: ' . $this->nomemp . ' - ' . $this->ncidade, 'B', 0, 'L');
        $this->Ln(10);
    }
}
