<?php
/**
* Guestbook extension
*
*
* This file is licensed under the MIT license. You can use
* this project as a base for your own project, but you are
* not allowed to remove this header comment block. You may
* not use the name "Vienara" as name for your project
* either. Thanks for understanding.
*
* @version: 1.0
* @copyright 2012: Dr. Deejay
* @developed by: Dr. Deejay
*
* @license MIT
*/
if(!defined('Vienara'))
	die;

// Just add the comments table
xensql_query("
	CREATE TABLE IF NOT EXISTS `{db_pref]guestbook` (
	  `id_comment` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `ip_adress` text NOT NULL,
	  `message` longtext NOT NULL,
	  `time` int(11) NOT NULL,
	  `isadmin` tinyint NOT NULL,
	  `website` mediumtext NOT NULL,
	  `username` text NOT NULL
	) ENGINE=MyIsam CHARACTER SET utf8 COLLATE 'utf8_bin';
");
