	<?php            
// prepare arguments
        $args  = array('role' => 'Administrator','orderby' => 'display_name',);
        // Create the WP_User_Query object
        $wp_user_query = new WP_User_Query($args);
        // Get the results
        $authors = $wp_user_query->get_results();
        // Check for results
        
        if (!empty($authors))
        {
            // loop trough each author
            foreach ($authors as $author)
            {	
                // get all the user's data
                $author_info = get_userdata($author->ID);
				$organization= $author_info->organization;
				$image = null;
				$fbImage = false;
				$imageID = get_user_meta($author->ID, 'profile_picture', true);
				if($imageID != ''){
					$image = wp_get_attachment_image($imageID, array(300, 9999));
				}else if(get_the_author_meta('fbconnect_userid', $author->ID) != 0){
					$fbImage = true;
				}else{
					$image = get_avatar($author->ID, 75);
				}
				
				?>
				<li class="tile boxgrid masonry">
                        <div class="avatar-wrap"><a href='<?php echo get_author_posts_url($author_info->ID) ?>'>
							<?php if($fbImage){ ?>
								<img class="fb_picture" src="https://graph.facebook.com/<?php echo get_the_author_meta('fbconnect_userid', $author->ID)?>/picture?type=large"/>
							<?php }else echo $image;//get_profile_picture($author->ID, 75) ?>
						</a></div>
                <h4><a href='<?php echo get_author_posts_url($author_info->ID) ?>'><?php echo $author_info->first_name; echo '&nbsp;'; echo $author_info->last_name; ?></a>
				- Agency <?php echo $author_info->position; ?></h4>
                <?php if($author_info->description != ''){?><p><?php echo $author_info->description ?></p><?php } ?> 
                
                    <?php if($author_info->twitter != ''){ ?>
                        <p class="about-meta">Follow me <a href="http://twitter.com/<?php echo $author_info->twitter?>"><?php echo $author_info->twitter ?></a></p>
                    <?php } ?>
                
                </li>
			
           <?php }
        } else {
            echo 'No authors found';
	}?>