<?php
/**
 *
 * Imik
 *
 * This file is licensed under the MIT license. You can use
 * this project as a base for your own project, but you are
 * not allowed to remove this header comment block. You may
 * not use the name "Vienara" as name for your project
 * either. Thanks for understanding.
 *
 * @version: 1.0.0
 * @author: Dr. Deejay
 * @copyright 2012: Imik
 * @package: Imik
 *
 * License: MIT
 * 
*/
class Imik {

	/**
	 * Show an image. Probably one of the easiest parts
	 * Since: 1.0.0
	*/
	function show($resource) {

		// We need an array. If we don't have that, we won't show anything.
		if(!is_array($resource))
			die('Invalid resource specified.');

		// Setup new variables
		$new_vars = array(
			'image' => 'url',
			'link' => 'href',
			'height' => 'height',
			'width' => 'width',
			'alt' => 'alt',
			'slash' => 'slash',
			'title' => 'comment'
		);

			// Create them
			foreach($new_vars as $key => $value)
				$$key = $resource[$value];

		// Image arguments. They're simpler than they sound like
		$args = array(
			'src' => $image,
			'height' => $height,
			'width' => $width,
			'alt' => $alt,
			'title' => $title
		);

		// This might be an image
		if(!empty($link))
			$img = '<a href="' . $link . '">';
		else
			$img = '';

		// Start with the image
		$img .= '<img';

			// Add arguments
			foreach($args as $key => $value)
				if(!empty($value))
					$img .= ' ' . $key . '="' . $value . '"';

		// Use a slash?
		if($slash == true)
			$img .= ' /';

		// Now end it
		$img .= '>';

		// Is this a link?
		if(!empty($link))
			$img .= '</a>';

		// Return what we have
		return $img;
	}
}
