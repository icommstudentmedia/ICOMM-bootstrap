<?php

/**
* MEMBERS.PHP
*
* Description: 
*     This file is called in functions.php line 581
*     These functions will add extra information under
*     the user profile section. 
*     The fields added will be:
*         - Position
*         - Twitter Account
*         - Organization
*
* @author
*
****/

//
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
 
 /**
* Extra User Profile Fields
*      This function provides additional fields to
*      be completed and filled by the user. 
*
* @author
* @param   $user (current user's profile)
* @return  
*/
function extra_user_profile_fields( $user ) { ?>
<!-- Sub-Title for the new information -->
<h3><?php _e("Extra profile information", "blank"); ?></h3>
 
<table class="form-table">
<tr>
	<!-- First Field: Position -->
<th><label for="position"><?php _e("Position"); ?></label></th>
<td>
<input type="text" name="position" id="position" value="<?php echo esc_attr( get_the_author_meta( 'position', $user->ID ) ); ?>" class="regular-text" /><br />
    <!-- Additional description under the form -->
<span class="description"><?php _e("Please enter your current position in I~Comm."); ?></span>
</td>
</tr>
<tr>
	<!-- Second Field: Twitter Account -->
<th><label for="twitter"><?php _e("Twitter Account"); ?></label></th>
<td>
<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
   <!-- Additional description under the form -->
<span class="description"><?php _e("Please enter your twitter."); ?></span>
</td>
</tr>
<tr>
	<!-- Third Field: Organization -->
<th><label for="organization"><?php _e("Organization"); ?></label></th>
<td>
<input type="text" name="organization" id="organization" value="<?php echo esc_attr( get_the_author_meta( 'organization', $user->ID ) ); ?>" class="regular-text" /><br />
    <!-- Additional description under the form -->
<span class="description"><?php _e("Please enter the Organization that you belong to."); ?></span>
</td>
</tr>


</table>
<?php }
 
 //
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
 

 /**
* Save Extra User Profile Fields
*    This function will save the new information 
*    about the user in the database
*
* @author
* @param  $user_id (current user's id)
* @return 
**/
function save_extra_user_profile_fields( $user_id ) {
 
if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
 
update_usermeta( $user_id, 'position', $_POST['position'] );
update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
update_usermeta( $user_id, 'organization', $_POST['organization'] );
}
?>