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
* @version: 1.0 Beta 1
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.co.cc
*
* @license MIT
*/

// Have we defined Vienara?
if(!defined('Vienara'))
	die();

// The timezone class
class Hash {

	// Generate a list of hashes
	function get()
	{
		global $vienara;

		// What kind of hashes do we support?
		$vienara['hashes'] = array(
			'sha1',
			'md5',
			'base64_encode',
			'base64_decode'
		);

		// Hmm..
		vienara_hook('hashes');

		// Does every function exist?
		foreach($vienara['hashes'] as $type => $value)
			if(!function_exists($value))
				unset($vienara['hashes'][$type]);

		// Return what we have
		return $vienara['hashes'];
	}

	// So we want to hash?
	function parse()
	{
		// Check the hashes
		$hashes = $this->get();

		// We do have anything set, right?
		if(!isset($_POST['hash']))
			return false;

		// Do we have a hash type set?
		elseif(!isset($_POST['type']))
			return false;

		// Is it in the list?
		elseif(!in_array($_POST['type'], $hashes))
			return false;

		// It's fine. Hash it.
		return $_POST['type']($_POST['hash']);
	}

	/**
	 * Raw hashing stuff
	*/
	function raw()
	{
		// We do have anything set, right?
		if(!isset($_POST['hash']))
			return false;

		// We need to make sure that we don't have HTML
		$_POST['hash'] = htmlspecialchars($_POST['hash'], ENT_NOQUOTES, 'UTF-8', false);

		// Return the result
		return $_POST['hash'];
	}
}
