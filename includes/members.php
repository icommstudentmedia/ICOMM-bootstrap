<?php

/**
* MEMBERS.PHP
*
* Description: (include where it is called)
*
*
* 
*
*
* 
*
* @author
*
*
****/

//
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
 
 /**
* Extra User Profile Fields
*
*
*
* @author
* @param
* @return
*/
function extra_user_profile_fields( $user ) { ?>
<h3><?php _e("Extra profile information", "blank"); ?></h3>
 
<table class="form-table">
<tr>
<th><label for="position"><?php _e("Position"); ?></label></th>
<td>
<input type="text" name="position" id="position" value="<?php echo esc_attr( get_the_author_meta( 'position', $user->ID ) ); ?>" class="regular-text" /><br />
<span class="description"><?php _e("Please enter your current position in I~Comm."); ?></span>
</td>
</tr>
<tr>
<th><label for="twitter"><?php _e("Twitter Account"); ?></label></th>
<td>
<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
<span class="description"><?php _e("Please enter your twitter."); ?></span>
</td>
</tr>
<tr>
<th><label for="organization"><?php _e("Organization"); ?></label></th>
<td>
<input type="text" name="organization" id="organization" value="<?php echo esc_attr( get_the_author_meta( 'organization', $user->ID ) ); ?>" class="regular-text" /><br />
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
*
*
*
* @author
* @param
* @return
**/
function save_extra_user_profile_fields( $user_id ) {
 
if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
 
update_usermeta( $user_id, 'position', $_POST['position'] );
update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
update_usermeta( $user_id, 'organization', $_POST['organization'] );
}
?>