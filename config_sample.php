<?php
/******************************************************************************
 * Konfigurationsdatei für das Admidio-Plugin Beitrittserklärung
 *
 * Copyright    : (c) 2013 Roman Krapf
 * Homepage     : -
 * Module-Owner : Roman Krapf
 * License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 * Version		: 1.1
 *
 *****************************************************************************/

// usf_id's from the table adm_user_fields in the order you need to show in the pdf
$sortarray = array(1,2,3,4,5,7,8,10,11,12);

// pdf font (Courier, Helvetica, Times or Zapfdingbats)
$pdffont = 'Helvetica';
$pdffontsizetext = 10;
$pdffontsizesubtitle = 14;
$pdffontsizetitle = 18;

// the picture is refered from the folder generate_pdf
$pdflogo = '../../../adm_themes/modern/images/admidio_logo_20.png';
$pdflogowidth = 40;

// free text for the pdf
$pdftitel = 'Beitrittserklärung Admidio';
$pdfsubtitle = 'Personalien des Mitglieds:';
$pdftextintroduction = 'Die erziehungsberechtigte Person gibt hiermit die Einwilligung zum Eintritt in Admidio.';
$pdftextconstitution = 'Mit ihrer Unterschrift akzeptiert die erziehungsberechtigte Person die Statuten von Admidio';
$pdftextguarantee = 'Die Versicherung für Unfall und Haftpflicht ist Sache des Mitglieds.';
$pdflocationdate = 'Ort, Datum:';
$pdfsignature = 'Unterschrift:';
$pdffilename = 'Beitrittserklärung';	// Output: BeitrittserklärungNameVorname.pdf

?>