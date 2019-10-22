<?php
//============================================================+
// File name   : tcpdf_include.php
// Begin       : 2008-05-14
// Last Update : 2013-05-14
//
// Description : Search and include the TCPDF library.
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Search and include the TCPDF library.
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Include the main class.
 * @author Nicola Asuni
 * @since 2013-05-14
 */

// always load alternative config file for examples
require_once('config/tcpdf_config_alt.php');

// Include the main TCPDF library (search the library on the following directories).
$tcpdf_include_dirs = array(realpath('TCPDF.php'),realpath('TCPDF.php'), '../../TCPDF.php', '/usr/share/tcpdf/TCPDF.php', '/usr/share/php-tcpdf/TCPDF.php', '/var/www/tcpdf/TCPDF.php', '/var/www/html/tcpdf/TCPDF.php', '/usr/local/apache2/htdocs/tcpdf/TCPDF.php');
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
}

//============================================================+
// END OF FILE
//============================================================+
