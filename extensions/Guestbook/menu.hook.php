<?php
global $vienara, $imik;

$image = array(
	'url' => $vienara['setting']['blog_url'] . '/images/new.png',
	'height' => '',
	'width' => '',
	'href' => '',
	'alt' => '*',
	'slash' => true,
	'comment' => ''
);

$img = $imik->show($image);

echo '
		' . ($vienara['setting']['menu_icons'] == 1 ? $img . ' ' : '') . '<a href="' . Blog_file . '?app=guestbook">Guestbook</a>';
