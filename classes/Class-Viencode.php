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
class Viencode {

	// Parse the content!
	function parse($content = '') {

		// Stuff we can replace without any hard obstacles
		$easy_replace = array(
			'[br]' => '<br />',
			'[hr]' => '<hr />',
			':evil:' => '<img src="./smileys/emoticon_evilgrin.png" alt="*" />',
			':D' => '<img src="./smileys/emoticon_grin.png" alt="*" />',
			':)' => '<img src="./smileys/emoticon_smile.png" alt="*" />',
			':o' => '<img src="./smileys/emoticon_surprised.png" alt="*" />',
			':P' => '<img src="./smileys/emoticon_tongue.png" alt="*" />',
			':3' => '<img src="./smileys/emoticon_waii.png" alt="*" />',
			':(' => '<img src="./smileys/emoticon_unhappy.png" alt="*" />',
			';)' => '<img src="./smileys/emoticon_wink.png" alt="*" />'
		);

		// Hooks?
		vienara_hook('viencode');

			// Just replace it.
			foreach($easy_replace as $key => $value)
				$content = str_ireplace($key, $value, $content);
	
		// What do we have?
		return $content;
	}
}
