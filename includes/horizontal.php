<?php
/*
Single Post Template: Horizontal 
Description: Choose the orientation of the slider
*/
?>
<?php $postid = get_the_ID(); ?> 
            <?php wp_reset_query(); ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div id="slides-h">
            	<?php
				$thumb_ID = get_post_thumbnail_id( $post->ID );
				$attachments = get_children(array('post_parent' => $post->ID,
										'post_status' => 'inherit',
										'post_type' => 'attachment',
										'post_mime_type' => 'image',
										'order' => 'ASC',
										'orderby' => 'menu_order ID',
										'exclude' => $thumb_ID));
				
				foreach($attachments as $att_id => $attachment) {
					$full_img_url = wp_get_attachment_url($attachment->ID);
						$split_pos = strpos($full_img_url, 'wp-content');
						$split_len = (strlen($full_img_url) - $split_pos);
						$abs_img_url = substr($full_img_url, $split_pos, $split_len);
						$full_info = @getimagesize(ABSPATH.$abs_img_url);
						?>
						<div class="WallSizesHolderh">
						<div class="WallSizesThumbHolder">
							<a href="<?php echo $full_img_url; ?>" title="<?php echo $attachment->post_title; ?>" target="_blank" rel="slb"><img src="<?php bloginfo('stylesheet_directory'); ?>/includes/timthumb.php?src=<?php echo $full_img_url; ?>&w=761&h=486&zc=1" /></a>
						</div>
					</div>
				<?php
				}
				?>
            </div>
            <div id="nav" class="nav-slider"></div>
            <div id="meta-data" class="meta-datah">
                <p><?php the_content(); ?></p><br />
                <?php 
					$meta = get_post_meta($post->ID,'_my_meta',TRUE);
					$video = $meta['videoid'];
					if($video==NULL) 
						//Metabox Script
					echo '';
					else {?>
                    	<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>
						<script>
						jQuery('#slides-h').append('<div class="WallSizesHolderh"><div class="WallSizesThumbHolder"><div style="display:none"></div><object id="myExperience" class="BrightcoveExperience"><param name="bgcolor" value="#FFFFFF" /><param name="width" value="760" /><param name="height" value="428" /><param name="playerID" value="753375228001" /><param name="playerKey" value="AQ~~,AAAAocwtmPk~,RTQzrMOt-UDc4WA-2qo6yl9dXGQG3Mff" /><param name="videoID" value="<?php echo $video; ?>" /><param name="isVid" value="true" /><param name="isUI" value="true" /><param name="dynamicStreaming" value="true" /></object></div>');							
                        </script>
                        <script type="text/javascript">brightcove.createExperiences();</script>
				<?php } ?>
            </div>
			<?php endwhile; else: ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			<?php endif; ?>
			
<div class="clear"></div>    
