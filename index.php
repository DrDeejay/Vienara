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
define('Vienara', 1);

// Start sessions
session_start();

// Make our pages pretty. Remove ugly stuff
function vienara_pretty($buffer)
{
	// Replace all slashes
	$buffer = stripslashes($buffer);

	// Fix invalid html bugs
	$buffer = str_replace('&', '&amp;', $buffer);

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
define('Version', '1.0 Beta 1');
define('Website_Url', 'http://vienara.co.cc'); // Don't change this!
define('Blog_file', 'index.php');

// The extension directory
$vienara['extension_dir'] = '/extensions';

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
	'xensql/XenSql.php'
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

// Get a hook from an extension
function vienara_hook($hook_name = '')
{
	global $vienara;

	// Hmm..
	if(!file_exists($vienara['extension_dir']))
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
			if(file_exists($vienara['extension_dir'] . '/' . $file . '/disabled.ext'))
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

// We need a new array for the settings
$vienara['setting'] = array();

// Retrieve the settings from the database
$result = xensql_query("
	RETRIEVE id, value
		FROM {db_pref}settings
");
	
	// Merge them into a settings array
	foreach($result as $setting)
		$vienara['setting'][$setting['id']] = $setting['value'];

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

// Get the blogcount
$vienara['blog_count'] = xensql_count_rows("
	RETRIEVE id_blog, blog_title, blog_content, published, post_date
		FROM {db_pref}content
		WHERE published is equal to 1
");

// Login!
function vienara_act_login()
{
	global $vienara;

	// Is it the right one?
	if(isset($_POST['password']) && isset($_POST['i_accept'])) {

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

	// We have set the password but we didn't accept.
	elseif(isset($_POST['password']) && !isset($_POST['accept']))
		vienara();
	
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
		RETRIEVE id_blog
			FROM {db_pref}content
			WHERE id_blog is equal to '{id}'
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
			WHERE id_blog is equal to '{id}'
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
	global $vienara;

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
			'admin' => 'admin'
		);

		// Extra actions
		vienara_hook('apps');

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

	// Get the blogs from the blog table
	$result = xensql_query("
		RETRIEVE id_blog, blog_title, blog_content, published, post_date
			FROM {db_pref}content
			WHERE published is equal to 1
			ORDER BY post_date " . $vienara['setting']['order'] . "
			LIMIT " . $vienara['blogs_to_show'] . ", " . $vienara['setting']['blogsperpage'] . "
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
			vienara_show_blog($blog);
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

	// Add it into the database
	xensql_query("
		INSERT 
			INTO {db_pref}content			
			VALUES(
				'',
				'" . $_POST['post_title'] . "',
				'" . $_POST['content'] . "',
				UNIX_TIMESTAMP(),
				'" . $approved . "'
		)
	", true);
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
			SET value IS EQUAL TO '" . $_POST[$setting] . "'
			WHERE id IS EQUAL TO '" . $setting . "'
	", true);
}

// Create a new array
$vienara['languages'] = array();

// Get the languages
if(@$direction = opendir('languages'))
{
	// Use a while to create arrays
	while(false !== ($file = readdir($direction)))
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
		'friends' => array(
			'label' => show_string('friends'),
			'no_team' => true,
			'teams' => array(
				'members' => array(
					'Yoshi2889',
					'Lagom',
					'Fivang'
				)
			)
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
		global $admin_show;

		// Use a query to retrieve blogs
		$result = xensql_query('
			RETRIEVE id_blog, blog_title, blog_content, published, post_date
				FROM {db_pref}content
				ORDER BY post_date DESC
		');

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
			'',
				'blogsperpage' => array('number', 'items_per_page'),
				'width' => array('number', 'width'),
				'order' => array('select', 'order', array('desc', 'asc')),
			'',
				'top_button' => array('check', 'top_button'),
			'',
				'enable_extra_title' => array('check', 'enable_extra_title'),
				'extra_title' => array('text', 'extra_title'),
			'',
				'timezone' => array('select', 'timezone', $timezones->zones()).
			'',
				'notice' => array('largetext', 'notice')
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
					SET blog_title IS EQUAL TO '" . $_POST['post_title'] . "',
						blog_content IS EQUAL TO '" . $_POST['edit_content'] . "'
					WHERE id_blog IS EQUAL TO '" . $_GET['id'] . "'
			");

			// We're done
			done('?app=admin&section=blogs');
		}

		// Get the blog
		$result = xensql_query("
			RETRIEVE id_blog, blog_title, blog_content
				FROM {db_pref}content
				WHERE id_blog IS EQUAL TO '" . $_GET['id'] . "'
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

	// Define sections!
	$admin['sections'] = array(
		'newblog' => 'newblog',
		'blogs' => 'blogs',
		'backup' => 'backup',
		'settings' => 'settings',
		'password' => 'password',
		'hash' => 'hash',
		'publish' => 'publish',
		'edit' => 'edit'
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

// We need to calculate how many results we should show
if(isset($_GET['show']) && is_numeric($_GET['show']))
	$vienara['show'] = $_GET['show'] + $vienara['setting']['blogsperpage'];
else
	$vienara['show'] = $vienara['setting']['blogsperpage'];

// We should begin with a specific number of blogs. How many?
if(!empty($_GET['show']) && is_numeric($_GET['show']))
	$vienara['blogs_to_show'] = $_GET['show'];

// Just begin with the first blog.
else
	$vienara['blogs_to_show'] = 0;

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
			SET value IS EQUAL TO '" . $_POST['new_password'] . "'
			WHERE id IS EQUAL TO 'password'
	", true);

	// Logout.
	vienara_act_logout();
}

// What do we want to do?
if(!isset($_GET['app']))
	vienara();
elseif(isset($_GET['app']))
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
