<?php
/**
* Vienara
*
*
* This file is licensed under the MIT license. You can use
* this project as a base for your own project, but you are
* not allowed to remove this header comment block. You may
* not use the name "Vienara" as name for your project
* either. Thanks for understanding.
*
* @version: 1.0 Beta 1 Public
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.co.cc
*
* @license MIT
*/
define('Vienara', 1);

// Setup a clean Vienara array
$vienara = array();

// Important includes
$vienara['includes'] = array(
	'Config.php',
	'Template.php',
	'xensql/XenSql.php'
);

	// Include them
	foreach($vienara['includes'] as $include)

		// Include it
		include $include;

// Connect with the database
$db = xensql_connect($db_settings['server'], $db_settings['username'], $db_settings['password'], $db_settings['dbname']);

	// Did it work?
	if(!$db)
		fatal_error('Database connection failed.');

// Retrieve the settings from the database
$result = xensql_query("
	SELECT id, value
		FROM {db_pref}settings
");
	
	// Merge them into a settings array
	foreach($result as $setting)
		$vienara['setting'][$setting['id']] = $setting['value'];

// Always load the english language file
include 'languages/english_usa.php';

// What language should we load?
if(file_exists('languages/' . $vienara['setting']['language'] . '.php'))
	include 'languages/' . $vienara['setting']['language'] . '.php';

// Are we using an RTL language?
if(!empty($vienara['lang'][$vienara['setting']['language']]['rtl']))
	$is_rtl = ' dir="rtl"';
else
	$is_rtl = '';

// This will make sure we don't get stupid errors
function show_helpstring($string = '')
{
	global $txt;

	// Is it set?
	if(!isset($txt['doc'][$string]))
		return;

	// Just display it
	return $txt['doc'][$string];
}

// Have we set an id?
if(!isset($_GET['id']))
	die();

// Get the string
$string = show_helpstring($_GET['id']);
$doc = show_helpstring('doc_' . $_GET['id']);

	// Is it empty?
	if(empty($string))
		die();

	// Nope, so show it and load the template
	template_help($doc, $string);

// Close the script
exit;
