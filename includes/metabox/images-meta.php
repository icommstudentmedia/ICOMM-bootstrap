<?php
/****************************************
 * *IMAGE GALLERY
 * *************************************/
add_action('add_meta_boxes', 'add_image_box');
add_action('save_post', 'save_images');
function add_image_box() {
    add_meta_box('image_box', 'Attached Images', 'image_box_content', 'post', 'normal', 'high');	
    add_meta_box('image_box', 'Attached Images', 'image_box_content', 'portfolio', 'normal', 'high');
    add_meta_box('image_box', 'Attached Images', 'image_box_content', 'blogs', 'normal', 'high');
    add_meta_box('image_box', 'Attached Images', 'image_box_content', 'pathwaypost', 'normal', 'high');
}
function image_box_content($post){
    ?><style type="text/css">
        .image-attachment{
            max-height: 150px; /*These have to be here because not all the images have proper thumbnail sizes.*/
            max-width: 150px;
			margin: auto;
			bottom: 0;
			top: 0;
			position: absolute;
        }
		.image-wrap{
			width: 150px;
			height: 150px;
			position: relative;
		}
        .image-box{
            display: inline-block;
            width: 150px;
            min-height: 150px;
            vertical-align: top;    
			margin:2px;
			padding: 2px;
			background-color: #DFDFDF;
        }
        .delete-field{
            visibility: hidden;
        }
        .image-options{
            margin: 0;
            padding: 2px 0px;
        }
        .image-options li{
            width: 150px;
			
        }
		.image-options .delete-button{
			float: right;
			margin: 0 2px;
		}
		.image-options .remove-button{
			float: left;
			margin: 0 2px;
		}
        #attach-images-box{
            height: 500px;
            overflow: scroll;
        }
        .unattached-image-wrap{
            width: 150px;
            display: inline-block;
            margin: 4px;
        }
        .unattached-image-wrap img{
            max-width: 150px;
            max-height: 150px;
        }
        .attached-field{
            visibility: hidden;
        }
        #attach-images-loader{
            margin-left: auto;
            margin-right: auto;
            display: block;
            margin-top: 220px;
        }
        #close-attach-images{
            float: right;
            margin-right: 5px;
        }
        #top-wrapper p{
            display: inline-block;
        }
    </style>
        <p>Your images will appear in the order shown below. Unfortunately it isn't possible to attach an image to multiple posts.</p>
    
        <?php $user = wp_get_current_user();
            
    
    $args = array('post_parent' => $post->ID,
                    'post_status' => 'inherit',
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'order' => 'ASC',
                    'orderby' => 'menu_order ID');
    $attachments = get_children($args);
    foreach($attachments as $attachment){
        ?><div class="image-box" id="<?php echo $attachment->ID ?>">
			<div class="image-wrap">
				<img src="<?php echo wp_get_attachment_thumb_url($attachment->ID)?>" class="image-attachment">
			</div>
            <?php if(current_user_can('delete_posts')){ ?>
            <ul class="image-options">
                <li><label for="order">Order:</label>
                <input name="order[<?php echo $attachment->ID ?>]" class="order-field" size="2" value="<?php echo $attachment->menu_order ?>"></li>
                <li><button class="remove-button button">Unattach</button></li>
				<?php if(current_user_can('delete_posts')){ ?>
					<li><button class="delete-button button">Delete</button></li>
                <?php } } ?>
            </ul>
        </div>
        <?php
    } ?>
    <button id="add-image-button" class="button">Add More Images</button>
    <script type="text/javascript">
        <?php if(current_user_can('delete_posts')){ ?> 
        jQuery('.delete-button').click(function(e){
           e.preventDefault();
           jQuery(this).parents('.image-box').html("<p>This image will be deleted when you save your post</p><input type='text' class='delete-field' hidden='hidden' name='deleteimage[]' value='" + jQuery(this).parents('.image-box').attr('id') + "'>");
           
           return false;
        });
        <?php } ?>
        jQuery('.remove-button').click(function(e){
           e.preventDefault();
           jQuery(this).parents('.image-box').html("<p>This image will be removed when you save your post</p><input type='text' class='remove-field' hidden='hidden' name='removeimage[]' value='" + jQuery(this).parents('.image-box').attr('id') + "'>");
           return false;
        });
        jQuery('#add-image-button').click(function(e){
           e.preventDefault();
           if(jQuery('#attach-images-box').length == 0){
                jQuery('#image_box').append("<div id='attach-images-box'><img id='attach-images-loader' src='http://byuicomm.net/wp-content/themes/icomm/images/large-ajax-loader.gif' alt='Loading...'></div>");
                jQuery('#attach-images-box').load(ajaxurl, {action: 'unattached_images'}, function(){
                    jQuery('#attach-images-box button.attach').click(function(e){
                       e.preventDefault();
                       if(jQuery(this).parent().children('input').length == 0){
                            jQuery(this).text("Unattach").after("<input type='text' class='attached-field' hidden='hidden' name='attachimage[]' value='" + jQuery(this).parent().attr('id') + "'>");
                       }else{
                           jQuery(this).text("Attach").parent().children('input').remove();
                       }
                       
                       return false;
                    });
                    
                    jQuery('#close-attach-images').click(function(e){
                       jQuery('#attach-images-box').remove(); 
                    });
                });
       
            }
           
           
           return false;
        });
    </script><?php
}

function save_images($post_id){
    
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ( !current_user_can( 'edit_post', $post_id ) )
      return;
  global $wpdb;
  //delete images
  if(isset($_POST['deleteimage']) && current_user_can('delete_posts'))
	  foreach($_POST['deleteimage'] as $attachmentid){
		  wp_delete_attachment($attachmentid);
  }
  //remove images
if(isset($_POST['removeimage']))
  foreach($_POST['removeimage'] as $attachmentid){
      if(is_numeric($attachmentid)){
          $wpdb->query($wpdb->prepare(
                "
                UPDATE $wpdb->posts 
                SET post_parent = 0, menu_order = 0
                WHERE ID = %d 
                  AND post_type = 'attachment'
                ", $attachmentid
));
      }
  }
  //change attachment order, need to pass both key, which is the id of the attachment, and value, which is the desired order number
    if(isset($_POST['order']))
  foreach($_POST['order'] as $key => $value){
      if(is_numeric($key) && is_numeric($value)){
          $wpdb->query($wpdb->prepare(
                "
                UPDATE $wpdb->posts 
                SET menu_order = %d
                WHERE ID = %d 
                  AND post_type = 'attachment'
                ", $value, $key
                  
          ));
      }
  }
    if(isset($_POST['attachimage']))
  foreach($_POST['attachimage'] as $attachmentid){
      if(is_numeric($attachmentid)){
          $wpdb->query($wpdb->prepare(
                  "
                  UPDATE $wpdb->posts 
                  SET post_parent = %d
                  WHERE ID = %d
                  AND post_type = 'attachment'
                ", $post_id, $attachmentid
                  ));
      }
  }

}
?>
