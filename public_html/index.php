<?php
/*
// Config
*/


// errors
ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_DEPRECATED);
ini_set('display_errors', TRUE);

// locale data
setlocale(LC_ALL, 'pl_PL.utf8');
setlocale(LC_NUMERIC, 'C');

// timezone
ini_set('date.timezone', 'Europe/Warsaw');
if (function_exists('date_default_timezone_set')) { date_default_timezone_set('Europe/Warsaw'); }

// encoding
mb_internal_encoding('UTF-8');

// other
ini_set('magic_quotes_gpc', 'off');
ini_set('allow_call_time_pass_reference', 'off');

// fonts
define('FPDF_FONTPATH', 'font/');

// required includes
require_once('singleton.class.php');
require_once('lib.class.php');
require_once('fpdf.class.php');


/*
// Main program
*/

//echo '<pre>'.print_r($_SERVER, TRUE).'</pre>'; die();

$sPath = $_SERVER['QUERY_STRING'];
$aPath = explode('&', $sPath);

$bDownload = FALSE;
$sLng = 'en';

$sLng = (($aPath[0]) ? $aPath[0] : $sLng);

if ($aPath[0] == 'download') { $bDownload = TRUE; $sLng = (($aPath[1]) ? $aPath[1] : $sLng); }

switch ($sLng)
{
	case 'pl':
		require_once('cvpl.class.php');
		break;
	
	case 'de':
		require_once('cvde.class.php');
		break;
	
	case 'en':
	default:
		require_once('cven.class.php');
		break;
}

$oCV = new CV();

if ($bDownload)
{ 
	$oCV->bDownload = TRUE;
	
	$oCV->render();
	
	$oCV->Output('jakub-luczynski.cv.'.$sLng.'.pdf', 'D');
}
else
{
	$oCV->render();
	
	$oCV->Output();
}

//$oCV->Output('cv.pdf', 'D');
?>