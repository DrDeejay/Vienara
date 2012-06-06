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
* @version: 1.0 Alpha 1
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.co.cc
*
* @license MIT
*/
define('Vienara', 1);

// Start sessions
session_start();

// Make our pages pretty. Remove ugly stuff
function vienara_pretty($buffer)
{
	// Replace all slashes
	$buffer = stripslashes($buffer);

	// Fix bad characters
	$char_table = get_html_translation_table(HTML_ENTITIES);
	
		// Replace it
		foreach($char_table as $key => $value) {

			// We don't need this character
			if($key == '&' || $value == '&')
				continue;

			// This shouldn't be real html
			$key = str_replace('<', '&lt;', $key);
			$key = str_replace('>', '&gt;', $key);

			// Now change the character
			$buffer = str_replace($value, $key, $buffer);
		}		

	// Return
	return $buffer;
}

// Make sure header redirects work
ob_start('vienara_pretty');

// The vienara array!
$vienara = array();

// What version are we using? And what is the link to the website?
define('Version', '1.0 Alpha 1');
define('Website_Url', 'http://vienara.co.cc'); // Don't change this!
define('Blog_file', 'index.php');
define('Branch', '1.0');
define('JqueryVersion', '1.7.2');

// Have we set a blog?
if(isset($_GET['blog'])) {

	// Redirect to the blog
	header('Location: ' . Blog_file . '#' . $_GET['blog']);

	// Exit, just to be sure
	exit;
}

// The extension directory
$vienara['extension_dir'] = 'extensions';

// Because sometimes, we need to fix stuff
function br2nl($text = '')
{
	// Just replace.
	$text = str_replace('<br />', '', $text);
	$text = str_replace('<br>', '', $text);

	return $text;
}

// The screen of death
function fatal_error($error = '', $include_fail = false)
{
	// We cannot load templates if we don't have the file that stores the templates
	if($include_fail == 'Template.php')
		die($error);

	// Call the right template
	screenofdeath_header();

	// Show the error
	echo $error;

	// We're done
	screenofdeath_footer();

	// And die
	die;
}

// Important includes
$vienara['includes'] = array(
	'Config.php',
	'Template.php',
	'xensql/XenSql.php',
	'Imik.php'
);

	// Include them
	foreach($vienara['includes'] as $include) {

		// Try to include it
		$result = @include $include;

		// Did it work?
		if($result == false)
			fatal_error('Failed to include file: ' . $include);
	}

// Connect with the database
$db = xensql_connect($db_settings['server'], $db_settings['username'], $db_settings['password'], $db_settings['dbname']);

	// Did it work?
	if(!$db)
		fatal_error('Database connection failed.');

// Load a class
function loadClass($class_name = '')
{
	// Do we have a class name set?
	if(empty($class_name))
		return;

	// We do
	else
		include 'classes/Class-' . $class_name . '.php';

	// Y u expectz cookiez? :D
	return true;
}

// Get the Decoda class
include 'decoda/Decoda.php';

// Setup imik
$imik = new Imik;

// We need a new array for the settings
$vienara['setting'] = array();

// Retrieve the settings from the database
$result = xensql_query("
	SELECT id, value
		FROM {db_pref}settings
");
	
	// Merge them into a settings array
	foreach($result as $setting)
		$vienara['setting'][$setting['id']] = $setting['value'];

// Get a hook from an extension
function vienara_hook($hook_name = '')
{
	global $vienara;

	// Hmm..
	if(!file_exists($vienara['extension_dir']))
		return;

	// Have we enabled extensions?
	if($vienara['setting']['ext_enable'] == 0)
		return;

	// Get each directory in the extension directory
	if(@$direction = opendir($vienara['extension_dir']))
	{
		// Use a while to create arrays
		while(false !== ($file = readdir($direction)))
		{
			// Don't use stuff we're not going to use anyway
			if($file == '.')
				continue;
			if($file == '..')
				continue;			

			// Is this extension disabled?
			if(file_exists($vienara['extension_dir'] . '/' . $file . '/disabled.ext') && !$vienara['setting']['ignore_disabled_ext'] == 1)
				continue;
			
			// Check for the hook
			if(file_exists($vienara['extension_dir'] . '/' . $file . '/' . $hook_name . '.hook.php')) {

				// Include it
				include($vienara['extension_dir'] . '/' . $file . '/' . $hook_name . '.hook.php');
			}
		
			// Hook doesn't exist here
			else
				continue;
		}
	}
	// It didn't. We're sure about that
	else
		fatal_error('Failed to load extensions. Requested hook: ' . $hook_name);
}

// Extra includes
vienara_hook('pre_include');

// Create a new array, which we will need for the custom tabs
$vienara['tabs'] = array();

	// Get those tabs!
	$result = xensql_query("
		SELECT id_tab, tab_link, tab_label, tab_position
			FROM {db_pref}menu
			ORDER BY tab_position ASC
	");

	// Add those to the array
	foreach($result as $tab)
		$vienara['tabs'][$tab['id_tab']] = $tab;

// Set the default timezone
date_default_timezone_set($vienara['setting']['timezone']);

// Always load the english language file
include 'languages/english_usa.php';

// What language should we load?
if(file_exists('languages/' . $vienara['setting']['language'] . '.php'))
	include 'languages/' . $vienara['setting']['language'] . '.php';

// Are we using an RTL language?
if(!empty($vienara['lang'][$vienara['setting']['language']]['rtl']))
	$is_rtl = ' dir="rtl"';
else
	$is_rtl = '';

// This will make sure we don't get stupid errors
function show_string($string = '')
{
	global $txt;

	// Is it set?
	if(!isset($txt[$string]))
		return;

	// Just display it
	return $txt[$string];
}

// Parse dates
function parse_date($date = '')
{
	// An array with things that should be translated
	$dates = array(
		'Jan' => show_string('jan'),
		'Feb' => show_string('feb'),
		'Mar' => show_string('mar'),
		'Apr' => show_string('apr'),
		'May' => show_string('may'),
		'Jun' => show_string('jun'),
		'Jul' => show_string('jul'),
		'Aug' => show_string('aug'),
		'Sep' => show_string('sep'),
		'Oct' => show_string('oct'),
		'Nov' => show_string('nov'),
		'Dec' => show_string('dec')
	);

	// Translate them
	foreach($dates as $key => $value)
		$date = str_replace($key, $value, $date);

	// Return
	return $date;
}

// Get the blogcount
$vienara['blog_count'] = xensql_count_rows("
	SELECT id_blog, blog_title, blog_content, published, post_date
		FROM {db_pref}content
		" . (!isset($_GET['app']) ? 'WHERE published = 1' : '') . "
");

// Login!
function vienara_act_login()
{
	global $vienara;

	// Is it the right one?
	if(isset($_POST['password'])) {

		// Does it match?
		if(sha1($_POST['password']) != $vienara['setting']['password'])
			die_nice(show_string('incorrect_pass'));

		// Yup!
		else {

			// Set the session
			$_SESSION['vienara_user_session'] = sha1($_POST['password']);

			// We're done with that
			header('Location: ' . Blog_file);
		}
	}
	
	// Nothing set.
	else
		vienara();
}

// Logout when we are logged in
function vienara_act_logout()
{
	// Are we logged in?
	if(isset($_SESSION['vienara_user_session']))
		unset($_SESSION['vienara_user_session']);

	// We're done
	die_nice(show_string('logged_out'));
}

// Delete a blogpost. For example if we accidentally posted something
function vienara_act_delete()
{
	global $db;

	// Are we logged in?
	if(!vienara_is_logged())
		vienara();

	// Do we have anything set?
	if(empty($_GET['id']))
		vienara();

	// Escape the id
	$_GET['id'] = xensql_escape_string($_GET['id']);

	// Empty variable!
	$count = 0;

	// We can check if it exists. So let's do that
	$result = xensql_query("
		SELECT id_blog
			FROM {db_pref}content
			WHERE id_blog = '{id}'
	");

		// Do we have any results?
		foreach($result as $meh)
			$count++;

	// Results?
	if($count == 0)
		die_nice(show_string('blog_not_found'));

	// Remove it
	xensql_query("
		DELETE
			FROM {db_pref}content
			WHERE id_blog = '{id}'
	", true);

	// Now show the blogs
	vienara();
}

// Setup the Viencode class. But load it first
loadClass('Viencode');

	// Set it up
	$viencode = new Viencode;

// Check the password
function vienara_is_logged()
{
	global $vienara;

	// Is the session set?
	if(!isset($_SESSION['vienara_user_session']))
		return false;

	// Yes, so does it match?
	elseif($_SESSION['vienara_user_session'] == $vienara['setting']['password'])
		return true;

	// Nope
	else
		return false;
}

// What action should we call?
function vienara_get_app($application = '')
{
	global $vienara, $vienara_acts;

	// Did we set anything?
	if(empty($application))
		vienara();
	
	// Yup
	else {

		// The action array
		$vienara_acts = array(
			'login' => 'login',
			'logout' => 'logout',
			'delete' => 'delete',
			'admin' => 'admin',
			'site' => 'site'
		);

		// Extra actions
		vienara_hook('apps');

		// Do we want to show the search page?
		if($vienara['setting']['enable_search'] == 1)
			$vienara_acts['search'] = 'search';

		// Is it set?
		if(isset($vienara_acts[$application]))
			call_user_func('vienara_act_' . $application);

		// Nope
		else
			vienara();
	}
}

// Display the footer and die
function die_nice($message = '')
{
	// Show the message
	echo $message;

	// And the footer
	vienara_footer();

	// Die!
	die;
}

// This displays the frontpage
function vienara()
{
	global $vienara, $viencode;

	// We're viewing the frontpage
	define('VienaraFront', 1);

	// Just in case
	$end = 10;
	
	// Int?
	if(isset($_GET['page']) && !is_numeric($_GET['page']))
		die_nice(show_string('bad_request'));

	// We didn't set a page
	if(!isset($_GET['page']))
		$_GET['page'] = 1;

	// How many messages should we load?
	if(isset($_GET['page'])) {

		// Begin with..
		$begin = ($_GET['page']-1) * $vienara['setting']['blogsperpage'];

		// Or is it one?
		if($_GET['page'] == 1)
			$begin = 0;

		// How many should we load?
		$end = $vienara['setting']['blogsperpage'];
	}

	// Just load 10
	else
		$begin = 0;

	// Get the blogs from the blog table
	$result = xensql_query("
		SELECT id_blog, blog_title, blog_content, published, post_date, is_status
			FROM {db_pref}content
			WHERE published = 1
			ORDER BY post_date " . $vienara['setting']['order'] . "
			LIMIT $begin, $end
	");

		// Get the right template
		foreach($result as $blog) {

			// We need new lines
			$blog['blog_content'] = nl2br($blog['blog_content']);

			// Parse!
			$blog['blog_content'] = $viencode->parse($blog['blog_content']);

			// Hooks!
			vienara_hook('single_blog');

			// Finally show everything
			vienara_show_blog($blog, ($blog['is_status'] == 1 ? true : false));
		}
}

// Check if we want to post a message
if(!empty($_POST['content'])) {

	// We should be logged in
	if(!vienara_is_logged())
		die_nice(show_string('not_logged'));

	// No title?
	if(empty($_POST['post_title']))
		$_POST['post_title'] = show_string('no_title');

	// Important fields
	$important_fields = array(
		'post_title',
		'content'
	);

		// Are they empty?
		foreach($important_fields as $key => $value) {

			// Hmm..
			if(empty($_POST[$value]))
				die_nice(show_string('fill_in_all_fields'));

			// Make sure the content is safe
			$_POST[$value] = xensql_escape_string($_POST[$value]);

			// No html
			$_POST[$value] = htmlspecialchars($_POST[$value], ENT_NOQUOTES, 'UTF-8', false);
		}

	// Do we want it approved?
	if(isset($_POST['adm_post']) && isset($_POST['approved']))
		$approved = 1;
	elseif(isset($_POST['adm_post']) && !isset($_POST['approved']))
		$approved = 0;
	else
		$approved = 1;

	// And is this a status update?
	if(isset($_POST['adm_post']) && isset($_POST['is_status']))
		$is_status = 1;
	elseif(isset($_POST['adm_post']) && !isset($_POST['is_status']))
		$is_status = 0;
	else
		$is_status = 1;

	// Add it into the database
	xensql_query("
		INSERT 
			INTO {db_pref}content			
			VALUES(
				'',
				'" . $_POST['post_title'] . "',
				'" . $_POST['content'] . "',
				UNIX_TIMESTAMP(),
				'" . $approved . "',
				'" . $is_status . "'
		)
	");
}

// Update settings, to make sure we have the right value in the database
function vienara_saveSetting($setting = '', $is_check = '')
{
	// Did we set it?
	if(empty($_POST[$setting]) && !empty($is_check))
		$_POST[$setting] = 0;

	// No sql injections
	$_POST[$setting] = xensql_escape_string($_POST[$setting]);

	// No html!
	$_POST[$setting] = htmlspecialchars($_POST[$setting], ENT_NOQUOTES, 'UTF-8', false);

	// Hmm
	$_POST[$setting] = nl2br($_POST[$setting]);

	// Is it a checkbox?
	if($_POST[$setting] == 'on' && !empty($is_check))
		$_POST[$setting] = 1;
	elseif($_POST[$setting] == 'off' && !empty($is_check))
		$_POST[$setting] = 0;	

	// Update it
	xensql_query("
		UPDATE {db_pref}settings
			SET value = '" . $_POST[$setting] . "'
			WHERE id = '" . $setting . "'
	", true);
}

// Create a new array
$vienara['languages'] = array();

// Get the languages
if(@$directory = opendir('languages'))
{
	// Use a while to create arrays
	while(false !== ($file = readdir($directory)))
	{
		// Don't use stuff we're not going to use anyway
		if($file == '.')
			continue;
		if($file == '..')
			continue;
		elseif($file == 'index.html')
			continue;		

		// Remove the extension
		$file = str_replace('.php', '', $file);		
	
		// Just add it to the array
		$vienara['languages'][] = $file;
	}
}

// Show rss feeds
if(isset($_GET['rss'])) {

	// Get the class file
	loadClass('RSS');

		// Set it up
		$feed = new RSS;

		// Get the setup feed function
		$feed->setup();

	// Nothing left to show
	die;
}

// Get a page
function vienara_act_site($all = false)
{
	global $vienara;

	// Make it safe
	if(empty($_GET['id']) && !$all)
		die_nice(show_string('page_not_found'));
	elseif(!$all)
		$page = xensql_escape_string($_GET['id']);
	elseif(!$all && !is_numeric($_GET['id']))
		die_nice(show_string('page_not_found'));

	// Empty array.
	$return_page = array();

	// Get the page(s) from the database
	$result = xensql_query("
		SELECT id_page, page_body, page_title, show_header
			FROM {db_pref}pages
			" . ($all == false ? "WHERE id_page='$page'" : "") . "
			ORDER BY page_title ASC
	");

	// Fetch through those pages
	foreach($result as $p) {

		// Found it
		$found = 1;

		// Show everything?
		if($all == true)
			$return_page[$p['id_page']] = $p;

		// Nope, just one.
		else
			template_page($p);
	}

	// Check if we need all
	if($all == true)
		return $return_page;

	// Found?
	if(!isset($found))
		die_nice(show_string('page_not_found'));
}

// Search through the site
function vienara_act_search()
{
	global $vienara;

	// There might be a chance that we have already entered something to search for.
	if(isset($_POST['keywords'])) {

		// Search types
		$types = array(
			'normal' => 'normal',
			'images' => 'images',
			'maps' => 'maps',
			'videos' => 'videos'
		);

		// Ok, we need to make sure that we have a type set
		if(empty($_POST['type']))
			$_POST['type'] = 'normal';
		elseif(!in_array($_POST['type'], $types))
			$_POST['type'] = 'normal';

		// We need valid urls
		$_POST['keywords'] = str_replace(' ', '+', $_POST['keywords']);

		// Exact values?
		if(isset($_POST['exact']))
			$_POST['keywords'] = '"' . $_POST['keywords'] . '"';

		// And do we only want results from this site?
		if(isset($_POST['thissite']))
			$_POST['keywords'] .= ' site:' . $vienara['setting']['blog_url'];

		// Define the modus
		if($_POST['type'] == 'normal')
			$modus = 'search';
		elseif($_POST['type'] == 'images')
			$modus = 'imghp';
		elseif($_POST['type'] == 'maps')
			$modus = 'maps';
		elseif($_POST['type'] == 'videos')
			$modus = 'videohp';
		else
			$modus = 'search';

		// The google url
		$url = 'http://www.google.com/' . $modus . '?q=' . $_POST['keywords'];

		// Clean the contents of the screen
		ob_get_clean();

		// Redirect to Google!
		header('Location: ' . $url);

		// An exit, in case redirects don't work
		exit(show_string('search_fail'));
	}

	// There isn't much to do here. Make sure we show the template
	template_search();
}

// Remove a directory and all its files
function remove_dir($dir = '')
{
	// Get the files in this directory
	$files = scandir($dir);

	// Get through each file
	foreach($files as $file) {

		// No dots
		if($file == '.')
			continue;
		elseif($file == '..')
			continue;

		// Is this a dir?
		if(is_dir($file))
			remove_dir($file);
		else
			unlink($dir . '/' . $file);
	}

	// Remove.
	rmdir($dir);
}

// Extract a zip archive
function zip_extract($filename, $directory)
{
	global $vienara;

	// Create the directory
	mkdir($vienara['extension_dir'] . '/' . $directory);

	// Give it the right permissions
	chmod($vienara['extension_dir'] . '/' . $directory, 0775);

	// Open the file
	$zip = zip_open($filename);

	// Get through the files we have just created
	while($file = zip_read($zip)) {

		// Get the entryname
		$entry = zip_entry_name($file);

		// Is this a directory?
		if(!strpos($entry, '.')) {

			// Create the directory
			mkdir($vienara['extension_dir'] . '/' . $directory . '/' . $entry);

			// Continue
			continue;
		}

			// Get the file
			$new_file = zip_entry_read($file);

			// Create it
			$result = fopen(str_replace('.zip', '', $filename) . '/' . $entry, 'w+');

			// Write it
			fwrite($result, $new_file);

			// Close.
			fclose($result);
	}

	return $vienara['extension_dir'] . '/' . $directory;
}

// The administration panel. Isn't it pretty?
function vienara_act_admin()
{
	global $vienara, $admin;

	// Wait. We are logged in, right?
	if(!vienara_is_logged())
		die_nice(show_string('admin_not_allowed'));

	// Setup a fresh admin array
	$admin = array();

	// Setup the title
	$admin['title'] = $vienara['setting']['title'] . ' - ' . show_string('admin');

	// This will setup the sidebar
	$admin['sidebar'] = array(
		'home' => array(
			'title' => show_string('index'),
			'href' => Blog_file . '?app=admin',
			'show' => true,
			'icon' => 'home.png'
		),
		'extensions' => array(
			'title' => show_string('extensions'),
			'href' => Blog_file . '?app=admin&section=extensions',
			'show' => true,
			'icon' => 'plugin.png'
		),
		'terminal' => array(
			'title' => show_string('terminal'),
			'href' => Blog_file . '?app=admin&section=terminal',
			'show' => true,
			'icon' => 'terminal.png'
		),
		'post_blog' => array(
			'title' => show_string('new_post'),
			'href' => Blog_file . '?app=admin&section=newblog',
			'show' => true,
			'icon' => 'new.png'
		),
		'blog_list' => array(
			'title' => show_string('list_blogs'),
			'href' => Blog_file . '?app=admin&section=blogs',
			'show' => true,
			'icon' => 'blogs.png'
		),
		'settings' => array(
			'title' => show_string('settings'),
			'href' => Blog_file . '?app=admin&section=settings',
			'show' => true,
			'icon' => 'settings.png'
		),
		'pages' => array(
			'title' => show_string('manage_pages'),
			'href' => Blog_file . '?app=admin&section=pages',
			'show' => true,
			'icon' => 'page_edit.png'
		),
		'password' => array(
			'title' => show_string('change_pass'),
			'href' => Blog_file . '?app=admin&section=password',
			'show' => true,
			'icon' => 'password.png'
		),
		'hash' => array(
			'title' => show_string('hash'),
			'href' => Blog_file . '?app=admin&section=hash',
			'show' => true,
			'icon' => 'hash.png'
		),
		'edit_menu' => array(
			'title' => show_string('edit_menu'),
			'href' => Blog_file . '?app=admin&section=menu',
			'show' => true,
			'icon' => 'menu_edit.png'
		),
		'table_repair' => array(
			'title' => show_string('repair_optimize'),
			'href' => Blog_file . '?app=admin&section=repairtable',
			'show' => true,
			'icon' => 'wrench.png'
		),
		'style_edit' => array(
			'title' => show_string('style_edit'),
			'href' => Blog_file . '?app=admin&section=css',
			'show' => true,
			'icon' => 'style_edit.png'
		),
		'help' => array(
			'title' => show_string('help_docs'),
			'href' => Blog_file . '?app=admin&section=help',
			'show' => true,
			'icon' => 'help.png'
		),
		'logout' => array(
			'title' => show_string('logout'),
			'href' => Blog_file . '?app=logout',
			'show' => true,
			'icon' => 'logout.png'
		),
	);

	// Hooks!
	vienara_hook('adm_menu');

	// The admin notice!
	$admin['notice'] = array(
		'class' => 'notice',
		'string' => show_string('admin_welcome_notice')
	);

	// The main admin function. This is the function that show's the welcome thing. Currently, it only displays credits
	function admin_main()
	{
		global $vienara;

		// Get the changelog
		$vienara['changelog'] = @file_get_contents('changelog.txt');

			// Wipe out html
			$vienara['changelog'] = htmlspecialchars($vienara['changelog']);

			// Make it pretty
			$vienara['changelog'] = nl2br($vienara['changelog']);

		// We do have one right?
		if(empty($vienara['changelog']))
			$vienara['changelog'] = show_string('changelog_fail');

		// We're using the main admin stuff
		define('adm_sect', 'main');
	}

	// Hash text strings
	function admin_section_hash()
	{
		// Define where we are
		define('adm_sect', 'hash');
	}

	// Define the credits
	$admin['credits'] = array(
		'team' => array(
			'label' => show_string('team'),
			'teams' => array(
				'developers' => array(
					'label' => show_string('developers'),
					'members' => array(
						'Dr. Deejay'
					),
				)
			)
		),
		'special_thanks' => array(
			'label' => show_string('special_thanks'),
			'teams' => array(
				'family' => array(
					'label' => show_string('friends'),
					'members' => array(
						'Yoshi2889',
						'Lagom',
						'Fivang'
					),
				),
				'credits' => array(
					'label' => show_string('credits'),
					'members' => array(
						'<a href="http://www.famfamfam.com/lab/icons/silk/">FamFamFam</a>',
						'<a href="http://www.fatcow.com/free-icons">Fatcow</a>',
						'<a href="http://milesj.me/code/php/decoda">Decoda</a>'
					),
				)
			)
		),
		'extensions' => array(
			'label' => show_string('extensions'),
			'teams' => array()
		)
	);

	// Credits!
	vienara_hook('adm_credits');
	
	// This calls the new blog template
	function admin_section_newblog()
	{
		// Where are we...?
		define('adm_sect', 'newblog');
	}

	// Call all of the blogs, so the admin can take a look at them
	function admin_section_blogs()
	{
		global $admin_show, $vienara;

		// Just in case
		$end = 10;
	
		// Int?
		if(isset($_GET['page']) && !is_numeric($_GET['page']))
			die_nice(show_string('bad_request'));

		// We didn't set a page
		if(!isset($_GET['page']))
			$_GET['page'] = 1;

		// How many messages should we load?
		if(isset($_GET['page'])) {

			// Begin with..
			$begin = ($_GET['page']-1) * $vienara['setting']['blogsperpage'];

			// Or is it one?
			if($_GET['page'] == 1)
				$begin = 0;

			// How many should we load?
			$end = $vienara['setting']['blogsperpage'];
		}

		// Just load 10
		else
			$begin = 0;

		// Use a query to retrieve blogs
		$result = xensql_query("
			SELECT id_blog, blog_title, blog_content, published, post_date
				FROM {db_pref}content
				ORDER BY post_date DESC
				LIMIT $begin, $end
		");

			// Show them all. :)
			$admin_show = $result;

		// What admin section are we in?
		define('adm_sect', 'blogs');
	}

	// And this will call the change password template
	function admin_section_password()
	{
		// Define where we are
		define('adm_sect', 'password');
	}

	// Edit our settings. This isn't too hard, though.
	function admin_section_settings()
	{
		global $vienara, $admin;
	
		// Get the timezone class
		loadClass('Timezones');

		// Setup the class
		$timezones = new Timezone();

		// The list of settings
		$admin['settings'] = array(
				'title' => array('text', 'blog_title'),
				'language' => array('select', 'language'),
				'blog_url' => array('text', 'blog_url'),
				'css_cache_version' => array('text', 'css_cache_version'),
			'',
				'blogsperpage' => array('number', 'items_per_page'),
				'width' => array('number', 'width'),
				'order' => array('select', 'order', array('desc', 'asc')),
			'',
				'top_button' => array('check', 'top_button'),
				'menu_icons' => array('check', 'menu_icons'),
			'',
				'enable_extra_title' => array('check', 'enable_extra_title'),
				'extra_title' => array('text', 'extra_title'),
			'',
				'timezone' => array('select', 'timezone', $timezones->zones()),
			'',
				'notice' => array('largetext', 'notice'),
			'',
				'enable_custom_copyright' => array('check', 'enable_custom_copyright'),
				'custom_copyright' => array('text', 'custom_copyright'),
				'copyright_link_to' => array('text', 'copyright_link_to'),
			'',
				'enable_likes' => array('check', 'enable_likes'),
				'enable_comments' => array('check', 'enable_comments'),
				'quick_status' => array('check', 'quick_status'),
				'enable_search' => array('check', 'enable_search'),
			'',
				'avatar' => array('text', 'avatar'),
			'',	
				'ignore_disabled_ext' => array('check', 'ignore_disabled_ext'),
				'ext_enable' => array('check', 'ext_enable'),
		);

		// Extra settings! ;)
		vienara_hook('settings');

		// Do we want to save it?
		if(isset($_POST['settings'])) {

			// Get through the settings array
			foreach($admin['settings'] as $key => $value) {

				// Is it a divider?
				if(is_numeric($key))
					continue;

				// Should it be numeric?
				if(isset($_POST[$key]) && !is_numeric($_POST[$key]) && $value[0] == 'number')
					die_nice(show_string('please_numeric'));

				// Is this setting set?
				if(isset($_POST[$key]))
					vienara_saveSetting($key, ($value[0] == 'check' ? true : ''));

				// PHP is like a little kid that doesn't know how to behave
				elseif(empty($_POST[$key]) && $value[0] == 'check')
					vienara_saveSetting($key, ($value[0] == 'check' ? true : ''));
			}

			// Say that we are done
			done('?app=admin&section=settings');
		}

		// Define where we are
		define('adm_sect', 'settings');
	}

	// Publish a blogpost
	function admin_section_publish()
	{
		// Hmm..
		if(empty($_GET['id'])) {

			// Show the blog list
			admin_section_blogs();
	
			// Return
			return;
		}

		// Get the publish class file
		loadClass('Publish');

		// Set it up
		$pub = new Publish;

		// Publish!
		$pub->do_publish($_GET['id']);
		
		// We're done!
		done('?app=admin&section=blogs');
	}

	// Edit a blog. Quite simple, no?
	function admin_section_edit()
	{
		// Important checks
		if(!is_numeric($_GET['id']))
			admin_section_blogs();
		
		// Make it safe bro
		$_GET['id'] = xensql_escape_string($_GET['id']);

		// Edit a blogpost!
		if(!empty($_POST['edit_content'])) {

			// Title empty?
			if(empty($_POST['post_title']))
				die_nice(show_string('fill_in_all_fields'));

			// Make them safe. Both.
			$_POST['post_title'] = xensql_escape_string($_POST['post_title']);
			$_POST['edit_content'] = xensql_escape_string($_POST['edit_content']);

			// No html
			$_POST['post_title'] = htmlspecialchars($_POST['post_title'], ENT_NOQUOTES, 'UTF-8', false);
			$_POST['edit_content'] = htmlspecialchars($_POST['edit_content'], ENT_NOQUOTES, 'UTF-8', false);

			// Update!
			xensql_query("
				UPDATE {db_pref}content
					SET blog_title = '" . $_POST['post_title'] . "',
						blog_content = '" . $_POST['edit_content'] . "'
					WHERE id_blog = '" . $_GET['id'] . "'
			");

			// We're done
			done('?app=admin&section=blogs');
		}

		// Get the blog
		$result = xensql_query("
			SELECT id_blog, blog_title, blog_content
				FROM {db_pref}content
				WHERE id_blog = '" . $_GET['id'] . "'
				LIMIT 1
		");

			// Very simple. Show the edit screen
			foreach($result as $blog)
				vienara_template_edit($blog);

		// Whut?
		define('adm_sect', 'edit');

		// Die nice. ;)
		die_nice();
	}

	// The menu editor
	function admin_section_menu()
	{
		global $vienara;

		// Are we attempting to save a menu tab?
		if(isset($_POST['menu_label'])) {

			// Important fields
			$imp_fields = array(
				'menu_label',
				'menu_href',
				'menu_pos'
			);

				// Check them all
				foreach($imp_fields as $key => $value) {

					// Make 'em safe
					$_POST[$value] = xensql_escape_string($_POST[$value]);
					$_POST[$value] = htmlspecialchars($_POST[$value], ENT_NOQUOTES, 'UTF-8', false);

					// Are they empty?
					if(empty($_POST[$value]))
						die_nice(show_string('fill_in_all_fields'));
				}

			// The position should have a numeric value
			if(!is_numeric($_POST['menu_pos']))
				die_nice(show_string('pos_use_numeric'));

			// Set a tab id just in case we're creating a new one
			if(isset($_POST['new']))
				$_POST['tab_id'] = '';

			// Does it exist?
			if(empty($vienara['tabs'][$_POST['tab_id']]) && !isset($_POST['new']))
				die_nice(show_string('tab_not_found'));

			// Let's update it
			if(!isset($_POST['new']))
				xensql_query("
					UPDATE {db_pref}menu
						SET tab_label = '" . $_POST['menu_label'] . "',
							tab_link = '" . $_POST['menu_href'] . "',
							tab_position = '" . $_POST['menu_pos'] . "'
						WHERE
							id_tab = '" . $_POST['tab_id'] . "'
				");

			// Create a new tab
			else
				xensql_query("
					INSERT
						INTO {db_pref}menu
					VALUES (
						'',
						'" . $_POST['menu_pos'] . "',
						'" . $_POST['menu_href'] . "',
						'" . $_POST['menu_label'] . "'
					)
				");

			// Say that we're done
			done('?app=admin&section=menu');
		}

		// Delete one?
		elseif(isset($_GET['delete'])) {

			// The id should have a numeric value
			if(!is_numeric($_GET['delete']))
				die_nice(show_string('tab_not_found'));

			// Does it exist?
			if(empty($vienara['tabs'][$_GET['delete']]))
				die_nice(show_string('tab_not_found'));

			// Meh
			$_GET['delete'] = xensql_escape_string($_GET['delete']);

			// Delete it
			xensql_query("
				DELETE
					FROM {db_pref}menu
					WHERE id_tab='" . $_GET['delete'] . "'
			");

			// We're done
			done('?app=admin&section=menu');
		}

		// Define which template we should use
		define('adm_sect', 'menu');
	}

	// Repair and optimize tables.
	function admin_section_repairtable()
	{
		global $db_settings, $vienara;

		// Do we have some things to do?
		if(isset($_POST['repair_database'])) {

			// Make it sql friendly
			$_POST['repair_database'] = xensql_escape_string($_POST['repair_database']);

			// Repair or optimize?
			if(!isset($_POST['optimize']))
				$result = xensql_query('REPAIR TABLE ' . $_POST['repair_database']);
			else
				$result = xensql_query('OPTIMIZE TABLE ' . $_POST['repair_database']);

			// Did it work?
			if(!$result)
				$vienara['repair_fail'] = true;
			else
				$vienara['repair_fail'] = false;
		}

		// Get the tables
		$vienara['admin_tables'] = xensql_query("
			SHOW TABLES
				FROM " . $db_settings['dbname'] . "
		");

		// Where are we?
		define('adm_sect', 'repairtable');
	}

	// Show the help documents
	function admin_section_help()
	{
		global $txt, $vienara;

		// Setup a new array
		$vienara['help_docs'] = array();

		// Setup the help document array
		foreach($txt['doc'] as $key => $value) {

			// So what is this?
			if(!isset($txt['doc']['doc_' . $key]))
				continue;

			// Add it to the array
			$vienara['help_docs'][] = array(
				'title' => $key
			);
		}
		
		// Where are we?
		define('adm_sect', 'help');
	}

	// Extensions!
	function admin_section_extensions()
	{
		global $vienara;

		// Let's remove this extension.
		if(isset($_GET['delete'])) {

			// Increase memory
			@ini_set('memory_limit', '-1');

			// Decode the url
			$_GET['delete'] = urldecode($_GET['delete']);

			// Setup a new dir variable
			$dir = $vienara['extension_dir'] . '/' . $_GET['delete'];
		
			// Does it exist?
			$ext = $vienara['extension_dir'] . '/' . $_GET['delete'] . '/ExtensionInfo.php';

				// Test it
				if(!file_exists($ext))
					
					// Do nothing
					return;

			// Delete every file
			remove_dir($dir);
		}

		// Install one?
		elseif(isset($_GET['install'])) {

			// Check if there is an installer
			if(file_exists($vienara['extension_dir'] . '/' . $_GET['install'] . '/install.php'))
				include $vienara['extension_dir'] . '/' . $_GET['install'] . '/install.php';
		}

		// Enable or disable an extension
		elseif(isset($_GET['changestatus'])) {

			// Setup the dir
			$dir = $vienara['extension_dir'] . '/' . $_GET['changestatus'] . '/disabled.ext';

			// Does it exist?
			if(!file_exists($dir)) {

				// Create the file
				$result = fopen($dir, 'w');

					// Did it work?
					if(!$result)
						die_nice(show_string('create_disabled_fail'));
					else
						fclose($result);
			}
			else
				unlink($dir);
		}

		// Upload and extract an extension
		elseif(isset($_FILES['extension_archive'])) {

			// Check the extension
			if(!$_FILES['extension_archive']['type'] == 'archive/zip')
				die_nice(show_string('invalid_file'));

			// It's fine
			else {

				// Are there any errors?
				if($_FILES['extension_archive']['error'] != 0)
					die_nice(show_string('invalid_file'));

				// Nope
				else {

					// Define the new filename
					$fname = $_FILES['extension_archive']['tmp_name'];

					// We need this later
					$name = $_FILES['extension_archive']['name'];

					// Get a new dirname
					$new_dirname = str_replace('.zip', '', $name);

					// Does it already exist?
					if(file_exists($vienara['extension_dir'] . '/' . $name))
						die_nice(show_string('ext_already'));

					// Move it
					$result = move_uploaded_file($fname, $vienara['extension_dir'] . '/' . $name);

						// Did it work?
						if(!$result)
							die_nice(show_string('upload_fail'));

					// Chmod it
					chmod($vienara['extension_dir'] . '/' . $name, 0775);

					// Get the file
					$install = zip_extract($vienara['extension_dir'] . '/' . $name, $new_dirname);

					// Check for a sql file
					if(file_exists($install . '/install.php'))
						include $install . '/install.php';
				}
			}
		}

		// Get the files
		$dir = $vienara['extension_dir'];

		// Define a new array
		$vienara['extensions'] = array();

		// Does the extension dir exist?
		if(!file_exists($dir))
			$extensions = array();

		// Get the list of files
		else {
			$extensions = scandir($dir);

			// Fetch through them and tell if they're dots or not
			foreach($extensions as $key => $value) {

				// Dot?
				if($value == '.')
					continue;
				elseif($value == '..')
					continue;
				elseif($value == 'index.html')
					continue;
				elseif(strpos($value, '.zip'))
					continue;

				// Get the configuration file
				include($dir . '/' . $value . '/ExtensionInfo.php');

				// Check if we have this mod enabled
				if(file_exists($dir . '/' . $value . '/disabled.ext'))
					$isEnabled = false;
				else
					$isEnabled = true;

				// Add the information to the array
				$vienara['extensions'][] = array(
					'author' => $mod['author'],
					'version' => $mod['version'],
					'title' => $mod['title'],
					'enabled' => $isEnabled,
					'dir' => $value
				);
			}
		}

		// Define the current template
		define('adm_sect', 'extensions');
	}

	// Manage pages
	function admin_section_pages()
	{
		global $vienara;

		// Get the pages
		$vienara['pages'] = vienara_act_site(true);

		// Delete?
		if(!empty($_GET['delete'])) {

			// Is it set?
			if(empty($vienara['pages'][$_GET['delete']]))
				die_nice('page_not_found');

			// Delete it
			xensql_query("
				DELETE
					FROM {db_pref}pages
					WHERE id_page = '" . $_GET['delete'] . "'
			");

			// We're done
			done('?app=admin&section=pages');			
		}

		// We're about to edit a page
		elseif(!empty($_GET['edit'])) {

			// Is it set?
			if(empty($vienara['pages'][$_GET['edit']]))
				die_nice(show_string('page_not_found'));

			// We need to alter the page body a bit
			$vienara['pages'][$_GET['edit']]['page_body'] = htmlspecialchars($vienara['pages'][$_GET['edit']]['page_body']);
		}

		// Edit!
		if(isset($_POST['page_content']) && isset($_POST['page_id'])) {

			// Things that shouldn't be empty.
			$imp_fields = array(
				'page_content',
				'page_title'
			);

			// Fetch through them
			foreach($imp_fields as $field) {

				// Escape.
				$_POST[$field] = xensql_escape_string($_POST[$field]);

				// Unscape the html
				$_POST[$field] = htmlspecialchars_decode($_POST[$field]);

				// Is it empty?
				if(empty($_POST[$field]))
					die_nice(show_string('fill_in_all_fields'));
			}

			// Should we show the header?
			if(isset($_POST['page_header']))
				$showheader = 1;
			else
				$showheader = 0;

			// Update-vous!
			xensql_query("
				UPDATE {db_pref}pages
					SET page_title = '" . $_POST['page_title'] . "',
						page_body = '" . $_POST['page_content'] . "',
						show_header = '$showheader'
					WHERE
						id_page = '" . $_POST['page_id'] . "'
			");

			// We're done
			done('?app=admin&section=pages');
		}

		// Create a new page
		elseif(isset($_POST['page_content']) && !isset($_POST['page_id'])) {

			// Things that shouldn't be empty.
			$imp_fields = array(
				'page_content',
				'page_title'
			);

			// Fetch through them
			foreach($imp_fields as $field) {

				// Escape.
				$_POST[$field] = xensql_escape_string($_POST[$field]);

				// Is it empty?
				if(empty($_POST[$field]))
					die_nice(show_string('fill_in_all_fields'));
			}

			// Should we show the header?
			if(isset($_POST['page_header']))
				$showheader = 1;
			else
				$showheader = 0;

			// Update-vous!
			xensql_query("
				INSERT 
					INTO {db_pref}pages
				VALUES (
					'',
					'" . $_POST['page_title'] . "',
					'" . $_POST['page_content'] . "',
					'$showheader'
				)
			");

			// We're done
			done('?app=admin&section=pages');
		}

		// Hmm
		define('adm_sect', 'managepages');
	}

	// Edit the site css
	function admin_section_css()
	{
		global $vienara;

		// Attempting to save it?
		if(!empty($_POST['new_style'])) {

			// Backup it before saving?
			if(isset($_POST['css_backup'])) {

				// The old file
				$oldfile = 'style.css';

				// And the new file
				$newfile  = 'StyleBackups/style-' . date('YmdHis') . '.css';

				// Save it
				copy($oldfile, $newfile);
			}

			// Put the contents into the file
			file_put_contents('style.css', $_POST['new_style']);

			// We are done
			done('?app=admin&section=css');
		}

		// Get the content of the file
		$vienara['style'] = file_get_contents('style.css');

		// Escape
		$vienara['style'] = htmlspecialchars($vienara['style']);

		// Define the current template
		define('adm_sect', 'css');
	}

	// The terminal!
	function admin_section_terminal()
	{
		global $vienara;

		// Check if we have already a command set
		if(empty($_POST['command']))
			$vienara['current_command'] = show_string('no_command');

		// Yeah we have one set
		else {

			// The array with commands
			$commands = array(
				'hello_world' => 'hello_world',
				'help' => 'help',
				'getsettings' => 'getsettings',
				'refresh' => 'refresh',
				'support_url' => 'support_url'
			);

			// Is it set?
			if(isset($commands[$_POST['command']])) {

				// What should we do?
				switch($_POST['command']) {

					// The hello world command
					case 'hello_world':
						$vienara['current_command'] = 'Hello world!';
						break;

					// Do nothing
					case 'refresh':
						$vienara['current_command'] = show_string('no_command');
						break;

					// Get the settings and their values
					case 'getsettings':

						$vienara['current_command'] = '<ul>';

						// Walk through the settings		
						foreach($vienara['setting'] as $key => $value) {

							// For security reasons, don't show the password
							if($key == 'password')
								continue;

							// Show it
							$vienara['current_command'] .= '<li><strong>' . $key . ':</strong> ' . $value . '</li>';
						}

						$vienara['current_command'] .= '</ul>';				

						break;

					// The link you can use for getting support
					case 'support_url':
						$vienara['current_command'] = '<a href="' . Website_Url . '">' . Website_Url . '</a>';
						break;

					// Provide a list of commands
					case 'help':
						$vienara['current_command'] = implode(', ', $commands);
						break;
				}
			}

			// Nope
			else
				$vienara['current_command'] = show_string('command_not_found');
		}

		// We need this to get the template
		define('adm_sect', 'terminal');
	}

	// Define sections!
	$admin['sections'] = array(
		'newblog' => 'newblog',
		'blogs' => 'blogs',
		'backup' => 'backup',
		'settings' => 'settings',
		'password' => 'password',
		'hash' => 'hash',
		'publish' => 'publish',
		'edit' => 'edit',
		'menu' => 'menu',
		'repairtable' => 'repairtable',
		'help' => 'help',
		'extensions' => 'extensions',
		'pages' => 'pages',
		'css' => 'css',
		'terminal' => 'terminal'
	);

	// New sections :D
	vienara_hook('adm_section');

		// Do we have a section set?
		if(!isset($_GET['section']))
			admin_main();

		// We do have one set!
		elseif(isset($_GET['section']) && isset($admin['sections'][$_GET['section']]) && function_exists('admin_section_' . $_GET['section']))
			call_user_func('admin_section_' . $_GET['section']);

		// Yes, but it does not exist.
		elseif(isset($_GET['section']) && !isset($admin['sections'][$_GET['section']]))
			admin_main();

		// Yes, but there is no function available for it
		elseif(isset($_GET['section']) && isset($admin['sections'][$_GET['section']]) && !function_exists('admin_section_' . $_GET['section']))
			admin_main();

		// Hmm..
		else
			admin_main();

	// Load the admin template
	template_admin($admin);
}

// Check for maintenance
if($maintenance['enable'] == 1 && !vienara_is_logged())
	fatal_error($maintenance['message']);

// Full maintenance.
elseif($maintenance['enable'] == 2)
	fatal_error($maintenance['message']);

// Get the header
vienara_header();

// After loading the header, we should have a hook
vienara_hook('pre_content');

// Alter the password?
if(isset($_POST['old_password'])) {

	// Are we logged in?
	if(!vienara_is_logged()) {

		// Call the Vienara function
		vienara();

		// We're done
		die_nice();
	}

	// Important fields
	$imp_fields = array(
		'old_password',
		'new_password'
	);

		// Empty?
		foreach($imp_fields as $key => $value) {

			// Check if it's empty
			if(empty($_POST[$value]))
				die_nice(show_string('fill_in_all_fields'));

			// Now make it safe
			$_POST[$value] = xensql_escape_string($_POST[$value]);
		}

	// Is it correct?
	if(!$vienara['setting']['password'] == sha1($_POST['old_password']))
		die_nice(show_string('incorrect_pass'));

	// Hash!
	$_POST['new_password'] = sha1($_POST['new_password']);

	// Update it
	xensql_query("
		UPDATE {db_pref}settings
			SET value = '" . $_POST['new_password'] . "'
			WHERE id = 'password'
	", true);

	// Logout.
	vienara_act_logout();
}

// What do we want to do?
if(!isset($_GET['app']))
	vienara();
elseif(!empty($_GET['app']))
	vienara_get_app($_GET['app']);
else
	vienara();

// Before loading the footer.
vienara_hook('pre_footer');

// And get the footer
vienara_footer();

// Flush!
ob_flush();

// We need to close the script because we don't really want malware
die;
