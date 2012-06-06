<?php
// Important globals
global $vienara;

// The header
function vienara_header()
{
	global $vienara, $show, $is_rtl, $viencode;

	// The simple stuff
	echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"' . $is_rtl . ' lang="' . $vienara['lang']['code'] . '">
<head>
	<script type="text/javascript" src="javascript/Jquery.js?' . $vienara['setting']['css_cache_version'] . '"></script>
	<script type="text/javascript" src="javascript/Vienara.js?' . $vienara['setting']['css_cache_version'] . '"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="style.css?' . $vienara['setting']['css_cache_version'] . '" />
	<title>' . $vienara['setting']['title'] . ($vienara['setting']['enable_extra_title'] == 1 ? ' | ' . $vienara['setting']['extra_title'] : '') . '</title>';
	
	// Custom header scripts
	vienara_hook('html_header');	

	echo '
</head>
<body class="vienara">
	<div id="editorhere_after"></div>
	<div id="wrapper" style="width: ' . $vienara['setting']['width'] . '%;">
		<div id="header" class="bg_color">
			' . $vienara['setting']['title'] . '
		</div>
		<div class="menu">
			<a href="' . Blog_file . '">' . ($vienara['setting']['menu_icons'] == 1 ? '<img src="./images/home.png" alt="" /> ' : '') . show_string('index') . '</a>';

			// Custom tabs
			foreach($vienara['tabs'] as $tab)
				echo '
					' . (file_exists('MenuIcons/tab_' . $tab['tab_position']  . '.png') ? '<img src="MenuIcons/tab_' . $tab['tab_position']  . '.png" alt="" /> ' : '') . '<a href="' . $tab['tab_link'] . '">' . $tab['tab_label'] . '</a>';
		
			vienara_hook('menu');

			if($vienara['setting']['enable_search'] == 1)
				echo '
					<a href="' . Blog_file . '?app=search">' . ($vienara['setting']['menu_icons'] == 1 ? '<img src="./images/search.png" alt="" /> ' : '') . show_string('search') . '</a>';

			echo (!vienara_is_logged() ? '<a href="javascript:void(0);" onclick="$(\'.login\').slideToggle(); $(\'.password\').focus();">' . ($vienara['setting']['menu_icons'] == 1 ? '<img src="./images/login.png" alt="" /> ' : '') . show_string('login') . '</a><br />
			<div style="display: none;" class="login">
				<form action="' . Blog_file . '?app=login" method="post">
					' . show_string('password') . ': <input type="password" name="password" class="password" /> <input type="submit" value="' . show_string('login') . '" />
				</form>
			</div>' : '
			<a href="' . Blog_file . '?app=admin">' . ($vienara['setting']['menu_icons'] == 1 ? '<img src="./images/admin.png" alt="" /> ' : '') . show_string('admin') . '</a>
			<a href="' . Blog_file . '?app=logout">' . ($vienara['setting']['menu_icons'] == 1 ? '<img src="./images/logout.png" alt="" /> ' : '') . show_string('logout') . '</a>');

	echo '
		</div>
		<div class="reg_content">
		<br />';

		// Content!
		echo '
			<div id="content">';

	// Notice?
	if(!empty($vienara['setting']['notice']))
		echo '
				<div class="news">
					' . $viencode->parse($vienara['setting']['notice']) . '
				</div>';

	// Facebook likes
	if($vienara['setting']['enable_comments'] == 1)
		echo '
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));</script>';

	// Are we viewing the frontpage of vienara?
	if(!isset($_GET['app']) && vienara_is_logged() && $vienara['setting']['quick_status'] == 1)
		echo '
			<br />
			<div class="cat_bg bg_color">
				' . show_string('new_status') . '
			</div>
			<div class="padding bg_color5">
				<form action="' . Blog_file . '" method="post">
					<input type="hidden" name="adm_post" />
					<input type="hidden" name="is_status" />
					<input type="hidden" name="approved" />
					<input type="hidden" name="post_title" value="' . show_string('status_title') . '" />
					<textarea class="new_status" rows="1" cols="1" name="content"></textarea><br />
					<input type="submit" value="' . show_string('submit') . '" />
				</form>
			</div>
			<br />';
}

// And this displays the footer
function vienara_footer()
{
	global $vienara;

	// And close the template
	echo '
				<div class="aligncenter">';

	
	// We don't want to display this when we're in the admin panel or anywhere else
	if(!isset($_GET['app']))
		vienara_page($vienara['blog_count'], Blog_file . '?page=');

		echo '
					<br /><br />' . ($vienara['setting']['top_button'] == 1 ? '<a href="javascript:void(0);" onclick="$(\'html, body\').animate({scrollTop:0}, \'slow\');">' . show_string('top') . '</a>' : '') . '
				</div>
			</div>
		</div>
	</div>
	<div class="copyright">
		<a href="' . Website_Url . '">' . show_string('powered_by') . 'Vienara ' . show_string('version') . Version . '</a><br />
		' . show_string('icons_by') . '<a href="http://www.famfamfam.com/lab/icons/silk/">FamFamFam</a> ' . show_string('and') . ' <a href="http://www.fatcow.com/free-icons">Fatcow</a>' . ($vienara['setting']['enable_custom_copyright'] == 1 ? '
			<br />' . (!empty($vienara['setting']['copyright_link_to']) ? '<a href="' . $vienara['setting']['copyright_link_to'] . '">' : '') . $vienara['setting']['custom_copyright'] . (!empty($vienara['setting']['copyright_link_to']) ? '</a>' : '') : '') . '<br />
		<a href="' . Blog_file . '?rss">' . show_string('rss') . '</a>
	</div>
</body>
</html>';
}

// Show a blog
function vienara_show_blog($information = '', $is_status = false)
{
	global $vienara;

	// Is this a status?
	if($is_status == true)
		echo '
			<div class="blogpost padding">
				<table style="width: 100%;">
					<tr>
						<td width="5%">
							<img src="' . $vienara['setting']['avatar'] . '" alt="" />
						</td>	
						<td width="95%" style="vertical-align: top">
							' . $information['blog_content'] . '<br />
							<em>' . show_string('posted_on') . ': ' . date("F j, Y, g:i a", $information['post_date']) . '</em>
						</td>
					</tr>
				</table>
			</div>';
	else
		echo '
			<div class="blogpost">
				<div class="floatleft">
					<div class="date">
						<span class="daymonth">' . parse_date(date("M j", $information['post_date'])) . '</span><br />
						' . parse_date(date("Y", $information['post_date'])) . '
					</div>	
				</div>
				<div style="width: 90%">
					<div class="title">
						<a href="#' . $information['id_blog'] . '" id="' . $information['id_blog'] . '">' . $information['blog_title'] . '</a>
					</div>
					' . show_string('posted_on') . ': ' . date("F j, Y, g:i a", $information['post_date']) . '
				</div>
				<br class="clear" />
				<div class="blog_content">
					' . $information['blog_content'] . '
					' . ($vienara['setting']['enable_likes'] == 1 ? '<br /><br /><iframe src="https://www.facebook.com/plugins/like.php?href=' . $vienara['setting']['blog_url'] . '?blog=' . $information['id_blog'] . '" style="border:none!important; width:450px; height:80px"></iframe>' : '') . '
				' . ($vienara['setting']['enable_likes'] == 1 ? '<br /><div class="fb-comments" data-href="' . $vienara['setting']['blog_url'] . '?blog=' . $information['id_blog'] . '" data-num-posts="5" data-width="470"></div>' : '') . '
				</div>
			</div>';
}

// The help template
function template_help($doc_message = '', $title = '')
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<style type="text/css">
			body {
				background: #B9D897;
				color: dimgrey;
				font-family: Verdana;
				font-size: 13px;
			}
			.message {
				width: 40%;
				margin: auto;
				padding: 15px;
				background: white;
				border: 1px solid dimgrey;
			}
			a {
				color: #9BC86C;
				text-decoration: none;
			}
		</style>
		<title>' . $title . '</title>
	</head>
	<body>
		<div class="message">
			' . $doc_message . '
			<br /><br />
			<strong><a href="javascript:void(0);" onclick="window.close();">' . show_helpstring('close') . '</a></strong>
		</div>
	</body>
</html>';
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
			<div class="sidebar">';

		// Show the administration navigation
		foreach($admin['sidebar'] as $item)
			echo '
				<a href="' . $item['href'] . '" class="sidebar_link"><img src="./images/' . $item['icon'] . '" alt="" /> ' . $item['title'] . '</a>';

	echo '
			</div>
		</div>
		<div class="floatright" style="width: 75%; padding: 10px;">';

	// Sub admin templates!
	if(!defined('adm_sect') || adm_sect == 'main') {

		// Welcome!
		echo '
			<div class="' . $admin['notice']['class'] . '">
				' . $admin['notice']['string'] . '
			</div><br />
			<table width="100%" cellspacing="1">
				<tr>
					<td width="50%" class="bg_color5 padding"><strong>' . show_string('blog_version') . ':</strong></td>
					<td width="50%" class="bg_color4 padding">' . Version . '</td>
				</tr>
				<tr>
					<td width="50%" class="bg_color5 padding"><strong>' . show_string('db_version') . ':</strong></td>
					<td width="50%" class="bg_color4 padding">' . $vienara['setting']['version'] . '
					' . (Version != $vienara['setting']['version'] ? '<a href="upgrade.php" class="update_req">' . show_string('update_required') . '</a>' : '') . '</td>
				</tr>
				<tr>
					<td width="50%" class="bg_color5 padding"><strong>' . show_string('blog_branch') . ':</strong></td>
					<td width="50%" class="bg_color4 padding">' . Branch . '</td>
				</tr>
				<tr>
					<td width="50%" class="bg_color5 padding"><strong>' . show_string('blog_support') . ':</strong></td>
					<td width="50%" class="bg_color4 padding"><a href="' . Website_Url . '">' . Website_Url . '</a></td>
				</tr>
				<tr>
					<td width="50%" class="bg_color5 padding"><strong>' . show_string('jquery_v') . ':</strong></td>
					<td width="50%" class="bg_color4 padding">' . JqueryVersion . '</td>
				</tr>
			</table>
			<br /><br />
			<div class="cat_bg bg_color">
				' . show_string('version_history') . '
			</div>
			<div class="bg_color4 padding changelog">
				' . $vienara['changelog'] . '
			</div>';

		// Echo everything
		foreach($admin['credits'] as $team) {
			
			// Echo the team title
			echo '
				<br />
				<div class="cat_bg bg_color">
					' . $team['label'] . '
				</div>';

			// Show the things it contains
			foreach($team['teams'] as $key => $value) {

				// Are we talking about members now?
				if(empty($team['no_team'])) {

					// Echo the team name
					echo '
						<div class="cat_bg bg_color2">
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
				<div id="editor">
					<div class="cat_bg bg_color newblog_title">
						' . show_string('new_post') . '
					</div>
					<div class="padding bg_color5">
						<form action="' . Blog_file . '" method="post">
							<input type="hidden" name="adm_post" />
							<table width="100%">
								<tr class="subject">
									<td width="20%"><strong id="title_desc">' . show_string('post_title') . ':</strong></td>
									<td width="80%"><input type="text" id="post_title" name="post_title" /></td>
								</tr>
								<tr>
									<td width="20%" class="blog_msg"><strong>' . show_string('message') . ':</strong></td>
									<td width="80%"><textarea class="editor new_post" name="content" rows="1" cols="1"></textarea></td>
								</tr>
								<tr class="subject">
									<td width="20%"><strong>' . show_string('approved') . ':</strong></td>
									<td width="80%"><input type="checkbox" id="approved" name="approved" /></td>
								</tr>
								<tr class="subject">
									<td width="20%"><strong>' . show_string('is_status') . ':</strong></td>
									<td width="80%"><input type="checkbox" id="is_status" name="is_status" /></td>
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
		
		global $admin_show, $viencode;

		// Set the color class
		$class = 5;
	
		echo '
			<div class="cat_bg bg_color">
				' . show_string('blogs') . '
			</div>
			<div class="admin_blogs">';

		// Show the contents
		foreach($admin_show as $blog) {

			// Parse bbc
			$blog['blog_content'] = $viencode->parse($blog['blog_content']);

			// Show!
			echo '
				<div class="adm_blog bg_color' . $class . '">
						<a href="' . Blog_file . '?app=admin&section=publish&id=' . $blog['id_blog'] . '">
						' . ($blog['published'] == 1 ? '
							<img src="./images/accept.png" alt="" />' : '
							<img src="./images/cancel.png" alt="" />') . ' 
						</a>
				' . ($blog['published'] == 0 ? '<em>' : '') . '<a href="javascript:void(0);" onclick="$(\'#adm_blog_' . $blog['id_blog'] . '\').slideToggle(\'slow\');">' . $blog['blog_title'] . '</a>' . ($blog['published'] == 0 ? '</em>' : '') . '
					<div class="floatright" style="width: 40px;">
						<a href="' . Blog_file . '?app=delete&id=' . $blog['id_blog'] . '" /><img src="./images/delete.png" alt="' . show_string('delete') . '" /></a>
						<a href="' . Blog_file . '?app=admin&section=edit&id=' . $blog['id_blog'] . '" /><img src="./images/edit.png" alt="' . show_string('edit') . '" /></a>
					</div>
					<br class="clear" />
				</div>
				<div style="display: none;" id="adm_blog_' . $blog['id_blog'] . '">
					<div class="padding">
						' . ($blog['published'] == 0 ? '<div class="notice">' . show_string('not_approved') . '</div>' : '') . '
						' . nl2br($blog['blog_content']) . '
					</div>
				</div>';

			// Reset the background color
			$class = $class == '5' ? 4 : 5;
		}
	
		echo '
			</div>';

		vienara_page($vienara['blog_count'], Blog_file . '?app=admin&section=blogs&page=');
	}

	// Manage our settings
	elseif(adm_sect == 'settings') {
		echo '
		<form accept-charset="UTF-8" action="' . Blog_file . '?app=admin&section=settings" method="post">
			<input type="hidden" name="settings" />
			<div class="cat_bg bg_color">
				' . show_string('settings') . '
			</div>
			<div class="padding bg_color6">
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
							<td width="50%"><a href="help.php?id=' . $key . '" target="_blank">' . show_string('help') . '</a> ' . $spage[1] . ':</td>
							<td width="50%"><input type="text" name="' . $key . '" value="' . $vienara['setting'][$key] . '" /></td>
						</tr>';
			
				// A number!
				elseif($spage[0] == 'number')
					echo '
						<tr>
							<td width="50%"><a href="help.php?id=' . $key . '" target="_blank">' . show_string('help') . '</a> ' . $spage[1] . ':</td>
							<td width="50%"><input type="text" name="' . $key . '" value="' . $vienara['setting'][$key] . '" maxlength="3" size="3" /></td>
						</tr>';

				// It's a checkbox
				elseif($spage[0] == 'check')
					echo '
						<tr>
							<td width="50%"><a href="help.php?id=' . $key . '" target="_blank">' . show_string('help') . '</a> ' . $spage[1] . ':</td>
							<td width="50%"><input type="hidden" name="' . $key . '_type" value="checkbox" /><input type="checkbox" name="' . $key . '" ' . ($vienara['setting'][$key] == '1' ? ' checked="checked"' : '') . ' /></td>
						</tr>';				

				// Show a textarea
				elseif($spage[0] == 'largetext')
					echo '
						<tr>
							<td width="50%"><a href="help.php?id=' . $key . '" target="_blank">' . show_string('help') . '</a> ' . $spage[1] . ':</td>
							<td width="50%"><textarea class="editor" cols="1" rows="1" name="' . $key . '">' . br2nl($vienara['setting'][$key]) . '</textarea></td>
						</tr>';		

				// Selection boxes. ;D
				elseif($spage[0] == 'select') {
	
					// This might be the language selection box
					if($key == 'language') {
		
						// Echo it
						echo '					
							<tr>
								<td width="50%"><a href="help.php?id=' . $key . '" target="_blank">' . show_string('help') . '</a> ' . $spage[1] . ':</td>
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
								<td width="50%"><a href="help.php?id=' . $key . '" target="_blank">' . show_string('help') . '</a> ' . $spage[1] . ':</td>
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
			</div>
			<br /><input type="submit" value="' . show_string('submit') . '" />
		</form>';
	}

	// It's the password edit form
	elseif(adm_sect == 'password')
		echo '
				<div class="cat_bg bg_color">
					' . show_string('change_pass') . '
				</div>
				<div class="padding bg_color5">
					<form action="' . Blog_file . '" method="post">
						' . show_string('old_pass') . ': <input type="password" name="old_password" /><br />
						' . show_string('new_pass') . ': <input type="password" name="new_password" /> <input type="submit" value="' . show_string('submit') . '" />
					</form>
				</div>';

	// Hashes! These are fun :D
	elseif(adm_sect == 'hash') {

		// Load the hash class
		loadClass('Hash');

			// Setup this class
			$hash = new Hash;

			// Get the hash types
			$hash_types = $hash->get();

		echo '
			<div class="cat_bg bg_color">
				' . show_string('hash') . '
			</div>
			<div class="padding bg_color4">
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
				</form>
			</div>';

		// Did we hash?! :D :D :D
		if($hash->parse() != false)
			echo '
				<br />
				<div class="blogborder" style="width: 80%;">
					' . $hash->parse() . '
				</div>';
	}

	// Add some tabs
	elseif(adm_sect == 'menu') {

		// Do we want to edit this?
		if(!empty($_POST['tab_to_edit']) && !empty($vienara['tabs'][$_POST['tab_to_edit']])) {

			// Show the interface
			echo '
				<div class="cat_bg bg_color">
					' . show_string('tab_edit_single') . '
				</div>
				<div class="bg_color5 padding">
					<form action="' . Blog_file . '?app=admin&section=menu" method="post">
						<input type="hidden" name="tab_id" value="' . $_POST['tab_to_edit'] . '" />
						<table width="100%">
							<tr>
								<td width="50%"><strong>' . show_string('label') . '</strong>:</td>
								<td width="50%"><input type="text" name="menu_label" value="' . $vienara['tabs'][$_POST['tab_to_edit']]['tab_label'] . '" /></td>
							</tr>
							<tr>
								<td width="50%"><strong>' . show_string('href') . '</strong>:</td>
								<td width="50%"><input type="text" name="menu_href" value="' . $vienara['tabs'][$_POST['tab_to_edit']]['tab_link'] . '" /></td>
							</tr>
							<tr>
								<td width="50%"><strong>' . show_string('position') . '</strong>:</td>
								<td width="50%"><input type="text" name="menu_pos" size="2" maxlength="3" value="' . $vienara['tabs'][$_POST['tab_to_edit']]['tab_position'] . '" /></td>
							</tr>
						</table><br /><br />
						<input type="submit" value="' . show_string('submit') . '" />
					</form>
				</div>';
		}
		else {
			echo '
				<div class="cat_bg bg_color">
					' . show_string('edit_existing_tabs') . '
				</div>
				<form action="' . Blog_file . '?app=admin&section=menu" method="post">';

			// A base class
			$class = 5;

			// Show 'em
			foreach($vienara['tabs'] as $tab) {

				echo '
					<div class="bg_color' . $class . ' padding">
						<input type="radio" name="tab_to_edit" value="' . $tab['id_tab'] . '" /> ' . $tab['tab_label'] . '
					</div>';

				// Reset the color class
				$class = ($class == 5 ? 4 : 5);

				// Found it. :)
				$found = 1;
			}

			// Did we found anything?
			if(empty($found))
				echo show_string('no_tabs_available');

			echo '
					<br />
					<input type="submit" value="' . show_string('edit') . '" />
				</form><br /><br />
				<div class="cat_bg bg_color">
					' . show_string('new_tab') . '
				</div>
				<div class="bg_color5 padding">
					<form action="' . Blog_file . '?app=admin&section=menu" method="post">
						<input type="hidden" name="new" />
						<table width="100%">
							<tr>
								<td width="50%"><strong>' . show_string('label') . '</strong>:</td>
								<td width="50%"><input type="text" name="menu_label" value="' . show_string('my_tab') . '" /></td>
							</tr>
							<tr>
								<td width="50%"><strong>' . show_string('href') . '</strong>:</td>
								<td width="50%"><input type="text" name="menu_href" value="http://example.com" /></td>
							</tr>
							<tr>
								<td width="50%"><strong>' . show_string('position') . '</strong>:</td>
								<td width="50%"><input type="text" name="menu_pos" size="1" maxlength="3" value="" /></td>
							</tr>
						</table><br /><br />
						<input type="submit" value="' . show_string('submit') . '" />
					</form>
				</div><br /><br />
				<div class="cat_bg bg_color">
					' . show_string('remove_tab') . '
				</div>
				<ul>';

			foreach($vienara['tabs'] as $tab)
				echo '
					<li><a href="' . Blog_file . '?app=admin&section=menu&delete=' . $tab['id_tab'] . '"><img src="./images/delete_tab.png" alt="*" /></a> ' . $tab['tab_label'] . '</li>';

		echo '
				</ul>';
		}
	}


	// Repair and optimize tables
	elseif(adm_sect == 'repairtable') {

		echo '
			<div class="cat_bg bg_color">
				' . show_string('repair_optimize') . '
			</div>
			<div class="bg_color4">';

		// Done?
		if(isset($vienara['repair_fail'])) {

			// It failed. :(
			if($vienara['repair_fail'] == true)
				echo '
					<div class="result_bad">' . show_string('action_fail') . '</div>';

			// It worked :)
			elseif($vienara['repair_fail'] == false)
				echo '
					<div class="result_good">' . show_string('action_done') . '</div>';
		}

		echo '
			<form action="' . Blog_file . '?app=admin&section=repairtable" method="post">
			<div class="list_tables">';

		$variant = 5;

		foreach($vienara['admin_tables'] as $table) {
			echo '
				<div class="padding bg_color' . $variant . '"><input type="radio" name="repair_database" value="' . $table[0] . '" />' . $table[0] . '</div>';

			$variant = ($variant == 5 ? 4 : 5);
		}

		echo '
			</div>
				<br />
				<input type="checkbox" name="optimize" />' . show_string('rather_optimize') . '<br />
				<input type="submit" value="' . show_string('submit') . '" />
			</form>
			</div>';
	}

	// Show the help page
	elseif(adm_sect == 'help') {

		global $txt;

		// Show them all
		foreach($vienara['help_docs'] as $documentation) {

			// Show it
			echo '
				<br />
				<div class="cat_bg bg_color">
					<a href="help.php?id=' . $documentation['title'] . '">' . $txt['doc'][$documentation['title']] . '</a>
				</div>
				<div class="bg_color4 padding">
					' . $txt['doc']['doc_' . $documentation['title']] . '
				</div>';
		}
	}

	// Manage extensions
	elseif(adm_sect == 'extensions') {

		echo '
			<div class="cat_bg bg_color">
				' . show_string('extension_list') . '
			</div>';

		if(empty($vienara['extensions']))
			echo '
				<div class="padding">' . show_string('no_extensions') . '</div>';
		else {

			echo '
				<table style="width: 100%" cellspacing="0">
					<tr>
						<td class="bg_color3 padding" width="30%">' . show_string('ext_title') . '</td>
						<td class="bg_color3 padding" width="10%">' . show_string('ext_version') . '</td>
						<td class="bg_color3 padding" width="20%">' . show_string('ext_author') . '</td>
						<td class="bg_color3 padding" width="10%">' . show_string('ext_enabled') . '</td>
						<td class="bg_color3 padding" width="30%">' . show_string('ext_actions') . '</td>
					</tr>';

			$variation = 5;

			foreach($vienara['extensions'] as $extension) {

				echo '
					<tr>
						<td class="bg_color' . $variation . ' padding">' . $extension['title'] . '</td>
						<td class="bg_color' . $variation . ' padding">' . $extension['version'] . '</td>
						<td class="bg_color' . $variation . ' padding">' . $extension['author'] . '</td>
						<td class="bg_color' . $variation . ' padding">' . ($extension['enabled'] == true ? show_string('enabled') : show_string('disabled')) . '</td>
						<td class="bg_color' . $variation . ' padding">
							[<a href="' . Blog_file . '?app=admin&section=extensions&delete=' . urlencode($extension['dir']) . '">' . show_string('delete') . '</a>]
							[<a href="' . Blog_file . '?app=admin&section=extensions&changestatus=' . urlencode($extension['dir']) . '">' . ($extension['enabled'] == true ? show_string('disable') : show_string('enable')) . '</a>]
							[<a href="' . Blog_file . '?app=admin&section=extensions&install=' . urlencode($extension['dir']) . '">' . show_string('run_installer') . '</a>]
						</td>
					</tr>';

				$variation = ($variation == 5 ? 4 : 5);
			}

			echo '
				</table>';
		}

		echo '
			<br /><br />
			<div class="cat_bg bg_color">
				' . show_string('upload_ext') . '
			</div>
			<div class="padding bg_color5">
				<form enctype="multipart/form-data" action="' . Blog_file . '?app=admin&section=extensions" method="post">
					<input type="file" name="extension_archive" />
					<input type="submit" value="' . show_string('submit') . '" /> 
				</form>
			</div>';
	}

	// Manage pages
	elseif(adm_sect == 'managepages') {

		// Do we want to see the regular page screen?
		if(!isset($_GET['edit'])) {
			// Begin with the template
			echo '
				<div class="cat_bg bg_color">
					' . show_string('manage_pages') . '
				</div>
				<table width="100%" cellspacing="0">
					<tr>
						<td class="padding bg_color2" width="5%">' . show_string('page_id') . '</td>
						<td class="padding bg_color2" width="50%">' . show_string('page_title') . '</td>
						<td class="padding bg_color2" width="20%">' . show_string('page_header') . '</td>
						<td class="padding bg_color2" width="25%">' . show_string('page_tools') . '</td>
					</tr>';

			foreach($vienara['pages'] as $page)
				echo '
					<tr>
						<td class="padding bg_color5">' . $page['id_page'] . '</td>
						<td class="padding bg_color4"><a class="grey" href="' . Blog_file . '?app=site&id=' . $page['id_page'] . '">' . $page['page_title'] . '</a></td>
						<td class="padding bg_color5">' . ($page['show_header'] == 1 ? show_string('enabled') : show_string('disabled')) . '</td>
						<td class="padding bg_color4">
							<a href="' . Blog_file . '?app=admin&section=pages&delete=' . $page['id_page'] . '">' . show_string('page_delete') . '</a>
							<a href="' . Blog_file . '?app=admin&section=pages&edit=' . $page['id_page'] . '">' . show_string('page_edit') . '</a>
						</td>
					</tr>';

			echo '
				</table><br /><br />
				<div class="cat_bg bg_color">
					' . show_string('new_page') . '
				</div>
				<div class="bg_color5 padding">
					<form accept-charset="UTF-8" action="' . Blog_file . '?app=admin&section=pages" method="post">
						<table width="100%">
							<tr>
								<td width="20%"><strong>' . show_string('page_title') . ':</strong></td>
								<td width="80%"><input type="text" name="page_title" /></td>
							</tr>
							<tr>
								<td width="20%"><strong>' . show_string('show_header') . ':</strong></td>
								<td width="80%"><input type="checkbox" name="page_header" checked/></td>
							</tr>
							<tr>
								<td width="20%"><strong>' . show_string('page_content') . ':</strong></td>
								<td width="80%"><textarea class="editor new_post" rows="1" cols="1" name="page_content"></textarea></td>
							</tr>
						</table><br /><br />
						<input type="submit" value="' . show_string('submit') . '" />
					</form>
				</div>';
		}

		// Show the edit screen
		else {

			// Just echo stuff
			echo '
				<div class="cat_bg bg_color">
					' . show_string('edit_page') . '
				</div>
				<div class="bg_color5 padding">
					<form accept-charset="UTF-8" action="' . Blog_file . '?app=admin&section=pages" method="post">
						<input type="hidden" name="page_id" value="' . $vienara['pages'][$_GET['edit']]['id_page'] . '" />
						<table width="100%">
							<tr>
								<td width="20%"><strong>' . show_string('page_title') . ':</strong></td>
								<td width="80%"><input type="text" name="page_title" value="' . $vienara['pages'][$_GET['edit']]['page_title'] . '" /></td>
							</tr>
							<tr>
								<td width="20%"><strong>' . show_string('show_header') . ':</strong></td>
								<td width="80%"><input type="checkbox" name="page_header"' . ($vienara['pages'][$_GET['edit']]['show_header'] == 1 ? ' checked' : '') . ' /></td>
							</tr>
							<tr>
								<td width="20%"><strong>' . show_string('page_content') . ':</strong></td>
								<td width="80%"><textarea class="editor new_post" rows="1" cols="1" name="page_content">' . $vienara['pages'][$_GET['edit']]['page_body'] . '</textarea></td>
							</tr>
						</table><br /><br />
						<input type="submit" value="' . show_string('submit') . '" />
					</form>
				</div>';
		}
	}

	// The css edit page
	elseif(adm_sect == 'css') {

		// Just echo the form. Nothing fancy
		echo '
				<div class="cat_bg bg_color">
					' . show_string('style_edit') . '
				</div>
				<form action="' . Blog_file . '?app=admin&section=css" method="post">
					<div class="bg_color5 padding">
						<textarea name="new_style" class="editor new_post" rows="1" cols="1">' . $vienara['style'] . '</textarea><br /><br />
						<input type="checkbox" name="css_backup" checked="checked" /> ' . show_string('backup_css') . '
						<br /><br />
						<input type="submit" value="' . show_string('submit') . '" />
					</div>
				</form>';
	}

	// The terminal
	elseif(adm_sect == 'terminal') {

		echo '
			<div class="cat_bg bg_color">
				' . show_string('terminal') . '
			</div>
			<div class="padding bg_color5">
					' . $vienara['current_command'] . '
				<hr />
				<form action="' . Blog_file . '?app=admin&section=terminal" method="post">
					<input type="text" name="command" autofocus="autofocus" style="width: 80%;" />
					<input type="submit" value="' . show_string('submit') . '" />
				</form>
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

// Edit a blog
function vienara_template_edit($bloginfo = array())
{
	echo '
		<div id="editor">
			<div class="cat_bg bg_color newblog_title">
				' . show_string('edit_post') . '
			</div>
			<div class="padding bg_color5">
				<form action="' . Blog_file . '?app=admin&section=edit&id=' . $bloginfo['id_blog'] . '" method="post">
					<input type="hidden" name="adm_post" />
					<table width="100%">
						<tr class="subject">
							<td width="20%"><strong id="title_desc">' . show_string('post_title') . ':</strong></td>
							<td width="80%"><input type="text" id="post_title" name="post_title" value="' . $bloginfo['blog_title'] . '" /></td>
						</tr>
						<tr>
							<td width="20%" class="blog_msg"><strong>' . show_string('message') . ':</strong></td>
							<td width="80%"><textarea class="editor new_post" name="edit_content" rows="1" cols="1">' . br2nl($bloginfo['blog_content']) . '</textarea></td>
						</tr>
					</table>
					<input type="submit" id="editor_submit" value="' . show_string('submit') . '" />
					<a href="' . Blog_file . '?app=admin&section=blogs" class="more" style="color: white">' . show_string('cancel_edit') . '</a>
				</form>
			</div>
			<br />
		</div>';
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

// Show a page
function template_page($info = array())
{
	// Show it
	if($info['show_header'] == 1)
		echo '
			<div class="cat_bg bg_color">
				' . $info['page_title'] . '
			</div>';

	// Regular content
	echo '
			<div class="bg_color5 padding">
				' . $info['page_body'] . '
			</div>';
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
			<div class="bg_color padding">' . show_string('search_for') . '</div>
				<div class="bg_color5 padding"><input type="radio" name="type" value="normal" checked="checked" /> ' . show_string('search_normal') . '</div>
				<div class="bg_color4 padding"><input type="radio" name="type" value="images" /> ' . show_string('search_images') . '</div>
				<div class="bg_color5 padding"><input type="radio" name="type" value="maps" /> ' . show_string('search_maps') . '</div>
				<div class="bg_color4 padding"><input type="radio" name="type" value="videos" /> ' . show_string('search_videos') . '</div><br />
			<div class="bg_color padding">' . show_string('extra_search_args') . '</div>
				<div class="bg_color5 padding"><input type="checkbox" name="thissite"/> ' . show_string('search_this') . '</div>
				<div class="bg_color4 padding"><input type="checkbox" name="exact" /> ' . show_string('search_exact') . '</div><br /><br />
			<div class="bg_color padding"><a href="javascript:void(0);" onclick="$(\'#privacy\').slideToggle(\'slow\')">' . show_string('privacy_policy') . '</a></div>
			<div id="privacy" class="padding bg_color5" style="display: none;">
				' . show_string('google_policy') . '
			</div>
		</form>';
}
