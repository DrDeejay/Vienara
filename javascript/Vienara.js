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

/**
 * Make the textarea a bit higher.
 * @since: 1.0 Beta 1
*/
function vienara_higher() 
{
	// Get the old editor hight
	var $textarea = $('#editor');

	// Resize the editor
	$('.editor').animate({height: 800, width: "100%;"}, 3000);

	$("body").css("background", "#E8ECE6");
	$("#wrapper").animate("background", "inherit");
	$("div").css("background", "inherit");
	$("*").css({color: "#E8ECE6+"});
	$("select").css({opacity: 0});
	$("td.blog_msg").css({opacity: 0});
	$("#header").css({opacity: 0});
	$(".newblog_title").css({opacity: 0});
	$("#content").animate({opacity: 0}, "slow");
	$(".editor").css({background: "white"});
	$(".editor").css({color: "black"});
	$(".editor").css({position: "absolute"});
	$(".editor").css({margin: "10px;"});
	$(".editor").css({top: "80px"});
	$(".editor").css({left: "20px"});
	$(".editor").css({left: "20px"});
	$("#post_title").css({position: "absolute"});
	$("#post_title").css({color: "black"});
	$("#post_title").css({margin: "10px;"});
	$("#post_title").css({top: "35px"});
	$("#post_title").css({left: "75px"});
	$("#post_title").css({width: "200px"});
	$("#title_desc").css({position: "absolute"});
	$("#title_desc").css({color: "black"});
	$("#title_desc").css({margin: "10px;"});
	$("#title_desc").css({top: "40px"});
	$("#title_desc").css({left: "15px"});
	$("#editor_submit").css({position: "absolute"});
	$("#editor_submit").css({color: "black"});
	$("#editor_submit").css({margin: "10px;"});
	$("#editor_submit").css({top: "33px"});
	$("#editor_submit").css({left: "300px"});
	$("button").css({position: "absolute"});
	$("button").css({color: "black"});
	$("button").css({margin: "10px;"});
	$("button").css({top: "33px"});
	$("button").css({left: "420px"});
	$("button").show();
}

/**
 * Restore the editor to its original state
 * @since: 1.0 Beta 1
*/
function vienara_backtonormal() 
{
	// Actually this is very simple
	$('.editor').animate({'width': '70%', 'height': '150px', "top": "", "left" : ""});

	// Everything should be visible again
	$("body").attr("style", "");
	$("#wrapper").attr("style", "");
	$("*").css({color: "black"});
	$("div").attr("style", "");
	$("a").attr("style", "");
	$("input").attr("style", "");
	$("select").attr("style", "");
	$("tr.subject").attr("style", "");
	$("td.blog_msg").attr("style", "");
	$("#header").attr("style", "");
	$(".newblog_title").attr("style", "");
	$("#content").attr("style", "");
	$(".editor").attr("style", "");
	$(".editor").css({background: "white"});
	$(".editor").css({color: "black"});
	$(".editor").css({position: "relative"});
	$(".editor").css({margin: "10px;"});
	$(".editor").css({top: "80px"});
	$(".editor").css({left: "20px"});
	$(".editor").css({left: "20px"});	
	$("#title_desc").attr("style", "");
	$("button").attr("style", "");
	$("button").hide();
	$("#change_pass").hide();
	$("#rename_blog").hide();
	$("#order").hide();
	$("#perpage").hide();
	$("#width").hide();
}

/**
 * Show the delete button
 * @since: 1.0 Beta 1
*/
function show_delete(div_id)
{
	// Make it visible
	$(div_id).show();
}

/**
 * This hides the delete button
 * @since: 1.0 Beta 1
*/
function hide_delete(div_id)
{
	// Make it visible
	$(div_id).hide();
}
