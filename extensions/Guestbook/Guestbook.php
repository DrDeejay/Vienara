<?php
/**
* Guestbook extension
*
*
* This file is licensed under the MIT license. You can use
* this project as a base for your own project, but you are
* not allowed to remove this header comment block. You may
* not use the name "Vienara" as name for your project
* either. Thanks for understanding.
*
* @version: 1.0
* @copyright 2012: Dr. Deejay
* @developed by: Dr. Deejay
*
* @license MIT
*/
if(!defined('Vienara'))
	die;

// The main guestbook function
function vienara_act_guestbook()
{
	global $vienara, $viencode;

	// Do we want to save a comment? Then save it, so we can see it. :)
	if(!empty($_POST['message'])) {

		// Clean the fields
		$_POST['username'] = xensql_escape_string($_POST['username']);
		$_POST['website'] = xensql_escape_string($_POST['website']);
		$_POST['message'] = xensql_escape_string($_POST['message']);

		// Escape the html
		$_POST['username'] = htmlspecialchars($_POST['username'], ENT_NOQUOTES, 'UTF-8', false);
		$_POST['website'] = htmlspecialchars($_POST['website'], ENT_NOQUOTES, 'UTF-8', false);
		$_POST['message'] = htmlspecialchars($_POST['message'], ENT_NOQUOTES, 'UTF-8', false);		

		// Do we have a username set?
		if(empty($_POST['username']) && !vienara_is_logged())
			$_POST['username'] = 'Guest';
		elseif(empty($_POST['user']) && vienara_is_logged())
			$_POST['username'] = 'Admin';

		// Get the ip adress
		$ip = $_SERVER['REMOTE_ADDR'];

		// Add it to the database
		xensql_query("
			INSERT
				INTO {db_pref}guestbook
			VALUES (
				'',
				'$ip',
				'" . $_POST['message'] . "',
				UNIX_TIMESTAMP(),
				'" . (vienara_is_logged() ? 1 : 0) . "',
				'" . $_POST['website'] . "',
				'" . $_POST['username'] . "'
			)
		");

		// We're done with this
		done('?app=guestbook');
	}

	// Deleting an existing one? Only if we are allowed to do that.
	if(isset($_GET['delete'])) {

		// We should be allowed to remove comments
		if(!vienara_is_logged())
			die_nice('Hacking attempt.');

		// Is it empty?
		if(empty($_GET['delete']) || !is_numeric($_GET['delete']))
			die_nice('Comment not found.');

		// Make sure we have a valid id
		$id = xensql_escape_string($_GET['delete']);

		// Check if the message is set
		$result = xensql_count_rows("
			SELECT id_comment
				FROM {db_pref}guestbook
				WHERE id_comment='$id'
		");

			// How many?
			if($result == 0)
				die_nice('Comment not found.');

		// Found. Now delete it
		xensql_query("
			DELETE
				FROM {db_pref}guestbook
				WHERE id_comment='$id'
		");

		// We're done
		done('?app=guestbook');
	}

	// Make our page pretty
	echo '
		<div class="cat_bg bg_color">
			Guestbook
		</div>
		<table width="100%" cellspacing="1">';

	// Get the comments by executing a query
	$result = xensql_query("
		SELECT id_comment, ip_adress, username, website, message, isadmin
			FROM {db_pref}guestbook
	");

	// Show the comments
	foreach($result as $comment) {

		// Let's parse bbc
		$comment['message'] = $viencode->parse($comment['message']);

		// Make new lines work
		$comment['message'] = nl2br($comment['message']);

		// This is just template stuff
		echo '
			<tr>
				<td width="15%" class="padding bg_color5" valign="top"><span style="font-size: 14px;">' . $comment['username'] . '</span><br />
					' . ($comment['isadmin'] == 1 ? 'Administrator<br /><img src="' . $vienara['setting']['avatar'] . '" alt="" /><br />' : '<img src="images/vienara_guest.png" alt="" /><br />') . '
					' . (!empty($comment['website']) ? '<a href="' . $comment['website'] . '">[Website]</a><br />' : '') . '
					' . (vienara_is_logged() ? '<a href="http://www.stopforumspam.com/ipcheck/' . $comment['ip_adress'] . '">[Check ip]</a>' : '') . '
				</td>
				<td width="85%" class="padding bg_color4" valign="top">
					' . (vienara_is_logged() ? '<a href="' . Blog_file . '?app=guestbook&delete=' . $comment['id_comment'] . '">[Delete]</a><br />' : '') . '
					' . $comment['message'] . '
				</td>
			</tr>';
	}

	// Show the rest of the code. Simple stuff
	echo '
		</table>
		<br /><br />
		<div class="cat_bg bg_color">
			Add comment
		</div>
		<div class="bg_color5 padding">
			<form action="' . Blog_file . '?app=guestbook" method="post">
				<table width="100%">
					<tr>
						<td width="20%" valign="top"><strong>Name:</strong></td>
						<td width="80%"><input type="text" name="username" /></td>
					</tr>
					<tr>
						<td width="20%" valign="top"><strong>Website:</strong></td>
						<td width="80%"><input type="text" name="website" /></td>
					</tr>
					<tr>
						<td width="20%" valign="top"><strong>Message:</strong></td>
						<td width="80%"><textarea class="editor" name="message" rows="5" cols="50"></textarea></td>
					</tr>
				</table><br /><br />
				When sending a comment, your ip-adress will be saved. It will only be used for anti-spam purposes and will not be published
				anywhere. When submitting this form, you will agree to that. After you submitted this form, you cannot delete or edit your 
				message. Messages can only be deleted by administrators. You can ask them to remove your comment if you want to, but when
				submitting this form, you agree to it that the administrator does not have to obey your request.<br /><br />
				<input type="submit" value="' . show_string('submit') . '" />
			</form>
		</div>';
}
