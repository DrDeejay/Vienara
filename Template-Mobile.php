<?php
// The header
function vienara_header()
{
	global $vienara;

	echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="mobile.css" />
	<title>' . $vienara['setting']['title'] . '</title>
</head>
<body>
	<div class="title">
		' . $vienara['setting']['title'] . '
	</div>';
}

// The footer
function vienara_footer()
{
	global $vienara;

	// We don't want to display this when we're in the admin panel or anywhere else
	if(!isset($_GET['app']))
		vienara_page($vienara['blog_count'], Blog_file . '?page=');

	echo '
		<br />
	<a class="menu" href="' . Blog_file . '">' . show_string('index') . '</a>
	<a class="menu" href="' . Blog_file . '?rss">' . show_string('rss') . '</a>
	<a class="menu" href="' . Blog_file . '?app=search">' . show_string('search') . '</a>
	<a class="menu" href="' . Blog_file . '?normal">' . show_string('full_version') . '</a><br /><br />
	<a href="' . Website_Url . '">' . show_string('powered_by') . 'Vienara ' . Version . '</a> | <a href="http://graywebhost.com">' . show_string('sponsored_by') . 'Graywebhost</a><br /><br />
</body>
</html>';
}

// The thing that shows the blogs.
function vienara_show_blog($info = array())
{
	echo '
		<div class="category">' . $info['blog_title'] . '</div>
			<div class="padding background">
				' . $info['blog_content'] . '
			</div>
	<br />';
}

// Search.
function template_search()
{
	echo '
		<form action="' . Blog_file . '?app=search" method="post">
			<table width="100%">
				<tr>
					<td width="90%"><input type="text" class="search" name="keywords" autofocus="autofocus" /></td>
					<td width="10%"><input type="submit" class="searchsubmit" value="' . show_string('search') . '" /></td>
				</tr>
			</table><br /><br />
			<div class="category">' . show_string('search_for') . '</div>
				<div class="background padding"><input type="radio" name="type" value="normal" checked="checked" /> ' . show_string('search_normal') . '</div>
				<div class="background padding"><input type="radio" name="type" value="images" /> ' . show_string('search_images') . '</div>
				<div class="background padding"><input type="radio" name="type" value="maps" /> ' . show_string('search_maps') . '</div>
				<div class="background padding"><input type="radio" name="type" value="videos" /> ' . show_string('search_videos') . '</div><br />
			<div class="category">' . show_string('extra_search_args') . '</div>
				<div class="background padding"><input type="checkbox" name="thissite"/> ' . show_string('search_this') . '</div>
				<div class="background padding"><input type="checkbox" name="exact" /> ' . show_string('search_exact') . '</div><br /><br />
			<div class="category">' . show_string('privacy_policy') . '</div>
			<div id="privacy" class="padding background">
				' . show_string('google_policy') . '
			</div>
		</form>';
}

// This will create the page list
function vienara_page($count = 1, $link = '?page=')
{
	global $vienara;

	// Nothing?
	if($count == 0)
		return;

	// How many results do we have?
	$pages = ceil($count / $vienara['setting']['blogsperpage']);

	echo '
			<strong>' . show_string('pages') . ':</strong>';

	// Is it just one?
	if($pages < 1)
		echo '
			<strong><a href="' . $link . '1">1</a></strong>';

	// Show each page
	for($p = 1; $p <= $pages; $p++) {

		// Show it
		echo '
			' . (isset($_GET['page']) && $_GET['page'] == $p ? '<strong>' : '') . '<a href="' . $link . $p . '">' . $p . '</a>' . (isset($_GET['page']) && $_GET['page'] == $p ? '</strong>' : '');
	}

	// We're almost done
	echo '
		<br />';
}
