<?php
/******************************************************************************
 * Generate PDF
 *
 * Copyright    : (c) 2013 Roman Krapf
 * Homepage     : -
 * Module-Owner : Roman Krapf
 * License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 * Version		: 1.1
 *
 * Uebergaben:
 *
 * user_id: ID from user with has to be exported
 *
 *****************************************************************************/

//get user_id from shown user
$user_id = $_POST['user_id'];

// if user_id isn't a number, go back to home
if (!ctype_digit($user_id))
{
	header( 'Location: '.$g_root_path);
}

// create path to plugin
$plugin_folder_pos = strpos(__FILE__, 'adm_plugins') + 11;
$plugin_file_pos   = strpos(__FILE__, 'generate_pdf/createpdf.php');
$plugin_folder     = substr(__FILE__, $plugin_folder_pos+1, $plugin_file_pos-$plugin_folder_pos-2);

if(!defined('PLUGIN_PATH')) 
{
    define('PLUGIN_PATH', substr(__FILE__, 0, $plugin_folder_pos));
}

// include required php-files
require('../library/tfpdf.php');
require_once(PLUGIN_PATH. '/../adm_program/system/common.php');
require_once(PLUGIN_PATH. '/'.$plugin_folder.'/config.php');



// set database to admidio, sometimes the user has other database connections at the same time
$gDb->setCurrentDB();

// get the userdata from the database
$sql = 'SELECT usd_usf_id, usf_name, usd_value, usf_value_list, usf_type 
			FROM adm_user_data as user_data 
			JOIN adm_user_fields as user_fields 
				ON user_fields.usf_id = usd_usf_id 
				AND user_data.usd_usr_id='.$user_id.'
		ORDER BY usd_usf_id';
$result = mysql_query($sql) OR die(mysql_error());

// create an array with the userdata
while ($row = mysql_fetch_assoc($result))
{
   $id = $row['usd_usf_id'];
   $daten[$id] = $row;
   
   // search lastname for filename
   if($daten[$id]['usf_name']=='SYS_LASTNAME')
   {
   		$lastname=$daten[$id]['usd_value'];
   }
   
   // search firstname for filename
   if($daten[$id]['usf_name']=='SYS_FIRSTNAME')
   {
   		$firstname=$daten[$id]['usd_value'];
   }
   
   // search for SYS-strings and replace it with the string from the language xml
   $stringposname = strpos($daten[$id]['usf_name'],'SYS');
   if(is_numeric($stringposname))
   {
   		$sysbezeichnung = trim(substr($daten[$id]['usf_name'],$stringposname));
   		$sysbezeichnung = $gL10n->get($sysbezeichnung);
   		$daten[$id]['usf_name'] = $sysbezeichnung;
   		
   }
   
   // set the correct format for the date
  if($daten[$id]['usf_type'] == 'DATE')
   {
   		$dateFormat = $gPreferences['system_date'];
		$date = new DateTimeExtended($daten[$id]['usd_value'], 'Y-m-d', 'date');
		$value = $date->format($dateFormat);
		$daten[$id]['usd_value'] = $value;
   }
}

// sort data in configured order
$sorted = sortMyArray($daten,$sortarray);

$pdf = new tFPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetMargins(25,25);
$pdf->SetFont($pdffont,'B',$pdffontsizetitle);
$pdf->SetX(25);
$pdf->SetY(25);
$pdf->MultiCell(120,16.5," ");
$pdf->MultiCell(120,10,utf8_decode($pdftitel));
$pdf->Image($pdflogo,145,25,$pdflogowidth);
$pdf->Ln();
$pdf->SetFont($pdffont,'',$pdffontsizetext);
$pdf->MultiCell(162,5,utf8_decode($pdftextintroduction));
$pdf->MultiCell(162,5,utf8_decode($pdftextconstitution));
$pdf->MultiCell(162,5,utf8_decode($pdftextguarantee));
$pdf->Ln();
$pdf->SetFont($pdffont,'B',$pdffontsizesubtitle);
$pdf->Cell(10,10,utf8_decode($pdfsubtitle));
$pdf->Ln();
$pdf->SetFont($pdffont,'',$pdffontsizetext);

foreach($sorted as $zeile)
{
	if(isset($zeile['usf_value_list']))
	{
		$zeile['usf_value_list'] = explode("\n", $zeile['usf_value_list']);
		$stringpos = strpos($zeile['usf_value_list'][$zeile['usd_value']-1],'SYS');
		$wert = $zeile['usf_value_list'][$zeile['usd_value']-1];
		if($stringpos)
		{
			$sysbezeichnung = trim(substr($zeile['usf_value_list'][$zeile['usd_value']-1],$stringpos));
			$wert = $gL10n->get($sysbezeichnung);
			
		}
		$inhalt = $zeile['usf_name'].': '.$wert;
	}
	else
	{
		$inhalt = $zeile['usf_name'].': '.$zeile['usd_value'];
	}
  	$pdf->MultiCell(0,5,utf8_decode($inhalt),0,1);
}

$pdf->SetY(260);
$pdf->Cell(20,10,$pdflocationdate);
$pdf->Line(45,270,95,270);
$pdf->SetX(110);
$pdf->Cell(20,10,$pdfsignature);
$pdf->Line(130,270,190,270);
$pdf->Output($pdffilename.$lastname.$firstname.'.pdf','D');

function sortMyArray ($daten, $sortarray)
{
	$found = false;
	$countvalue = 0;
	$sorted = array();
	foreach ($sortarray as $sortnumber)
	{
		foreach ($daten as $datenzeile)
		{
			if($datenzeile['usd_usf_id'] == $sortnumber)
			{
				$found = true;
				$sorted[$countvalue] = $daten[$sortnumber];
				break;
			}
		}
		if($found)
		{
			$found = false;
		}
		else
		{
			unset($sortarray[$countvalue]);
		}
		$countvalue++;
	}
	
	return $sorted;
}
?>