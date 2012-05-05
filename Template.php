<?php
// Important globals
global $vienara;

// The header
function vienara_header()
{
	global $vienara, $show, $is_rtl;
	
	$order_ASC = ($vienara['setting']['order'] == "ASC") ? 'selected' : '';
	$order_DESC = ($vienara['setting']['order'] == "DESC") ? 'selected' : '';

	// The simple stuff
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"' . $is_rtl . ' lang="' . $vienara['lang']['code'] . '">
<head>
	<script type="text/javascript" src="javascript/Jquery.js"></script>
	<script type="text/javascript" src="javascript/Vienara.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">
		body {
			background: #D6D6D6;
			font-size: 12px;
			font-family: Verdana, Arial, Helvetica;
			margin: 0;
			padding: 0;
		}
		a, a:visited, a:hover {
			text-decoration: none;
			color: #76A744;
		}
		#wrapper {
			width: '.$vienara['setting']['width'].';
			margin: auto;
			background: white;
			min-width: 400px;
			padding: 10px;
		}
		.padding {
			padding: 5px;
		}
		.bg_color {
			background: #89BE51;
			background-image: linear-gradient(bottom, #A8CD81 18%, #9AC56C 100%);
			background-image: -o-linear-gradient(bottom, #A8CD81 18%, #9AC56C 100%);
			background-image: -moz-linear-gradient(bottom, #A8CD81 18%, #9AC56C 100%);
			background-image: -webkit-linear-gradient(bottom, #A8CD81 18%, #9AC56C 100%);
			background-image: -ms-linear-gradient(bottom, #A8CD81 18%, #9AC56C 100%);
			background-image: -webkit-gradient(
				linear,
				left bottom,
				left top,
				color-stop(0.18, #A8CD81),
				color-stop(1, #9AC56C)
			);
			color: white;
			font-weight: normal;
			font-family: Verdana, Arial;
			border-top-right-radius: 5px;
			border-top-left-radius: 5px;
			font-size: 13px;
		}
		.bg_color2 {
			background: #C0DBA3;
		}
		.bg_color3 {
			background: #CAE7AC;
		}
		.bg_color4 {
			background: #F8FFF6;
		}
		.bg_color5 {
			background: #E8ECE6;
		}
		.db_border {
			border: 1px solid #EDFEDB;
			border-top-right-radius: 5px;
			border-top-left-radius: 5px;
		}
		#header {
			padding: 15px;
			color: white;
			font-size: 50px;
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
		}
		.cat_bg {
			color: white;
			font-weight: bold;
			padding: 5px;
		}
		.copyright {
			text-align: center;
			font-size: 11px;
			margin-bottom: 20px;
		}
		hr {
			border: 0;
			border-top: 1px solid grey;
		}
		.title {
			font-size: 20px;
			font-weight: normal;
			color: red;
		}
		.blog_content .floatleft:first-letter, .blog_content:first-letter {
			color: #638B3E;
			font-weight: bold;
			font-family: Times New Roman;
			font-size: 40px;
		}
		.menu {
			background: #78945B;
			padding: 5px;
			border-bottom-right-radius: 5px;
			border-bottom-left-radius: 5px;
		}
		.menu a {
			color: white;
			margin-right: 5px;
		}
		.aligncenter {
			text-align: center;
		}
		.editor {
			padding: 5px;
			font: inherit;
			width: 95%;
			height: 150px;
			opacity: 1!important;
			position: relative;
			top: 10;
			left: 10;
			font-family: inherit;
			border: grey 1px solid;
			border-radius: 5px;
		}
		.floatright {
			float: right;
		}
		.floatleft {
			float: left;
		}
		.clear {
			clear: both;
		}
		.cat_bg a {
			color: white;
		}
		input[type="text"], input[type="password"], select {
			padding: 5px;
			border: 1px solid grey;
			margin: 2px;
			background: #F7F7F7;
		}
		input[type="text"]:hover, input[type="password"]:hover, select:hover {
			background: white;
		}
		textarea, input { outline: none; resize: none;}
		input[type="submit"], .more, button {
			/* http://gradients.glrzad.com/ */
			background-image: linear-gradient(bottom, #71B055 18%, #88C46E 100%);
			background-image: -o-linear-gradient(bottom, #71B055 18%, #88C46E 100%);
			background-image: -moz-linear-gradient(bottom, #71B055 18%, #88C46E 100%);
			background-image: -webkit-linear-gradient(bottom, #71B055 18%, #88C46E 100%);
			background-image: -ms-linear-gradient(bottom, #71B055 18%, #88C46E 100%);
			background-image: -webkit-gradient(
				linear,
				left bottom,
				left top,
				color-stop(0.18, #71B055),
				color-stop(1, #88C46E)
			);
			border-radius: 5px;
			padding: 5px;
			width: 100px;
			color: white;
			border: 1px solid #639E48;
			font-weight: normal;
			font-family: Verdana, Arial;
			font-size: 12px;
		}
		.date {
			background: #6BB74C;
			color: white;
			padding: 5px;
			width: 45px;
			height: 45px;
			font-size: 18px;
			margin: 5px;
		}
		.date .daymonth {
			font-size: 13px;
		}
		.notice {
			border: 1px solid dimgrey;
			background: white;
			margin: 5px;
			padding: 5px;
		}
		.sidebar {
			width: 100%;
		}
		.sidebar_link {
			display: block;
			padding: 10px;
			margin-bottom: 1px!important;
			color: dimgrey!important;
			line-height: 25px;
			border-left: 10px solid #C3D5AF;
			background: #F7F7F7;
		}
		.sidebar_link:hover {
			background: #F5F5F5;
		}
		.adm_blog {
			margin: 1px;
			font-size: 15px;
			padding: 5px;
			color: dimgrey;
		}
		.blogborder {
			border: 1px solid grey;
			height: 200px;
			padding: 3px;
			overflow-y: scroll;
		}
		.blogborder a {
			color: inherit;
		}
		.update {
			padding: 10px;
			border: 1px solid #DEDEDE;
			background: #F5F5F5;
			margin: 10px;
			width: 250px;
			display: table-cell;
			font-size: 20px;
			color: #8AC168;
		}
		.done {
			margin: auto;
			width: 40%;
		}
		.radius {
			border-radius: 5px;
		}';

	// Custom css
	vienara_hook('cust_css');

	echo '
	</style>
	<title>' . $vienara['setting']['title'] . ($vienara['setting']['enable_extra_title'] == 1 ? ' | ' . $vienara['setting']['extra_title'] : '') . '</title>';
	
	// Custom header scripts
	vienara_hook('html_header');	

	echo '
</head>
<body class="vienara">
	<div id="editorhere_after"></div>
	<div id="wrapper">
		<div id="header" class="bg_color">
			' . $vienara['setting']['title'] . '
		</div>
		<div class="menu">
			<a href="' . Blog_file . '"><img src="./images/home.png" alt="" /> ' . show_string('index') . '</a>';
		
			vienara_hook('menu');

			echo (!vienara_is_logged() ? '<a href="javascript:void(0);" onclick="$(\'.login\').slideToggle(); $(\'.password\').focus();"><img src="./images/login.png" alt="" /> ' . show_string('login') . '</a><br />
			<div style="display: none;" class="login">
				<form action="' . Blog_file . '?app=login" method="post">
					<div class="notice">
						' . show_string('i_accept') . '
					</div>
					' . show_string('password') . ': <input type="password" name="password" class="password" /> <input type="submit" value="' . show_string('login') . '" /> <input type="checkbox" name="i_accept" /> ' . show_string('cookie_okay') . '
				</form>
			</div>' : '
			<a href="' . Blog_file . '?app=admin"><img src="./images/admin.png" alt="" /> ' . show_string('admin') . '</a>
			<a href="javascript:void(0);" onclick="$(\'#editor\').slideToggle();"><img src="./images/new.png" alt="" /> ' . show_string('new_post') . '</a>
			<a href="' . Blog_file . '?app=logout"><img src="./images/logout.png" alt="" /> ' . show_string('logout') . '</a>');

	echo '
		</div>
		<br />';

		// Are we logged in? If so, show the editor
		if(vienara_is_logged())
			echo '
				<div id="editorhere_before"></div>
				<div id="editor" style="display: none;">
					<div class="cat_bg bg_color newblog_title">
						' . show_string('new_post') . '
					</div>
					<div class="padding bg_color5">
						<form action="' . Blog_file . '" method="post">
							<table width="100%">
								<tr class="subject">
									<td width="20%"><strong id="title_desc">' . show_string('post_title') . ':</strong></td>
									<td width="80%"><input type="text" id="post_title" name="post_title" /></td>
								</tr>
								<tr>
									<td width="20%" class="blog_msg"><strong>' . show_string('message') . ':</strong></td>
									<td width="80%"><textarea class="editor" onfocus="vienara_higher()"  name="content" rows="1" cols="1"></textarea></td>
								</tr>
							</table>
							<input type="submit" id="editor_submit" value="' . show_string('submit') . '" />
							<button class="more" type="button" style="display: none;" onClick="vienara_backtonormal()" id="editor_cancel" href="javascript:void();">' . show_string('cancel') . '</button>
						</form>
					</div>
					<br />
				</div>';

		// Content!
		echo '
			<div id="content">';

}

// And this displays the footer
function vienara_footer()
{
	global $vienara, $show, $show_results;

	// And close the template
	echo '
				<div class="aligncenter">
					'.((($show_results > ((int)$vienara['setting']['blogsperpage'] - 1)) and ($show_results < $vienara['blog_count'])) ?  '<a href="' . Blog_file . '?show='.(((int)$show) - (2 * (int)$vienara['setting']['blogsperpage'])) . '" class="more" style="color: white!important;">< '.(($vienara['setting']['order'] == "DESC") ? show_string('newer') : show_string('older')).'</a>  ' : '').'
					'.((($vienara['blog_count'] - ((int)$vienara['setting']['blogsperpage'] + $show_results)) > 0) ?  '<a href="' . Blog_file . '?show='.$show . '" class="more" style="color: white!important;">'.(($vienara['setting']['order'] == "ASC") ? show_string('newer') : show_string('older')).' ></a><br /><br />' : '').'
					<br /><br />' . ($vienara['setting']['top_button'] == 1 ? '<a href="javascript:void(0);" onclick="$(\'html, body\').animate({scrollTop:0}, \'slow\');">' . show_string('top') . '</a>' : '') . '
				</div>
			</div>
	</div>
	<div class="copyright">
		<a href="' . Website_Url . '">' . show_string('powered_by') . 'Vienara ' . show_string('version') . Version . '</a><br />
		' . show_string('icons_by') . '<a href="http://www.famfamfam.com/lab/icons/silk/">FamFamFam</a> ' . show_string('and') . ' <a href="http://www.fatcow.com/free-icons">Fatcow</a>
	</div>
</body>
</html>';
}

// Show a blog
function vienara_show_blog($information = '')
{
	echo '
			<div class="floatleft" width="9%">
				<div class="date">
					<span class="daymonth">' . date("M j", $information['post_date']) . '</span><br />
					' . date("Y", $information['post_date']) . '
				</div>	
			</div>
			<div style="width: 90%">
				<div class="title">
					<a href="#' . $information['id_blog'] . '" name="' . $information['id_blog'] . '">' . $information['blog_title'] . '</a>
				</div>
				' . show_string('posted_on') . ': ' . date("F j, Y, g:i a", $information['post_date']) . '
			</div>
			<br class="clear" />
			<div class="blog_content"' . (vienara_is_logged() ? ' onMouseOver="show_delete(\'.deletebutton_' . $information['id_blog'] . '\')" onMouseOut="hide_delete(\'.deletebutton_' . $information['id_blog'] . '\')"' : '') . '>';

			// We need this in order to fix display issues
			if(vienara_is_logged())
				echo '
					<div class="floatleft" style="width: 90%;">';

			echo '
				' . $information['blog_content'];

			// If we are logged in, show a delete button
			if(vienara_is_logged())
				echo '
					</div>
					<div class="floatright deletebutton_' . $information['id_blog'] . '" style="display: none;">
						<a href="' . Blog_file . '?app=delete&id=' . $information['id_blog'] . '">' . show_string('delete') . '</a>
					</div>
					<br class="clear" />';

		echo '
			</div>
		<br /><br />';
}

// Meh
function screenofdeath_header()
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style type="text/css">
		body {
			background: #EBF1F6 url(images/error_bg.png) repeat-x;
			color: white;
			font-family: Verdana;
			font-size: 13px;
		}
		h1 {
			border-bottom: 2px solid white;
		}
		a {
			color: white;
		}
	</style>
</head>
<body>
	<div style="width: 500px;">
		<h1>A fatal error occurred</h1>
		We are very sorry, but it looks like a fatal error occurred. Vienara returns: <em>';
}

// And the footer
function screenofdeath_footer()
{
	echo '
		</em> If you are not sure what might have caused the error, please contact the administrator
		and he/she will probably make sure this error will be fixed as soon as possible. It might also
		been handy to inform the current url, version and what you did before the error occurred. You
		can also try refreshing the page, which might fix the issue.<br /><br />

		Also note that the current version of Vienara is ' . Version . '. If the administrator is not
		available or if you <em>are</em> the administrator, it might be a good idea to check out the
		<a href="' . Website_Url . '">Vienara site</a>, which includes a support forum where you can
		ask your questions.
	</div>
</body>
</html>';
}

// The administration template
function template_admin($admin = array())
{
	global $vienara;

	// We need an array..
	if(empty($admin) || !is_array($admin))
		return false;

	// Let's get started!
	echo '
		<div class="bg_color cat_bg">
			' . $admin['title'] . '
		</div>
		<div class="floatleft" style="width: 20%;">
			<br />
			<div class="sidebar">';

		// Show the administration navigation
		foreach($admin['sidebar'] as $item)
			echo '
				<a href="' . $item['href'] . '" class="sidebar_link"><img src="./images/' . $item['icon'] . '" alt="" /> ' . $item['title'] . '</a><br />';

	echo '
			</div>
		</div>
		<div class="floatright bg_color4 radius" style="width: 75%; padding: 10px;">';

	// Sub admin templates!
	if(adm_sect == 'main') {

		// Welcome!
		echo '
			<div class="' . $admin['notice']['class'] . '">
				' . $admin['notice']['string'] . '
			</div>';

		// Echo everything
		foreach($admin['credits'] as $team) {
			
			// Echo the team title
			echo '
				<br />
				<div class="cat_bg bg_color2">
					' . $team['label'] . '
				</div>';

			// Show the things it contains
			foreach($team['teams'] as $key => $value) {

				// Are we talking about members now?
				if(empty($team['no_team'])) {

					// Echo the team name
					echo '
						<div class="cat_bg bg_color3">
							' . $value['label'] . '
						</div>
						<div class="padding bg_color4">';

					// Echo each team
					echo implode(', ', $value['members']);

					echo '
						</div>';
				}

				// Yup.
				else
					echo '<div class="padding bg_color4">' . implode(', ', $value) . '</div>';
			}
		}
	}

	// Posting a new blog!
	elseif(adm_sect == 'newblog') {
	
		// Show the editor
		echo '
				<div id="editorhere_before"></div>
				<div id="editor">
					<div class="cat_bg bg_color newblog_title">
						' . show_string('new_post') . '
					</div>
					<div class="padding bg_color5">
						<form action="' . Blog_file . '" method="post">
							<table width="100%">
								<tr class="subject">
									<td width="20%"><strong id="title_desc">' . show_string('post_title') . ':</strong></td>
									<td width="80%"><input type="text" id="post_title" name="post_title" /></td>
								</tr>
								<tr>
									<td width="20%" class="blog_msg"><strong>' . show_string('message') . ':</strong></td>
									<td width="80%"><textarea class="editor" name="content" rows="1" cols="1"></textarea></td>
								</tr>
							</table>
							<input type="submit" id="editor_submit" value="' . show_string('submit') . '" />
						</form>
					</div>
					<br />
				</div>';
	}

	// Blogs!
	elseif(adm_sect == 'blogs') {
		
		global $admin_show;

		// Set the color class
		$class = 5;
	
		echo '
			<div class="blogborder">';

		// Show the contents
		foreach($admin_show as $blog) {

			// Show!
			echo '
				<div class="adm_blog bg_color' . $class . '">
					<a href="javascript:void(0);" onclick="$(\'#adm_blog_' . $blog['id_blog'] . '\').slideToggle(\'slow\');">' . $blog['blog_title'] . '</a>
				</div>
				<div style="display: none;" id="adm_blog_' . $blog['id_blog'] . '">
					' . nl2br($blog['blog_content']) . '
				</div>';

			// Reset the background color
			$class = $class == '5' ? 4 : 5;
		}

		echo '
			</div>';
	}

	// Manage our settings
	elseif(adm_sect == 'settings') {
		echo '
		<form accept-charset="UTF-8" action="' . Blog_file . '?app=admin&section=settings" method="post">
			<input type="hidden" name="settings" />
			<table width="100%">';

			// Now parse each setting
			foreach($admin['settings'] as $key => $spage) {

				// Setup a title
				if(!empty($spage))
					$spage[1] = show_string($spage[1]);

				// Is it empty?
				if(empty($spage))
					echo '
						<tr>
							<td colspan="2" style="padding: 4px!important;"><hr /></td>
						</tr>';

				// It's a textbox. Right?
				elseif($spage[0] == 'text')
					echo '
						<tr>
							<td width="50%">' . $spage[1] . ':</td>
							<td width="50%"><input type="text" name="' . $key . '" value="' . $vienara['setting'][$key] . '" /></td>
						</tr>';
			
				// A number!
				elseif($spage[0] == 'number')
					echo '
						<tr>
							<td width="50%">' . $spage[1] . ':</td>
							<td width="50%"><input type="text" name="' . $key . '" value="' . $vienara['setting'][$key] . '" maxlength="3" size="3" /></td>
						</tr>';

				// It's a checkbox
				elseif($spage[0] == 'check')
					echo '
						<tr>
							<td width="50%">' . $spage[1] . ':</td>
							<td width="50%"><input type="hidden" name="' . $key . '_type" value="checkbox" /><input type="checkbox" name="' . $key . '" ' . ($vienara['setting'][$key] == '1' ? ' checked="checked"' : '') . '" /></td>
						</tr>';				

				// Show a textarea
				elseif($spage[0] == 'largetext')
					echo '
						<tr>
							<td width="50%">' . $spage[1] . ':</td>
							<td width="50%"><textarea class="editor" cols="1" rows="1" name="' . $key . '">' . br2nl($vienara['setting'][$key]) . '</textarea></td>
						</tr>';		

				// Selection boxes. ;D
				elseif($spage[0] == 'select') {
	
					// This might be the language selection box
					if($key == 'language') {
		
						// Echo it
						echo '					
							<tr>
								<td width="50%">' . $spage[1] . ':</td>
								<td width="50%"><select name="language">';

							// Show the languages
							foreach($vienara['languages'] as $language)
								echo '
									<option value="' . $language. '"' . ($language == $vienara['setting']['language'] ? ' selected="selected"' : '') . '>' . $language . '</option>';

							echo '
								</select></td>
							</tr>';
					}

					// Nope
					else {
						// Echo it
						echo '					
							<tr>
								<td width="50%">' . $spage[1] . ':</td>
								<td width="50%"><select name="' . $key . '">';

							// Show the languages
							foreach($spage[2] as $item => $value)
								echo '
									<option value="' . $value. '"' . ($value == $vienara['setting'][$key] ? ' selected="selected"' : '') . '>' . $value . '</option>';

							echo '
								</select></td>
							</tr>';
					}
				}					
			}

			echo '
			</table>
			<br /><input type="submit" value="' . show_string('submit') . '" />
		</form>';
	}

	// It's the password edit form
	elseif(adm_sect == 'password')
		echo '
				<form action="' . Blog_file . '" method="post">
					' . show_string('old_pass') . ': <input type="password" name="old_password" /><br />
					' . show_string('new_pass') . ': <input type="password" name="new_password" /> <input type="submit" value="' . show_string('submit') . '" />
				</form>';

	// Hashes! These are fun :D
	elseif(adm_sect == 'hash') {

		// Load the hash class
		loadClass('Hash');

			// Setup this class
			$hash = new Hash;

			// Get the hash types
			$hash_types = $hash->get();

		echo '
			<form action="' . Blog_file . '?app=admin&section=hash" method="post">
				<strong>' . show_string('hash_type') . ':</strong><br />
					<select name="type">';

					// Show the list of hashes	
					foreach($hash_types as $key => $value)
						echo '
							<option value="' . $value . '">' . $value . '</option>';
					
		echo '
					</select>
				<br />
				<textarea class="editor" cols="1" rows="1" name="hash">' . show_string('hashthis') . '</textarea><br />
				<input type="submit" value="' . show_string('hash_now') . '" />
			</form>';

		// Did we hash?! :D :D :D
		if($hash->parse() != false)
			echo '
				<br />
				<div class="blogborder" style="width: 80%;">
					' . $hash->parse() . '
				</div>';
	}

	echo '
		</div>
		<br class="clear" />';
}

// We're done with something.
function done($link = '')
{
	echo '
			<div class="done">
				<div class="cat_bg bg_color">
					' . show_string('action_done') . '
				</div>
				<div class="bg_color4 padding">
					' . show_string('action_done2') . '
					<br /><br />
						<em><a href="' . $link . '">' . show_string('continue') . '</a></em>
				</div>
			</div>';

	// Die!
	die_nice();
}
