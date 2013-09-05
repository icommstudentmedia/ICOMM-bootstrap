<?php    

/**
* @ MEMBERS-LOOP.PHP
*
* Description: (include where it is called)
*               Gizmo = I'm not sure this file
*               is currently being used cause
*               I didn't find a call to include it
*
*
* 
*
* @author
*
*
*
**/


// prepare arguments
        $args  = array(
        // search only for Authors role
        'role' => 'Editor',
        // order results by display_name
        'orderby' => 'display_name',
		);
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
				?>
				<?php if($organization=='Agency')?>
				
				<li class="tile"> 
				<?php $author_info->first_name?> 
                <?php $author_info->last_name?>
                <?php $author_info->organization?> 
                <?php $author_info->position?> 
                <?php get_avatar( $author_info->user_email, '64' )?>
                </li>
                
           <?php }
        } else {
            echo 'No authors found';
        }?>
