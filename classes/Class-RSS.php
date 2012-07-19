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
* @version: 1.0 Release Candidate 1
* @copyright 2012: Vienara
* @developed by: Dr. Deejay and Thomas de Roo
* @package: Vienara
* @news, support and updates at: http://vienara.org
* @sponsored by: Graywebhost (http://graywebhost.com)
*
* @license MIT
*/

// Have we defined Vienara?
if(!defined('Vienara'))
	die();

/**
 * The RSS feed class
*/
class RSS {

	/**
	 * The setup function, which loads the rss stuff
	*/
	function setup() {

		// The feed header
		$this->header();

		// Get the feed items
		$this->getItems();

		// And load the footer
		$this->footer();
	}

	/**
	 * Feed header. This makes sure the browser knows this is RSS
	*/
	function header() {

		global $vienara;

		// 
		echo '<?xml version="1.0" encoding="UTF-8" ?>
			<rss version="2.0">
				<channel>
					<title>' . $vienara['setting']['title'] . ' - ' . show_string('rss') . '</title>
					<link>' . $vienara['setting']['blog_url'] . '</link>
					<description>' . show_string('rss_purpose') . '</description>
					<language>' . $vienara['setting']['language'] . '</language>';
	}

	/**
	 * Get the feed items
	*/
	function getItems() {

		// Get recent blogs from the database
		$result = xensql_query("
		SELECT id_blog, blog_title, blog_content, published, post_date
			FROM {db_pref}content
			WHERE published = 1
			ORDER BY post_date DESC
			LIMIT 15
		");

		// Now show these blogs
		foreach($result as $blog)
			echo '
					<item>
						<title>' . $blog['blog_title'] . '</title>
						<link>' . Blog_file . '?blog=' . $blog['id_blog'] . '</link>
						<description>' . $blog['blog_content'] . '</description>
					</item>';
	}

	/**
	 * Just close the existing rss feed
	*/
	function footer() {

		echo '
				</channel>
			</rss>';
	}
}
