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
	<script type="text/javascript" src="javascript/SCEditor.js?' . $vienara['setting']['css_cache_version'] . '"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="style.css?' . $vienara['setting']['css_cache_version'] . '" />
	<link rel="stylesheet" href="SCEditor.css?' . $vienara['setting']['css_cache_version'] . '" type="text/css" />
	<title>' . $vienara['setting']['title'] . ($vienara['setting']['enable_extra_title'] == 1 ? ' | ' . $vienara['setting']['extra_title'] : '') . '</title>';
	
	// Custom header scripts
	vienara_hook('html_header');

	// Custom css?
	if(!empty($vienara['setting']['custom_css']))
		echo '
	<style type="text/css">
		' . $vienara['setting']['custom_css'] . '
	</style>';	

	if(defined('Editor_BBC'))
		echo '
			<script>
				$(document).ready(function() {
					$("textarea.new_post").sceditorBBCodePlugin({
						// Buttons
						toolbar: "bold,italic,underline,strike|image,link|quote,code|source",
					});
				});
			</script>';
	elseif(defined('Editor_HTML'))
		echo '
			<script>
				$(document).ready(function() {
					$("textarea.new_post").sceditor({
						// Buttons
						toolbar: "bold,italic,underline,strike|image,link|source",
					});
				});
				function toggleEditor() {
					$("textarea.new_post").data("sceditor").toggleTextMode();
				}
			</script>';

	echo '
</head>
<body class="vienara">
	<div id="editorhere_after"></div>
	<div id="wrapper" style="width: ' . $vienara['setting']['width'] . '%;">
		<div class="headerbar">
			' . show_string('cur_time') . ': ' . $vienara['cur_time'] . '
		</div>
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
	if(!isset($_GET['app']) && !isset($_GET['blog']) && !isset($_GET['done']))
		vienara_page($vienara['blog_count'], Blog_file . '?page=');

		echo '
					<br /><br />' . ($vienara['setting']['top_button'] == 1 ? '<a href="javascript:void(0);" onclick="$(\'html, body\').animate({scrollTop:0}, \'slow\');">' . show_string('top') . '</a>' : '') . '
				</div>
			</div>
		</div>
	</div>
	<div class="copyright">
		<a href="' . Website_Url . '">' . show_string('powered_by') . 'Vienara ' . Version . '</a> | <a href="http://graywebhost.com">' . show_string('sponsored_by') . 'Graywebhost</a><br />
		' . show_string('icons_by') . '<a href="http://www.famfamfam.com/lab/icons/silk/">FamFamFam</a> ' . show_string('and') . ' <a href="http://www.fatcow.com/free-icons">Fatcow</a>' . ($vienara['setting']['enable_custom_copyright'] == 1 ? '
			<br />' . (!empty($vienara['setting']['copyright_link_to']) ? '<a href="' . $vienara['setting']['copyright_link_to'] . '">' : '') . $vienara['setting']['custom_copyright'] . (!empty($vienara['setting']['copyright_link_to']) ? '</a>' : '') : '') . '<br />
		<a href="' . Blog_file . '?rss">' . show_string('rss') . '</a> | <a href="' . Blog_file . '?mobile">' . show_string('simple_theme') . '</a>
	</div>
</body>
</html>';
}

// Show a blog
function vienara_show_blog($information = '', $is_status = false, $single = false)
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
			' . ($single ? '' : '<div class="blogpost">') . '
				<div class="floatleft">
					<div class="date">
						<span class="daymonth">' . parse_date(date("M j", $information['post_date'])) . '</span><br />
						' . parse_date(date("Y", $information['post_date'])) . '
					</div>	
				</div>
				<div style="width: 90%">
					<div class="title">
						<a href="' . Blog_file . '?blog=' . $information['id_blog'] . '" id="' . $information['id_blog'] . '">' . $information['blog_title'] . '</a>
					</div>
					' . show_string('posted_on') . ': ' . parse_date(date("F j, Y, g:i a", $information['post_date']), true) . '
					' . (vienara_is_logged() ? '<br /><a href="' . Blog_file . '?app=admin&amp;section=edit&amp;id=' . $information['id_blog'] . '">[' . show_string('edit') . ']</a>' : '') . '
				</div>
				<br class="clear" />
				<div class="blog_content">
					' . $information['blog_content'] . '
					' . (!$single ? '<br /><br /><a href="' . Blog_file . '?blog=' . $information['id_blog'] . '">' . show_string('read_more') . '</a>' : '');

			// Are comments enabled?
			if($vienara['setting']['reg_comments'] == 1 && !$single)
				echo ' 
					| <a href="' . Blog_file . '?blog=' . $information['id_blog'] . '#new_comment">' . show_string('reply_this') . '</a>';

			echo '
					' . ($vienara['setting']['enable_likes'] == 1 ? '<br /><br /><iframe src="https://www.facebook.com/plugins/like.php?href=' . $vienara['setting']['blog_url'] . '?blog=' . $information['id_blog'] . '" style="border:none!important; width:450px; height:80px"></iframe>' : '') . '
				' . ($vienara['setting']['enable_likes'] == 1 ? '<br /><div class="fb-comments" data-href="' . $vienara['setting']['blog_url'] . '?blog=' . $information['id_blog'] . '" data-num-posts="5" data-width="470"></div>' : '') . '
				</div>
			' . ($single ? '' : '
			</div> ');

	// The comment thing. Only if we're viewing a single post, though.
	if($single && $vienara['setting']['reg_comments'] == 1) {

		echo '
			<br />
			<div class="cat_bg bg_color">
				' . show_string('comments') . '
			</div>
				<table width="100%" cellspacing="1">';

		// Get the comments
		foreach($vienara['comments'] as $comment)
			echo '
					<tr>
						<td width="15%" class="padding bg_color5" valign="top"><span style="font-size: 14px;">' . $comment['username'] . '</span><br />
							' . ($comment['isadmin'] == 1 ? show_string('administrator') . '<br /><img src="' . $vienara['setting']['avatar'] . '" alt="" /><br />' : '<img src="images/vienara_guest.png" alt="" /><br />') . '
							' . (!empty($comment['website']) ? '<a href="' . $comment['website'] . '">[' . show_string('website') . ']</a><br />' : '') . '
							' . (vienara_is_logged() ? '<a href="http://www.stopforumspam.com/ipcheck/' . $comment['ip'] . '">[' . show_string('trace_ip') . ']</a>' : '') . '
						</td>
						<td width="85%" class="padding bg_color4" valign="top">
							' . (vienara_is_logged() ? '<a href="' . Blog_file . '?blog=' . $information['id_blog'] . '&deletecomment=' . $comment['id_comment'] . '">[' . show_string('delete') . ']</a><br />' : '') . '
							' . $comment['message'] . '<br />
							<em>' . show_string('post_date') . ': ' . parse_date(date("F j, Y, g:i a", $comment['poster_time'])) . '</em>
						</td>
					</tr>';

		// No comments?
		if(empty($vienara['comments']))
			echo '
					<tr>
						<td colspan="2" class="padding bg_color5">
							' . show_string('no_comments') . '
						</td>
					</tr>';

		echo '
				</table>
			<br />
			<div class="cat_bg bg_color">
				<a href="#new_comment" name="new_comment">' . show_string('new_comment') . '</a>
			</div>
			<div class="padding bg_color5">
					<form action="' . Blog_file . '?blog=' . $information['id_blog'] . '" method="post">
						<table width="100%">
							<tr>
								<td width="20%" valign="top"><strong>' . show_string('name') . ':</strong></td>
								<td width="80%"><input type="text" name="username" /></td>
							</tr>
							<tr>
								<td width="20%" valign="top"><strong>' . show_string('website') . ':</strong></td>
								<td width="80%"><input type="text" name="website" /></td>
							</tr>
							<tr>
								<td width="20%" valign="top"><strong>' . show_string('message') . ':</strong></td>
								<td width="80%"><textarea class="editor" name="message" rows="5" cols="50"></textarea></td>
							</tr>
						</table><br /><br />
						' . show_string('comment_policy') . '<br /><br />
						<input type="submit" value="' . show_string('submit') . '" />
					</form>
			</div>';
	}
}

// The help template
function template_help($doc_message = '', $title = '')
{
	global $is_rtl, $vienara;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"' . $is_rtl . ' lang="' . $vienara['lang']['code'] . '">
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

// We're done with something.
function template_done($link = '')
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
	global $php_pages;

	// Show the page
	if($info['is_php'] == 0)
		echo $info['page_body'];
	elseif($info['is_php'] == 1 && $php_pages == true)
		eval($info['page_body']);
	else
		echo $info['page_body'];
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
			<div id="privacy" class="padding bg_color5">
				' . show_string('google_policy') . '
			</div>
		</form>';
}
