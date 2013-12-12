<?php
// !!!! --- If these credentials are not needed, delete the user in the database!!
// username scroll_reader_13
// password ScrollReaderUser_2013

// Application Controller Patter
// Handler functions
function get_articles_home($data)
{
	$args = array(
		'post_type' => 'post',
		'post_count' => '5'
		);
	// Get WP_Query object (has queries)
	$app_query = get_query($args);
	// Get array of WP_Posts from WP_Query and re-store the value in $app_query
	$app_query = $app_query->get_posts(array(
											'posts_per_page'   => 5,
											'offset'           => 0,
											'category'         => '',
											'orderby'          => 'post_date',
											'order'            => 'DESC',
											'include'          => '',
											'exclude'          => '',
											'meta_key'         => '',
											'meta_value'       => '',
											'post_type'        => 'post',
											'post_mime_type'   => '',
											'post_parent'      => '',
											'post_status'      => 'publish',
											'suppress_filters' => true 
											)
										);

	// Get only what is needed for the app and store it in an array
	$articles = array();

	for ($i = 0; $i < 5; $i++)
	{
		$articles[] = array(
							'author' => get_author_by_id($app_query[$i]->post_author),
							'title' => $app_query[$i]->post_title,
							'date' => $app_query[$i]->post_date,
							'content' => html2txt($app_query[$i]->post_content),
							'image' => get_thumb_url($app_query[$i]->ID)
							);
	}

	return $articles;
}

function get_articles_by_category($data)
{	
}
function get_article($data)
{	
}
function get_author($data)
{	
}
function get_articles_by_author($data)
{	
}

// Utility functions
function get_author_by_id($author_id)
{
	// Get a WP_User user object
	$author_name = get_user_by('id', $author_id);
	
	// Return only the string with the name
	return $author_name->display_name;
}

function get_thumb_url($post_id)
{
	$thumb_id = get_post_thumbnail_id($post_id);
	// Returns the array with the attachment info
	$thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail', true);

	//return only the image url (located in index 0)
	return $thumb_url[0];
}

function get_query($args)
{
	return new WP_Query($args);
}

// Get rid of Markup tags from a given string
// Source: (Comments section of) http://www.php.net/strip_tags
function html2txt($document){ 
$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
               '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
               '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
); 
$text = preg_replace($search, '', $document); 
return $text; 
} 
?>