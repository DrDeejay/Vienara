<?php
/**
 * TestPosts
 *
 * Version 1.0
 * Compatible with: Vienara 1.0
 * Created for: the Vienara community
*/
define('Vienara', 1);

// Include XenSql and the settings
include '../Config.php';
include '../xensql/XenSql.php';

// Connect with the database
$db = xensql_connect($db_settings['server'], $db_settings['username'], $db_settings['password'], $db_settings['dbname']);

	// Did it work?
	if(!$db)
		fatal_error('Database connection failed.');

// How many posts should we generate?
$posts = 1000;

// Status updates?
$status = 1; # Set to 0 to create blogs, set to 1 to create status updates

// Generate the posts
for($p = 1; $p <= $posts; $p++)

	xensql_query("
		INSERT 
			INTO {db_pref}content			
			VALUES(
				'',
				'Another blog - Part $p',
				'Hi all! This is another blogpost.',
				UNIX_TIMESTAMP(),
				'1',
				'$status'
		)
	");
