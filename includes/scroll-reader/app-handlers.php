<?php
// !!!! --- If these credentials are not needed, delete the user in the database!!
// username scroll_reader_13
// password ScrollReaderUser_2013
function get_articles_home($data)
{
	$args = array(
		'post_type' => 'post'
		);
	$app_query = get_query($args);
	// Debug
	print_r($app_query);
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
function get_query($args)
{
	return new WP_Query($args);
}
?>