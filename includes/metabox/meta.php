
<?php 	$storeid = $meta['videoid']; ?>
<div class="bc-upload">
	<div class="bc-brand"></div>
    <?php if ($storeid == NULL) { ?>
		<div class="upload"><a class="upload_start" rel=".my_meta_control">Upload Video</a></div>
    <?php  } else  { ?>
    	<?php 
		// Include the BCMAPI SDK
		require('bc-mapi.php');
		$bc = new BCMAPI( '5lr_GNp0hRM6wYM1r_lfuSZBSAGKVt94K2Kgb4sBLHNSKxdZg6qLOA..','6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..');
		
		// most videos are stored with the numeric id, but some(mostly transferred from kickapps) use reference id, which is a string
		$video = NULL;
		if(is_numeric($storeid)){
			try{
				$video = $bc->find('videoById', $storeid);
			}catch(Exception $e){
				echo $storeid;
				print_r($e);
			}
		}else{
			try{
				$video = $bc->find('videoById', $storeid);
			}catch(Exception $e){
				
			}
		}
		$array = get_object_vars($video);
		
		$mytime = $array['length']; //initialize the constants
		$msec_hh = 1000 * 60 * 60; // millisecs per hour
		$msec_mm = 1000 * 60; // millisecs per minute
		$msec_ss = 1000; // millisecs per second
		
		$hh = $mytime / $msec_hh; // divide by millisecs per hour => hrs
		$r = $mytime % $msec_hh;
		$mm = $r / $msec_mm; // divide rest by millisecs per minute => mins
		$r = $r % $msec_mm;
		$ss = $r / $msec_ss; // divide rest by millisecs per second => secs
		$r = $r % $msec_ss;
                
		?>
                
        <div class="bc-metadata">
        	<div class="bc-metadata-left">
                <!-- <p class="current">Current Video</p> -->
                <div id="vinfo">
                    <h2><?php if ($array['name']==NULL)  echo $array['name'].'We are processing  your video...'; else  echo $array['name'];  ?></h2>
                    <p><?php if ($array['shortDescription']==NULL)  echo 'Your video will appear online shortly. In the meantime, you do not have to wait for anything to change here. Thanks! If you put in an ID instead of uploading and you\'re seeing this message, you put in a bad id. Click the remove button and try again'; else  echo $array['shortDescription'];  ?></p><br />
                    <p><?php if ($array['length']==NULL)  echo''; else  echo sprintf("Duration: %02s:%02s:%02s", (int)$hh, (int)$mm, (int)$ss); ?></p>
            	</div>
            </div>
            <div class="bc-metadata-right"> <img src="<?php echo $array['videoStillURL'] ?>" width="200" /> </div>
        </div>
        <button id="remove_video">Remove Video</button>
    	<div id="clearvid">
        <input type="hidden" name="currentid" value="<?php if(!empty($meta['videoid'])) echo $meta['videoid']; ?>"/>
        <input type="submit" value="Delete Video" id="delete_video"  style="opacity:0" onclick="clear();"/>
            <iframe id="delete_target" name="delete_target" src="#" style="width:0;height:0;"></iframe>
        </div>
    <?php  }; ?>
    
</div>
<div class="my_meta_control">
	<div class="login-head">
    	<h1>Life is easy - when we make it simple!</h1>
    </div>
    <div class="close"><p>(X)</p></div>
    <div class="login-logo"></div>
	<div id="content-upload">
        
    </div>
    <div id="div-or"></div>
    <div id="update-id"><input id="bcid" type="text" placeholder="Add Video ID" name="_my_meta[videoid]" value="<?php if(!empty($meta['videoid'])) echo $meta['videoid']; ?>"/><button id="add_id_btn">Attach video...</button></div>
    <?php if ($storeid == NULL) { ?>
     <script>
	  jQuery(window).load(function() {
  	
	jQuery("#content-upload").append('<form action="http://www.byuicomm.net/wp-content/themes/icomm/includes/metabox/new_upload.php" method="post" name="myForm" enctype="multipart/form-data" target="upload_target" onsubmit="return startUpload();" ><iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe><p id="f1_upload_process">Uploading your video...<br/><img src="http://www.byuicomm.net/wp-content/themes/icomm/includes/metabox/images/loading.gif" /><br/></p><div id="f1_upload_form" align="center"><ul><li><input type="text" name="file_name" id="file_name" placeholder="Select your file..." /><input type="file" name="bcVideo" id="file-button" onchange="this.form.file_name.value = this.value;" /></li><li><input type="text" name="bcName" id="title-count" placeholder="Video Title" /></li><li><textarea name="bcShortDescription" id="count" placeholder="Description"></textarea></li><li><input type="submit" name="submitBtn" class="bt-primary" value="Upload Now..." /></li></ul></div><p id="f2_upload_form" align="center"></p></form>');});
    
</script>
             
	<?php  } else  { ?>
    
    <script>
	jQuery(window).load(function() {
    jQuery('#clearvid').wrap('<form action="http://www.byuicomm.net/wp-content/themes/icomm/includes/metabox/delete_video.php" method="post" target="delete_target" enctype="multipart/form-data" />');});
	jQuery('#delete_video').css("opacity",1);
	jQuery('#delete_video').click(function() { jQuery('#bcid').val('');});
	jQuery('#delete_video').click(function() { jQuery('#delete_video').css("opacity",0);});
    </script>
    <?php } ?>
</div>

<script type="text/javascript" src="<?php bloginfo('template_url') ?>/includes/metabox/js/jquery.counter-1.0.min.js"></script>
<script src="http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js"></script>

<script language="javascript" type="text/javascript">
jQuery(function() {
		jQuery(".upload_start[rel]").overlay({mask:{color: '#000',loadSpeed: 500,opacity: 0.5}, closeOnClick:true });
});


jQuery(window).load(function() {
jQuery("#count").counter({goal: 250});
jQuery("#title-count").counter({goal: 250});
});

function validateForm(){
	
	alert("this is an alert test");

};


function startUpload(){
	  var a=document.forms["myForm"]["bcName"].value
	  var b=document.forms["myForm"]["bcShortDescription"].value
	  var c=document.forms["myForm"]["file_name"].value
	  if (c==""){ alert("Come on! I thought you wanted to add a video, select your video to continue"); return false }  
	  if (a==""){ alert("Add the title, it look likes is empty!"); return false } 
	  if (b==""){ alert("Add the description"); return false }
	  
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
	  document.getElementById('update-id').style.visibility = 'hidden';
	  document.getElementById('div-or').style.visibility = 'hidden';
	  
	  //document.getElementById("upload_target").onload = uploadDone; //This function should be called when the iframe has compleated loading
			// That will happen when the file is completely uploaded and the server has returned the data we need.
      return true;
}

function uploadDone() { 
	alert('upload done!')
}

function stopUpload(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">Your video was uploaded successfully! Now <b>update</b> your post to save your video.</span><div><button id="okay" class="close_control">Okay</button></div><style>#f1_upload_form{width:700px !important;}</style>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!</span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result ;
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
	  return true;
}
function respUpload(resp){
	document.getElementById('bcid').value = resp ;
}
jQuery('#add_id_btn').click(function(){
	if(jQuery('#title').val() == '') 
		jQuery('#title').val('POST TITLE');
});
jQuery(".close_control").live("click", function(e) {
	jQuery(".my_meta_control").hide();
	jQuery("#exposeMask").hide();
});
/*jQuery("#close_box").live("click", function(e) {
	jQuery(".my_meta_control").hide();
	jQuery("#exposeMask").hide();
});*/
//-->
jQuery("#remove_video").click(function(e) {
	jQuery("#bcid").val('');
	alert("Removing Video...");
});
</script>   

