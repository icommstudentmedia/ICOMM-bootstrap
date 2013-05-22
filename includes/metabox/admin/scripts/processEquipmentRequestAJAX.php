<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
require_once("connection.php");

if($_GET['comment']){
  if($db){
    $statement = $db->prepare("UPDATE equipment SET comment = ? WHERE id = ?;");
    $statement->bind_param('si', htmlentities($_GET['comment'],ENT_QUOTES), $_GET['id']);
    if($statement->execute()){
      ;//echo "This comment was updated in the database: <b>" . htmlentities($_GET['comment'],ENT_QUOTES);
    } //else echo "There was an error<br />";
    $statement->close();
  } //else echo "There was a problem connecting to the database<br />";
  
}

if($_GET['approval']){
  if($db){
  //use a when to switch the aproval from 1 to 0
    $statement = $db->prepare("UPDATE equipment SET manageapprove = 
                                (CASE manageapprove
                                WHEN 1
                                THEN 0
                                WHEN 1
                                THEN 0
                                ELSE 1
                                END)
                                WHERE id = ?;");
    $statement->bind_param('i', $_GET['id']);
    if($statement->execute()){
      //get the current aproval to put back in the webpage
      $statement = $db->prepare("SELECT manageapprove FROM equipment WHERE id=?;");
      $statement->bind_param('i', $_GET['id']);
      $statement->store_result();
    	$statement->bind_result($approval);
      if($statement->execute()){
        $statement->fetch();
        //echo "$approval," . $_GET['id'];
      } //else echo "There was an error<br />";
    } //else echo "There was an error<br />";
    $statement->close();
  } //else echo "There was a problem connecting to the database<br />";
}


if($_GET['editcell'] && $_GET['type']){
  if($db){
    //name will be type name,check_out_date, check_in_date
    if($_GET['type'] == 'name') 
      $statement = $db->prepare("UPDATE equipment SET name = ? WHERE id = ?;");
    if($_GET['type'] == 'check_out_date') 
      $statement = $db->prepare("UPDATE equipment SET check_out_date = ? WHERE id = ?;");
    if($_GET['type'] == 'check_in_date') 
      $statement = $db->prepare("UPDATE equipment SET check_in_date = ? WHERE id = ?;");
    if($_GET['type'] == 'group') 
      $statement = $db->prepare("UPDATE equipment SET `group` = ? WHERE id = ?;");  
      
    $statement->bind_param('si',$_GET['editcell'], $_GET['id']);
    if($statement->execute()){
      ;//echo "it worked";//it was successful
    } //else echo "There was an error<br />";
    $statement->close();
  } //else echo "There was a problem connecting to the database<br />";
}

if(isset($_GET['request'])){
  if($db){
    $statement = $db->prepare("UPDATE equipment SET request = ? WHERE id = ?;");
    $statement->bind_param('si', htmlentities($_GET['request'],ENT_QUOTES), $_GET['id']);
    if($statement->execute()){
      ;//echo "This comment was updated in the database: <b>" . htmlentities($_GET['comment'],ENT_QUOTES);
    } //else echo "There was an error<br />";
    $statement->close();
  } //else echo "There was a problem connecting to the database<br />";

}

?>
