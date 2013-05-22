<?php
/* Define the custom box */
add_action('add_meta_boxes', 'add_color_box' );
add_action('add_meta_boxes', 'add_video_box');
/* Do something with the data entered */
add_action( 'save_post', 'save_colors' );
add_action('save_post', 'save_video');

/****************************************
 * Color selection
 * *************************************/
function add_color_box() {
    add_meta_box('color_box', 'Colors', 'color_box_content', 'portfolio', 'side', 'high');	
}

/* Prints the box content */
function color_box_content( $post ) {
    wp_register_script('jscolor','http://byuicomm.net/wp-content/themes/icomm/includes/jscolor/jscolor.js');
    wp_enqueue_script('jscolor');
    $background = get_post_meta($post->ID, 'background_color', true);
    if($background == ''){
        $background = '#000000';
    }
    $foreground = get_post_meta($post->ID, 'foreground_color', true);
    if($foreground == ''){
        $foreground = '#FFFFFF';
    }
    
    ?>
    <label for="background">Background</label>
    <input id="background" class="color" type="text" name="background" size="7" maxlength="7" value="<?php echo $background; ?>">
    <br />
    <label for="foreground">Foreground</label>
    <input id="foreground" class="color" type="text" name="foreground" size="7" maxlength="7" value="<?php echo $foreground; ?>">
    <br />
    <p id="sample-colors" style="min-height:30px;line-height:35px;padding:5px;font-size: 25px;color:<?php echo $foreground ?>;background-color:<?php echo $background ?>">This is what the colors will look like</p>
    
    <script type="text/javascript">
        jQuery('.color').blur(function(){
           if(validateColors(this))
               setSample();
        });
        jQuery('#post').submit(function(e){
           e.preventDefault();
           if(validateColors(jQuery('#background').get(0)) && validateColors(jQuery('#foreground').get(0))){
               this.submit();
           }else{
               alert('One or more of your colors are invalid');
           }
        });
        function validateColors(input){
            var regex = /^(#)?([0-9a-fA-F]{3})([0-9a-fA-F]{3})?$/;
           if(!regex.test(input.value) && jQuery("#" + input.id + '-error').length != 1){
               jQuery(input).after("<span id='" + input.id + "-error' style='color:red'>Invalid Color</span>").select();
               return false;
           }else if(regex.test(input.value)){
               jQuery("#" + input.id + '-error').remove();
               return true;
           }
        }
        function setSample(){
            var background = jQuery('#background').val();
            if(background.search('#') == -1){
                background = "#" + background;
            }
            var foreground = jQuery('#foreground').val();
            if(foreground.search('#') == -1){
                foreground = "#" + foreground;
            }
            jQuery('#sample-colors').css({'background-color':background, 'color':foreground})
        }
    </script>
<?php }

/* When the post is saved, saves our custom data */
function save_colors( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ($_POST['post_type'] != 'portfolio')
      return;
  
  if ( !current_user_can( 'edit_post', $post_id ) )
      return;
  
    $background = $_POST['background'];
    $foreground = $_POST['foreground'];
      //add # if it's not there
      if(strpos($background, '#') === false){
          $background = '#'.$background;
      }
      if(strpos($foreground, '#') === false){
          $foreground = '#'.$foreground;
      }
      //update the data!
      update_post_meta($post_id, 'background_color', $background);
      update_post_meta($post_id, 'foreground_color', $foreground);
  
}

/****************************************
 * Video gallery
 * *************************************/
/* Adds a box to the main column on the Post and Page edit screens */
function add_video_box() {
    add_meta_box('video_box', 'Videos', 'video_box_content', 'portfolio', 'normal', 'high');	
}

/* Prints the box content */
function video_box_content( $post ) {
?>
    <style type="text/css">
        .attached-video {
            border: 1px solid #DFDFDF;
            display: block;
            margin-bottom: 10px;
            overflow: auto;
            padding: 8px;
            position: static;
        }
        .left {
            display: inline-block;
            float: left;
            min-width: 130px;
            width: 25%;
        }
        .right {
            display: inline-block;
            float: left;
            position: relative;
            width: 75%;
        }
        .left img {
            margin: 10px 0 0 10px;
        }
        .video-title {
            margin-top: 0;
        }
        #add-video-field {
            display: block;
        }

    </style>
    <p>Please insert the IDs of the videos in the order you want them to appear, (ID's can be found in the video gallery) Then save your post to attach the video.<br />
	If you need to upload a new video go to the <a href="http://www.byuicomm.net/wp-admin/admin.php?page=bc_video_library">video library</a>, click on the upload button in the top right corner and follow the instructions from there.</p>
    <?php 
        $video_ids = explode(',', get_post_meta($post->ID, 'video_ids', true));
        
        $count = 1;
        if($video_ids[0] != ''){
            require(TEMPLATEPATH.'/includes/metabox/bc-mapi.php');
            $brightcove = new BCMAPI('5lr_GNp0hRNSZA31TEWbzPQgygwb6H277DEXcZLPAmbBzVJVeVE2Ig..');
            echo "<ul id=current-videos>";
            foreach($video_ids as $video_id){
                $params = array(
                   'video_id' => $video_id,
                   'video_fields' => 'name,shortdescription,length,thumbnailurl,tags'
                );
                $video = $brightcove->find('videoById', $params);
                $all_seconds = floor($video->length / 1000);
                $minutes = floor($all_seconds / 60);
                $seconds = floor($all_seconds - ($minutes * 60));
                ?>
                <li class="attached-video">
                    <div class="left">
                        <label for="video<?php echo $count ?>">Video #<?php echo $count ?></label><input id="video<?php echo $count; ?>" name="video[]" value="<?php echo $video_id ?>"><br />
                        <img src="<?php echo $video->thumbnailURL; ?>" alt="thumbnail" title="Video Thumbnail">
                        <button class="remove-video button">Remove Video</button>
                    </div>
                    <div class="right">
                    <h2 class="video-title"><?php echo $video->name ?></h2>
                    <p class="video-description"><?php echo $video->shortDescription ?></p>
                    <p class="video-length"><b>Duration: </b><?php echo str_pad($minutes,2,'0',STR_PAD_LEFT).':'.str_pad($seconds,2,'0',STR_PAD_LEFT); ?></p>
                    <p class="video-tags"><b>Tags: </b><?php if(sizeof($video->tags) > 0 ){
                        foreach($video->tags as $tag);
                        echo $tag.',';
                    }?></p>
                    
                    </div>
                </li>
                <?php 
                 $count++;
            }
            echo "</ul>";
         }//end if 
         echo "<script type='text/javascript'>var videoCount = $count </script>"; ?>
    <button id="add-video-field" class="button">Add another video</button>
    <script type="text/javascript">
        jQuery('#add-video-field').click(function(e){
           e.preventDefault();
           jQuery(this).before("<label for='video" + videoCount + "'>Video #" + videoCount + "</label><input id='video" + videoCount + "' name='video[]'><br />"); 
           videoCount++;
           return false;
        });
        jQuery('.remove-video').click(function(e){
            e.preventDefault();
            jQuery(this).parents('.attached-video').remove();
            return false;
        });
    </script>
<?php }

/* When the post is saved, saves our custom data */
function save_video( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ($_POST['post_type'] != 'portfolio')
      return;
  if ( !current_user_can( 'edit_post', $post_id ) )
      return;
  
  include_once('bc-mapi.php');
  
  $brightcove = new BCMAPI('5lr_GNp0hRNSZA31TEWbzPQgygwb6H277DEXcZLPAmbBzVJVeVE2Ig..');
  $ids = array();
  if (!empty($_POST['video']))
	  foreach($_POST['video'] as $videoID){
		  if($videoID != '' && is_numeric($videoID)){
			  //make sure this is actually a video on the servers
			   $video = $brightcove->find('videoById', $videoID);
			   if($video != NULL){
				   $ids[] = $videoID;
			   }
		  }
	  }
  update_post_meta($post_id, 'video_ids', implode(',',$ids));
}

//remove the custom metadata editor box
add_action('admin_menu', 'remove_custom_box');
function remove_custom_box(){
    remove_meta_box('postcustom', 'portfolio', 'normal' );
    remove_meta_box('trackbacksdiv', 'portfolio', 'normal' );
    remove_meta_box('categorydiv', 'portfolio', 'side' );
}
?>