<?php
/**
 * XenSql
 *
 * Version: 1.0
 * Copyright 2012: XenSql
 * Developed by: Dr. Deejay
 * Based on: Xensql (The Xensql development team)
 *
 * Licensed under BSD
*/

// Did we already defined Xensql?
if(!defined('Xensql'))
{
	// We need to define Xensql, so we can check if we included the file twice
	define('Xensql', 1);

	// The Xensql array is ours!! >:D
	$xensql = array();
	
	// Are we using an obsolete version? (don't count 5.2, most host still need to update)
	if(@version_compare(PHP_VERSION, '5.2.0') == -1)
		die("Sorry, Xensql requires php 5.2");

	// Setup some important information
	define('XensqlV', '1.0');						# Define the current xensql version
	
	// What kind of database type are we going to use?
	$xensql['db_type'] = 'mysqli';
	
	// Make sure it is in the array of allowed types
	$xensql['allowed_db_types'] = array(
		'mysql',
		'mysqli',
		'sqlite'
	);
	
	// Is it in the array?
	if(!in_array($xensql['db_type'], $xensql['allowed_db_types']))
		$xensql['db_type'] = 'mysqli';
	
	// What error style are we using?
	$xensql['error_style'] = 'default';
	
	// Error styles, according to the setting above
	switch($xensql['error_style']) {
		case 'sunny':
			$xensql['style_background'] = '#E0DFC3';
			$xensql['style_fonts'] = 'Verdana, Calibri, Arial';
			$xensql['style_fontsize'] = '11px';
			$xensql['style_padding'] = '5px';
			$xensql['style_border'] = '1px solid #898855';
			$xensql['style_padding'] = '5px';
			$xensql['style_extra'] = '';		
			break;
		default:
			$xensql['style_background'] = '#D8E0C3';
			$xensql['style_fonts'] = 'Verdana, Calibri, Arial';
			$xensql['style_fontsize'] = '11px';
			$xensql['style_padding'] = '5px';
			$xensql['style_border'] = '1px solid #7A8955';
			$xensql['style_padding'] = '5px';
			$xensql['style_extra'] = '';
			break;
	}
	
	// Settings
	$xensql['error_pref'] = '<div style="'.$xensql['style_extra'].' font-family: '.$xensql['style_fonts'].'; font-size: '.$xensql['style_fontsize'].'; padding: '.$xensql['style_padding'].'; border: '.$xensql['style_border'].'; background: '.$xensql['style_background'].'; margin: '.$xensql['style_padding'].';"><strong>Xensql error:</strong> ';
	$xensql['error_suff'] = '</div>';
	$xensql['error_display'] = true;
	$xensql['language'] = 'english_usa';

	// Perhaps we have the settings in a settings file
	if(file_exists('XensqlSettings.php'))
		include 'XensqlSettings.php';

	// Language vars, for localization
	$xensql['str_connect_fail'] = 'Database connection failed.';
	$xensql['str_query_fail'] = 'Query could not be executed. Please try again.';
	$xensql['str_no_direct'] = 'Please don\'t run this script directly.';		
	$xensql['str_fail_close'] = 'Failed why trying to close database connection.';
	$xensql['str_invalid_char1'] = 'Character "*" is invalid. Please only select the columns you\'re planning to use.';
	$xensql['str_count_fail'] = 'Failed to count results. Please try again.';
	$xensql['str_replacement_available'] = 'There is a replacement available for this command.'; 
	$xensql['str_no_setting'] = 'Important argument left blank (setting identifier).'; 
	$xensql['str_not_found'] = 'Setting does not exist.'; 
	$xensql['str_already_closed'] = 'Database connection already closed or no connection made.';
	$xensql['str_return'] = 'The server returned:';
	$xensql['str_use_connection'] = 'Please connect to the database before trying to execute queries.';
	$xensql['str_query_saved'] = 'The given name is already used for another query. Query wasn\'t saved';
	
	// Did we already set an unique id?
	if(isset($xensql['unique_id']))
		unset($xensql['unique_id']);
	
	// Generate an unique identifier, so the issue with existing variables reduces
	$xensql['unique_id'] = sha1(microtime().microtime().microtime());

	// Empty variables before we are starting
	$xensql['query'] = array();

	// Set settings ;)
	function xensql_ini($setting = '', $value = false)
	{
		global $xensql;
		
		// Did we accidentally forgot something?
		if(empty($setting))
			return(xensql_error($xensql['str_no_setting']));
		
		// Did we found the setting?
		if(!isset($xensql[$setting]))
			echo xensql_error($xensql['str_not_found']);
		else
			$xensql[$setting] = $value;
	}

	// No direct script access
	if(basename(__FILE__) == basename($_SERVER['PHP_SELF']))
		die($xensql['str_no_direct']);

	// Setup the connection
	function xensql_connect($xensql_server = 'localhost', $xensql_user = 'root', $xensql_pass = '', $xensql_dbname = '')
	{
		global $xensql;

		// We need some extra stuff when we don't use sqlite
		if(!$xensql['db_type'] == 'sqlite')
			if(empty($xensql_server) || empty($xensql_user) || empty($xensql_dbname))
				return false;
			
		else
			if(empty($xensql_dbname))
				return false;
		
		// Do the query functions even exist?
		if($xensql['db_type'] == 'sqlite' && !function_exists('sqlite_query'))
			return false;
		elseif($xensql['db_type'] == 'mysqli' && !function_exists('mysqli_query'))
			return false;
		elseif($xensql['db_type'] == 'mysql' && !function_exists('mysql_query'))
			return false;		

		// Check what database type we have and after that: connect
		if($xensql['db_type'] == 'mysqli')
			$xensql['connect'][$xensql['unique_id']] = @mysqli_connect($xensql_server, $xensql_user, $xensql_pass, $xensql_dbname);
		elseif($xensql['db_type'] == 'mysql') {
			$xensql['connect'][$xensql['unique_id']] = @mysql_connect($xensql_server, $xensql_user, $xensql_pass, $xensql_dbname);
			$xensql['connect'][$xensql['unique_id']] = @mysql_select_db($xensql_dbname, $xensql['connect'][$xensql['unique_id']]);
		}
		elseif($xensql['db_type'] == 'sqlite')
			$xensql['connect'][$xensql['unique_id']] = @sqlite_open($xensql_server);
		
		// Did it work?
		if($xensql['connect'][$xensql['unique_id']] == false && $xensql['db_type'] == 'mysqli')
			die(xensql_error($xensql['str_connect_fail'], mysqli_connect_error()));
		elseif($xensql['connect'][$xensql['unique_id']] == false && $xensql['db_type'] == 'mysql')
			die(xensql_error($xensql['str_connect_fail'], mysql_error()));
		elseif($xensql['connect'][$xensql['unique_id']] == false && $xensql['db_type'] == 'sqlite')
			die(xensql_error($xensql['str_connect_fail'], sqlite_error()));

		// Return DB
		return $xensql['unique_id'];
	}

	// Clean DB requests and queries
	function xensql_escape_string($string = '')
	{
		global $xensql;

		// We don't need to parse anything if there isn't anything to parse
		if(empty($string))
			return $string;

		// Use htmlspecialchars and stripslashes
		$string = htmlspecialchars($string);
	
		// Use stripslashes but make sure it works
		$result = addslashes($string);

			// So did it work?
			if($result == false)
				exit;
			else
				$string = $result;
		
		// Return the cleaned string
		return $string;
	}

	// Perhaps the user wants to convert them back
	function xensql_unescape_string($string = '')
	{
		global $xensql;

		// Is it empty?
		if(empty($string))
			return $string;

		// Time to put a real comment here ;)
		$string = htmlspecialchars_decode($string);
		$string = stripslashes($string);
		
		return $string;
	}

	// Run db queries
	function xensql_query($aquery = '', $prefix = '', $fatal = true, $save = false, $save_as = '')
	{
		global $xensql, $db_settings;

		// Set a prefix, because we would like to have cool queries
		$prefix = $db_settings['prefix'];

		// Make sure we have a connection
		if(empty($xensql['connect']) || empty($xensql['connect'][$xensql['unique_id']]))
			die(xensql_error($xensql['str_use_connection']));

		// And a unique key
		elseif(empty($xensql['unique_id']))
			die(xensql_error($xensql['str_use_connection']));

		// Set the database variable
		$db = $xensql['connect'][$xensql['unique_id']];		

		// Avax -2.0 Beta 2 compatibility for queries
		$query = str_ireplace('setup Xensql()', '', $aquery);
		
		// Make sure we use an array by default
		$results = array();

		// Replace {db_prefix} with the database prefix
		if(stripos($query, '{db_pref}'))
			$query = str_ireplace('{db_pref}', $prefix, $query);

		// Replace some extra variables too
		if(isset($_GET['id']))
			$query = str_replace('{id}', $_GET['id'], $query);
		
		// Due coding standards, we don't allow "*"
		if(!strpos($query, '*') == false)
			die(xensql_error($xensql['str_invalid_char1']));
		
		// Execute the query, using the correct sql function
		if($xensql['db_type'] == 'mysqli')
			$query_exec = @mysqli_query($xensql['connect'][$xensql['unique_id']], $query);
		elseif($xensql['db_type'] == 'sqlite')
			$query_exec = @sqlite_exec($xensql['connect'][$xensql['unique_id']], $query);
		else
			$query_exec = @mysql_query($query);
		
		// Show an error and die if it's fatal
		if(!$query_exec && $fatal == true && $xensql['db_type'] == 'mysqli')
			die(xensql_error($xensql['str_query_fail'], mysqli_error($xensql['connect'][$xensql['unique_id']])));
		elseif(!$query_exec && !$fatal == true)
			echo xensql_error($xensql['str_query_fail'], mysqli_error($xensql['connect'][$xensql['unique_id']]));
		elseif(!$query_exec && $fatal == true && $xensql['db_type'] == 'mysql')
			die(xensql_error($xensql['str_query_fail'], mysql_error()));
		elseif(!$query_exec && !$fatal == true)
			echo xensql_error($xensql['str_query_fail'], mysql_error());
		elseif(!$query_exec && $fatal == true && $xensql['db_type'] == 'sqlite')
			die(xensql_error($xensql['str_query_fail'], sqlite_error()));
		elseif(!$query_exec && !$fatal == true)
			echo xensql_error($xensql['str_query_fail'], sqlite_error());		

		// Fetch results!
		if($xensql['db_type'] == 'mysqli') {
			// Create an array with results, so we can use a foreach
			while($query_results = @mysqli_fetch_array($query_exec))
			
				// Rename the $results variable
				$results[] = $query_results;
		}
		if($xensql['db_type'] == 'sqlite') {
			// Create an array with results, so we can use a foreach
			while($query_results = @sqlite_fetch_array($query_exec))
			
				// Rename the $results variable
				$results[] = $query_results;
		}
		else {
			// Create an array with results, so we can use a foreach
			while($query_results = @mysql_fetch_array($query_exec))
			
				// Rename the $results variable
				$results[] = $query_results;
		}

		// So do we want to save it?
		if($save == true) {

			// Check if we already have a result like this
			if(isset($xensql['query'][$save_as]))
				die(xensql_error($xensql['str_query_saved'], $save_as));
			
			// Or just save it
			$xensql['query'][$save_as] = $aquery;
		}
		
		// Return results
		return $results;
	}

	// Just a simple count rows function
	function xensql_count_rows($query = '', $db = '')
	{	
		global $xensql;

		// Define a start var
		$row_count = 0;
		
		// We first need to parse it
		$result = xensql_query($query);

		// Did it work?
		if($result == true)
		
			// Count them
			foreach($result as $key)
				$row_count++;
				
		// Or didn't it?
		else
			xensql_error($xensql['str_count_fail']);
		
		// Send it back
		return $row_count;
	}
	
	// Close DB connections. 
	function xensql_stop()
	{
		global $xensql;
		
		// Did we even set it?
		if(!isset($xensql['connect'][$xensql['unique_id']]))
		{
			// Echo that we're doing something wrong
			echo xensql_error($xensql['str_already_closed']);
			
			// Leave this function
			return;
		}
		
		// Close the database connection
		$did_it_work = mysqli_close($xensql['connect'][$xensql['unique_id']]);
		
		// Did it work?
		if($did_it_work == false)
			xensql_error($xensql['str_fail_close']);
		
		// Unset the db var
		unset($xensql['connect'][$xensql['unique_id']]);
	}
	
	// Error display :P
	function xensql_error($string = '', $error_stuff = '')
	{	
		global $xensql;

		// Let's add the error prefix and suffix
		$string = $xensql['error_pref'] . $string . $xensql['error_suff'];

		// Do we have an error set? If yes, we need to use it
		if(!empty($error_stuff)) {

			// First of all, clean the error prefix and suffix
			$error = str_replace($xensql['error_pref'], '', $string);
			$error = str_replace($xensql['error_suff'], '', $error);

			// Add the error to the error
			$error = $error . ' ' . $xensql['str_return'] . ' ' . $error_stuff;

			// And now add them back
			$error = $xensql['error_pref'] . $error;
			$error = $error . $xensql['error_suff'];

			// Do we want to display errors anyway?
			if(!$xensql['error_display'] == true)
				$error = '';
		}
		
		// Or just display regular errors.
		else {

			// Do we want to show errors?
			if($xensql['error_display'] == true)
				$error = $string;
			else 
				$error = '';
		}
		
		// Return the error
		return $error;
	}
}
