<?php
$result=0;
$multi= '"create_multiple_rendition":true';
// Include the BCMAPI SDK
require('bc-mapi.php');

// Instantiate the class, passing it our Brightcove API tokens (read, then write)
	$bc = new BCMAPI(
    '5lr_GNp0hRM6wYM1r_lfuSZBSAGKVt94K2Kgb4sBLHNSKxdZg6qLOA..',
    '6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..'
  );

// Create an array of meta data from our form fields
	$metaData = array(
    'name' => $_POST['bcName'],
    'shortDescription' => $_POST['bcShortDescription']
  );
// Option
	$options = array(
	'create_multiple_renditions'=>true
	);

// Move the file out of 'tmp', or rename
	rename($_FILES['bcVideo']['tmp_name'], '/home/newicomm/tmp/' . $_FILES['bcVideo']['name']);
	$file = '/home/newicomm/tmp/' . $_FILES['bcVideo']['name'];

// Upload the video and save the video ID
	$id = $bc->createMedia('video', $file, $metaData, $options);
	echo '<script language="javascript" type="text/javascript"> var res='.$id.';</script>';
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(1);</script>  
<script language="javascript" type="text/javascript">window.top.window.respUpload(res);</script>