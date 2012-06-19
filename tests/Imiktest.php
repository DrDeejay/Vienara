<?php
/**
 * Imik Test
 *
 * Version 1.0
 * Compatible with: Imik 1.0
 * Created for: the Vienara community
*/
define('Vienara', 1);

// Include Imik
include '../Imik.php';

// Setup the class
$imik = new Imik;

// Image details
$img = array(
	'url' => '../images/no_ava.png',
	'height' => '',
	'width' => '',
	'href' => 'http://google.com',
	'alt' => 'Imik test',
	'slash' => true,
	'comment' => 'Hi all'
);

// Create an image
echo $imik->show($img);
