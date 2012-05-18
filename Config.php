<?php
// Have we defined Vienara?
if(!defined('Vienara'))
	die();

/**
 * Database settings
 *
 * server => The MySQL server. This is mostly localhost
 * username => The username of your MySQL account
 * password => The password of your MySQL account
 * dbname => The name of the database you would like to store the data in. The database should exist
 * prefix => Additional, only needed when you want to install multiple Vienara installations in 1 database
 *
*/
$db_settings = array(
	'server' => 'localhost',
	'username' => 'vienaradev',
	'password' => '',
	'dbname' => 'vienara',
	'prefix' => 'vie_'
);

/**
 * Maintenance mode. If you enable this, no one has access
 * to your blog anymore, so you can alter things of your blog
 * without being afraid that something will go wrong
*/
$maintenance = array(
	/**
	 * Maintenance modus.
	 * Possible values:
	 * 	0) Disable maintenance. Everyone has access to your blog.
	 *	1) Enable light maintenance. Only administrators are able to view your blog.
	 *	2) Full maintenance. No one is able to see your blog. Even you aren't.
	*/
	'enable' => 0,
	'message' => 'Temperal blog maintenance. We will be back as soon as possible.'
);
