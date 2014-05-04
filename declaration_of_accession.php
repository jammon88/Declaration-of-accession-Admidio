<?php
/******************************************************************************
 * Beitrittserklärung
 *
 * Beitrittserklärung bietet für die Benutzer mit dem Recht "Profildaten aller
 * Benutzer bearbeiten" einen Button, mit welchem man eine Beitrittserklärung
 * als PDF anzeigen lassen kann. Dieser Button erscheint, sobald man die
 * Profildaten eines Benutzers anzeigen lässt 
 * (adm_program/modules/profile/profile.php?user_id=xx).
 *
 * Compatible with Admidio version 2.4.2
 *
 * Copyright    : (c) 2013 Roman Krapf
 * Homepage     : -
 * Module-Owner : Roman Krapf
 * License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 * Version		: 1.1
 *
 *****************************************************************************/

// create path to plugin
$plugin_folder_pos = strpos(__FILE__, 'adm_plugins') + 11;
$plugin_file_pos   = strpos(__FILE__, 'declaration_of_accession.php');
$plugin_folder     = substr(__FILE__, $plugin_folder_pos+1, $plugin_file_pos-$plugin_folder_pos-2);

if(!defined('PLUGIN_PATH'))
{
    define('PLUGIN_PATH', substr(__FILE__, 0, $plugin_folder_pos));
}
require_once(PLUGIN_PATH. '/../adm_program/system/common.php');

// Sprachdatei des Plugins einbinden
$gL10n->addLanguagePath(PLUGIN_PATH. '/'.$plugin_folder.'/languages');

$url = $_SERVER['REQUEST_URI'];
if(isset($_REQUEST['user_id']))
{
	$user_id = $_REQUEST['user_id'];
}

if(strstr($url, 'adm_program/modules/profile/profile.php?user_id=')!=null && $gCurrentUser ->editUsers())
{
	echo '<div id="plugin_'. $plugin_folder. '" class="admPluginContent">
		<div class="admPluginHeader">';
	echo '<h3>'.$gL10n->get('PLG_DECLARATION_PLUGIN_TITLE').'</h3>';
	echo '</div>
		<div class="admPluginBody">';
	echo '<form action="../../../adm_plugins/'.$plugin_folder.'/generate_pdf/createpdf.php" method="post">
			<input type="hidden" name="user_id" value="'.$user_id.'">
			<input type="submit" name="printPDF" value="'.$gL10n->get('PLG_DECLARATION_PRINT_BUTTON').'">
		</form>';
	echo '</div></div>';
}

?>