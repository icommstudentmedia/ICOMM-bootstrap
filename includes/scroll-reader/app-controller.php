<?php
/**
*	Controller for the Scroll Reader app (iOS)
*	@author Isaac Andrade - isaac.nic@gmail.com
*	@version 1.0
*/
// Include the file that has the include for the WP_Query class.
include ('../../../../../wp-blog-header.php');
// The path to the application handlers (functions)
$path = 'app-handlers.php';
// Conditionally include the model
if(file_exists($path))
{
	include_once ($path);
} 
else
{
	// Return a message to client
}

// Check if 'command' and 'data' came from POST message
if($_POST['command'])
{
	$command = $_POST['command'];
}
if($_POST['data'])
{
	$data = $_POST['data'];	
}
// Define array of commands
$handlers = array(
	'get_articles_home' => 'get_articles_home',
	'get_articles_by_category' => 'get_articles_by_category',
	'get_article' => 'get_article',
	'get_author' => 'get_author',
	'get_articles_by_author' => 'get_articles_by_author'
	);

// Use reflection to call handler functions depending on the values of $command $data
$message = $handlers[$command]($data);

// Create a json string
$jsonMessage = json_encode($message);

// Debug
echo $jsonMessage;

?>