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
* @version: 1.0 Alpha 1
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.co.cc
*
* @license MIT
*/

// Make sure header redirects work
ob_start();

// We don't like hacking attempts
define('Vienara', 1);

// Set magic quotes to 0
ini_set('magic_quotes_runtime', 0);

// Useful information will be provided below
define('Blog_Branch', '1.0');

// Variables that we need later
$vienara = array();
$user = array();

// Settings!
$vienara['install_file'] = 'install.php';

// No html ;)
foreach($_POST as $key => $value)
	$_POST[$key] = htmlspecialchars($_POST[$key]);

// The upper template.
function vienara_header()
{
	global $vienara, $user;

	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		body {
			background: #D6D6D6;
			font-size: 12px;
			font-family: Verdana, Arial, Helvetica;
		}
		a, a:visited, a:hover {
			text-decoration: none;
			color: #76A744;
		}
		#wrapper {
			width: 50%;
			margin: auto;
			border: 1px solid grey;
			background: white;
			min-width: 400px;
			padding: 10px;
		}
		.padding {
			padding: 5px;
		}
		.bg_color {
			background: #89BE51;
		}
		.bg_color2 {
			background: #97D159;
		}
		.bg_color3 {
			background: #BBE88B;
		}
		.bg_color4 {
			background: #F8FFF6;
		}
		.bg_color5 {
			background: #E8ECE6;
		}
		.db_border {
			padding: 1px;
			border: 1px solid dimgrey;
		}
		#header {
			padding: 15px;
			color: white;
			font-size: 50px;
		}
		.cat_bg {
			color: white;
			font-weight: bold;
			padding: 5px;
		}
		table.td_border_bottom tr td {
			border-bottom: 1px solid grey;
		}
		.sec_nav a {
			color: white;
			font-weight: normal;
			margin-right: 10px;
		}
		.copyright {
			text-align: center;
			font-size: 11px;
		}
		hr {
			border: 0;
			border-top: 1px solid grey;
		}
		.act_buttons {
			font-weight: bold;
			float: right;
		}
		.act_buttons a {
			margin-right: 10px;
		}
		.clear {
			clear: both;
		}
		.editor {
			padding: 5px;
			border: 1px solid #A4D371;
			font: inherit;
			width: 70%;
			height: 150px;
		}
		input[type="text"], input[type="password"] {
			border: 1px solid grey;
		}
		input[type="submit"] {
			background: #A7C78C url(./images/bg_color.png) repeat-x;
			border-radius: 5px;
			padding: 5px;
			width: 100px;
			color: white;
			border: 1px solid #639E48;
			font-weight: normal;
			font-family: Verdana, Arial;
			font-size: 12px;
		}
		tr.board_upper td {
			background: #8BD568;
			padding: 5px;
		}
		tr.board_upper td.right {
			border-top-right-radius: 10px;
		}
		tr.board_upper td.left {
			border-top-left-radius: 10px;
		}
		.radius {
			border-radius: 10px;
			padding: 10px;
			margin: 10px;
		}
		.user_buttons {
			float: right;
			font-size: 11px;
			color: dimgrey;
		}
		.user_buttons a {
			margin-right: 10px;
			color: dimgrey;
		}
	</style>
	<title>Vienara installation</title>
</head>
<body>
	<div id="wrapper">
		<div id="header" class="bg_color">
			Vienara installation
		</div>';
}

// And the footer
function vienara_footer()
{
	global $vienara;

	echo '
	</div>
	<div class="copyright">Powered by Vienara</div>
</body>
</html>';
}

// Die nice. Perhaps a fatal error or something
function die_nice($message = '')
{
	// Echo the message
	echo $message;

	// Show the footer
	vienara_footer();

	// And die
	die;
}

// This will show the main installation page
function install_main()
{
	global $vienara;

	// Show some pretty information.
	echo '
		<div class="padding">
			Hello! Welcome to the installation of <strong>Vienara</strong>!
			You are about to install Vienara ' . Blog_Branch . '. Thanks
			for choosing for our software. The
			installation shouldn\'t be hard and only requires a few minutes. Maybe just a
			few seconds. Please fill in the fields below and you are ready to begin the
			installation. Good luck and enjoy!
			<hr />
			<form action="' . $vienara['install_file'] . '?step=2" method="post">
				<table width="100%" cellpadding="5" cellspacing="0">
					<tr class="cat_bg bg_color">
						<td class="cat_bg bg_color" width="50%">Setting</td>
						<td class="cat_bg bg_color" width="50%">Value</td>						
					</tr>
					<tr>
						<td class="bg_color5" width="50%"><strong>Database server:</strong></td>
						<td class="bg_color4" width="50%"><input type="text" name="db_server" value="localhost" /></td>
					</tr>
					<tr>
						<td class="bg_color5" width="50%"><strong>Database username:</strong></td>
						<td class="bg_color4" width="50%"><input type="text" name="db_user" value="" /></td>
					</tr>
					<tr>
						<td class="bg_color5" width="50%"><strong>Database password:</strong></td>
						<td class="bg_color4" width="50%"><input type="password" name="db_password" value="" /></td>
					</tr>
					<tr>
						<td class="bg_color5" width="50%"><strong>Database name:</strong></td>
						<td class="bg_color4" width="50%"><input type="text" name="db_name" value="" /></td>
					</tr>
					<tr>
						<td class="bg_color5" width="50%"><strong>Database prefix:</strong></td>
						<td class="bg_color4" width="50%"><input type="text" name="db_prefix" value="vienara_" /></td>
					</tr>
					<tr class="cat_bg bg_color">
						<td class="cat_bg bg_color" colspan="2">Creating administrator password</td>					
					</tr>
					<tr>
						<td class="bg_color5" width="50%"><strong>Password:</strong></td>
						<td class="bg_color4" width="50%"><input type="password" name="password" value="" /></td>
					</tr>
					<tr>
						<td colspan="2"><input type="submit" value="Submit" /></td>					
					</tr>
				</table>
			</form>
		</div>';
}

// A list of steps
$available_steps = array(2);

// Step 2. Insert everything into the database and connect.
function install_step_2()
{
	global $vienara;

	// Did we posted anything?
	if(!isset($_POST['db_server']))
		die_nice('Bad request!');

	// Check if everything is filled in
	$vienara['important_fields'] = array(
		'db_server', 'db_user', 'db_password', 'db_name',
		'db_prefix', 'password'
	);

		// These fields shouldn't be empty
		foreach($vienara['important_fields'] as $field) {

			// Empty?
			if(empty($_POST[$field]) && !$field == 'db_password')
				die_nice('Please fill in the following field: ' . $field);

			// We don't like sql injections
			$_POST[$field] = addslashes($_POST[$field]);
		}
	
	// Try to connect with the database
	$db = mysqli_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_password'], $_POST['db_name']);

		// Did it work?
		if(!$db)
			die_nice('Database connection failed.');
	
	// Hash the password
	$_POST['password'] = sha1($_POST['password']);

	// Insert everything into the database
	$query = file_get_contents('install.sql');

		// Replace the database prefix
		$query = str_replace('{db_pref}', $_POST['db_prefix'], $query);

		// Perhaps we need to create a database
		$query = str_replace('{db_name}', $_POST['db_name'], $query);
		
		// Give the correct time
		$query = str_replace('{cur_time}', time(), $query);

		// Set the username correct
		$query = str_replace('{user_password}', $_POST['password'], $query);

		// Execute the queries
		mysqli_multi_query($db, $query);

	// Update index.php
	$file = file_get_contents('./Config.php');

	// Get the existing configuration settings
	include 'Config.php';

		// Replace important stuff
		$file = str_replace("'server' => '" . $db_settings['server'] . "',", "'server' => '" . $_POST['db_server'] . "',", $file);
		$file = str_replace("'username' => '" . $db_settings['username'] . "',", "'username' => '" . $_POST['db_user'] . "',", $file);
		$file = str_replace("'password' => '" . $db_settings['password'] . "',", "'password' => '" . $_POST['db_password'] . "',", $file);
		$file = str_replace("'dbname' => '" . $db_settings['dbname'] . "',", "'dbname' => '" . $_POST['db_name'] . "',", $file);
		$file = str_replace("'prefix' => '" . $db_settings['prefix'] . "'", "'prefix' => '" . $_POST['db_prefix'] . "'", $file);

		// Update the file
		file_put_contents('./Config.php', $file);

	// Say that we are done.
	die_nice('Installation done. You can now login into your blog. Thanks for using our software and don\'t forget to remove the installer.');
}

// Call the header
vienara_header();

// So what do we want to do?
if(!isset($_GET['step']))
	install_main();

// We've got a step to execute
elseif(isset($_GET['step']) && in_array($_GET['step'], $available_steps))
	call_user_func('install_step_' . $_GET['step']);

// Or just show the frontpage
else
	install_main();

// And call the footer. We're done now
vienara_footer();

// Echo everything
ob_flush();
