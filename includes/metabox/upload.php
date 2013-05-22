<?php
//echo "start";
print_r($_POST);
print_r($_FILES);

$result=0;

$file = $_FILES['bcVideo']['tmp_name'];
rename($file, "/home/newicomm/tmp/".$_FILES['bcVideo']['name']);

$filename = "/home/newicomm/tmp/".$_FILES['bcVideo']['name'];
$description = $_POST['bcShortDescription'];
$video_title = $_POST['bcName'];

//exit('filename:'.$filename);

$filepath = "http://api.brightcove.com/services/post";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
	curl_setopt($ch, CURLOPT_URL, $filepath);
    curl_setopt($ch, CURLOPT_POST, true);
    echo "<pre>";
	$post = array(
		"JSON"=>'{"method":"create_video","params":{"token":"6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..","video":{"name":"'.$video_title.'","shortDescription":"'.$description.'"}}}',
        "file"=>"@".$filename,
    );
	//if(file_exists('file57m.mov') == false) echo "The file doesn't exist";

print_r($post);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
    $response = curl_exec($ch);
	echo $response;
	$json = json_decode($response);
	echo '<script language="javascript" type="text/javascript"> var res='.$json->result.';</script>';
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(1);</script>  
<script language="javascript" type="text/javascript">window.top.window.respUpload(res);</script>