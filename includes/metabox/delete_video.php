<?php 

// Include the BCMAPI SDK
	require('bc-mapi.php');

// Instantiate the class, passing it our Brightcove API tokens (read, then write)
	$bc = new BCMAPI(
    '5lr_GNp0hRM6wYM1r_lfuSZBSAGKVt94K2Kgb4sBLHNSKxdZg6qLOA..',
    '6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..'
  );
$oldvideoid = $_POST['currentid'];



// Make our API call
	$video = $bc->find('videoById', $oldvideoid);
	$array = get_object_vars($video);
	echo 'Your Video "'.$array['name'].'" is now deleted';

// Delete a 'video' by ID, and cascade the deletion
	$options = array(
        'cascade' => TRUE
		);
	$bc->delete('video', $oldvideoid, NULL, $options);
// Retrieve upload status
//$status = $bc->getStatus('video', $oldvideoid);

print_r($status)

?>
<script>
alert("Your video is now deleted, click update post to save changes");
</script>