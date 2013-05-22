<?php

$v_name = $_POST['name'];
$v_title = $_POST['title'];
$v_des = $_POST['des'];
$v_tag1 = $_POST['tag1'];
$v_tag2 = $_POST['tag2'];
$v_tag3 = $_POST['tag3'];
$v_tag4 = $_POST['tag4'];
$v_tag5 = $_POST['tag5'];
$v_size = intval($_POST['size']);
$v_email = $_POST['email'];  

echo $_FILES['file_upload']['name'];

// check for null values or insert
if ($v_name=="" or $v_title=="" ) {
	die('File not selected. <a href="brightcove.php" >Try Again.</a><br>');
} 

$random_num = rand(1000, 9999);

$file_name_local = "upload/file".$v_size.".xml";
$file_name_remote = 'file='.$v_size.'rand='.$random_num.'.xml';
// XML FILE CREATION
$file= fopen($file_name_local, "w");
$_xml ="<?xml version=\"1.0\" encoding=\"utf-8\" ?>\r\n";
$_xml .="<publisher-upload-manifest publisher-id=\"694915275001\" preparer=\"David H.\" report-success=\"TRUE\">\r\n";
$_xml .="  <notify email=\"".$v_email."\" />\r\n\r\n";
$_xml .="  <notify email=\"jdhernandezr@gmail.com\" />\r\n\r\n";
$_xml .="    <asset filename=\"".$v_name."\"\r\n";
$_xml .="        refid=\"asset".$v_size.$random_num."\"\r\n";
$_xml .="        size=\"".$v_size."\"\r\n";
$_xml .="        display-name=\"".$v_title."\"\r\n";
$_xml .="        encode-to=\"MP4\"\r\n";
$_xml .="        encode-multiple=\"TRUE\"\r\n";
$_xml .="        type=\"VIDEO_FULL\"/>\r\n\r\n";
$_xml .="    <title name=\"".$v_title."\"\r\n";
$_xml .="        refid=\"".$v_name."\"\r\n";
$_xml .="        video-full-refid=\"asset".$v_size.$random_num."\"\r\n";
$_xml .="        active=\"FALSE\">\r\n";
$_xml .="        <short-description>".$v_des."</short-description>\r\n";
$_xml .="        <long-description></long-description>\r\n";
$_xml .="        <related-link-url></related-link-url>\r\n";
$_xml .="        <related-link-text></related-link-text>\r\n";
$_xml .="        <tag>".$v_tag1."</tag>\r\n";
$_xml .="        <tag>".$v_tag2."</tag>\r\n";
$_xml .="        <tag>".$v_tag3."</tag>\r\n";
$_xml .="        <tag>".$v_tag4."</tag>\r\n";
$_xml .="        <tag>".$v_tag5."</tag>\r\n";
$_xml .="    </title>\r\n\r\n";
$_xml .="</publisher-upload-manifest>";
fwrite($file, $_xml);
fclose($file);


$ftp_connection = ftp_connect('upload.brightcove.com') or die("Could not connect to FTP server");
if(ftp_login($ftp_connection, 'ldsbyuidaho','WEG4o5B7')) {
} else {
	echo "Could not authenticate";
}
if (ftp_put($ftp_connection, $file_name_remote, $file_name_local, FTP_ASCII, 0)) {
	header('Location: brightcove_success.php');
	ftp_close($ftp_connection);
} else {
	echo "Upload failed";
}

?>

 
<a href="brightcove.php">Upload another?</a>