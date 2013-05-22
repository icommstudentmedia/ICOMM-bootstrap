<?php require_once("connection.php");
if(isset($_GET['id'])){ $id = $_GET['id'];
if($db){
  $statement = $db->prepare("select comment from equipment where id = ?;");
  $statement->bind_param('i',$id);
  if($statement->execute()){
  
  $statement->store_result(); //we need to do this before getting the number of rows
  $returnNum = $statement->num_rows;
  $statement->bind_result($comment);
  
  //echo "there are $returnNum rows returned<br />";
  //echo "here is the comment:<br />";
  $statement->fetch();
  echo "<textarea id='comment' class='comment$id' cols='42' rows='5'>$comment</textarea><script>setTimeout ('focuscomment()',100);</script>"; //<div id='savecomment'><a id='savecommentlink' href='#' title='Save the comment'>Save</a></div>";
  } else echo "there was an error $id ";
} else echo "there was a problem connecting to the database";
} else {
echo "There was no id included";
}
?>