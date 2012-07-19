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
 * The publishing class
*/
class Publish {

	/**
	 * Check if the blog exists
	*/
	function check($blog_id) {

		// Make it sql-friendly.
		$blog_id = xensql_escape_string($blog_id);

		// Execute a database query
		$result = xensql_count_rows("
			SELECT id_blog
				FROM {db_pref}content
				WHERE id_blog = '$blog_id'
		");

			// How many rows?
			if($result == 1)
				return true;
			else
				return false;
	}

	/**
	 * Has this blog already been published?
	*/
	function isPublished($blog_id) {

		// Check it.
		$result = xensql_query("
			SELECT id_blog, published
				FROM {db_pref}content
				WHERE id_blog = '$blog_id'
		");

		// Walk through the result
		foreach($result as $blog) {

			// Is it published?
			if($blog['published'] == 1)
				return 0;
			else
				return 1;
		}
	}

	/**
	 * This is the function that publishes or unpublishes a post
	*/
	function do_publish($id_blog = 1) {

		// Check if the blog exists
		if($this->check($id_blog) == false)
			die_nice(show_string('blog_not_found'));

		// Is it already published?
		$shouldPublish = $this->isPublished($id_blog);

		// Update the result
		xensql_query("
			UPDATE {db_pref}content
				SET published = '$shouldPublish'
				WHERE id_blog = '$id_blog'
		");
	}
}
