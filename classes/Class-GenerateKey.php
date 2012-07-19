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
* @version: 1.0 Release Candidate 1
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.org
* @sponsored by: Graywebhost (http://graywebhost.com)
*
* @license MIT
*/

// Have we defined Vienara?
if(!defined('Vienara'))
	die();

// The GenerateKey class
class GenerateKey {

	// Some numbers!
	function numbers() {

		// Start with a random one
		$number = rand(1, 4000);

		// Then increase the number
		$number = $number + rand(1, 3000);

		// An extra digit
		$number .= rand(1, 9);

		// Return
		return $number;
	}

	// This actually generates the entire key
	function generate($key = '') {

		// Set a new variable
		$generated_key = '';

		// Some letters
		$letters = array(
			1 => 'a',
			2 => 'B',
			3 => 'c',
			4 => 'D',
			5 => 'e',
			6 => 'F',
			7 => 'g',
			8 => 'H',
			9 => 'i',
			10 => 'J'
		);

		// Get each character
		$chars = preg_split('//', $key, -1, PREG_SPLIT_NO_EMPTY);

		// Walk through the characters
		foreach($chars as $char)
			$generated_key .= $char . $letters[rand(1, 10)];

		// To make debugging a bit more easier
		$generated_key .= '_';

		// Return what we have
		return $generated_key;
	}

	// Setup the class
	function setup() {

		// Get the current time
		$key = date("mdHis");

		// Now make the current time a bit different by adding some numbers
		$key .= $this->numbers();

		// Now make sure it's totally unique
		$key = $this->generate($key);

		// Return what we have
		return $key;
	}
}
