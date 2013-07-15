<?php 

function wpbootstrap_scripts_with_jquery() 
{

    wp_enqueue_script("jquery");

    wp_register_script( 'custom', get_template_directory_uri() . '/bootstrap/js/custom.js', array( 'jquery' ) ); 
	// For either a plugin or a theme, you can then enqueue the script: 
	wp_enqueue_script( 'custom' );

	 // Register the script like this for a theme: 
	wp_register_script( 'bootstrap', get_template_directory_uri() . '/bootstrap/js/bootstrap.js', array( 'jquery' ) ); 
	// For either a plugin or a theme, you can then enqueue the script: 
	wp_enqueue_script( 'bootstrap' ); 
} 
add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' ); 

/****************************************************
* Register Widgets here
***************************************************/
/* Front Page Dynamic Sidebar */
add_action("widgets_init", "register_my_sidebars");

function register_my_sidebars(){
	/******FRONT PAGE**************/
	$args = array( 'name' => 'Front Page',
				   'id'   => 'front-page',
				   'before_widget' => "<div class='row-fluid'>",
				   'after_widget'  => "</div>",
				   'before_title' => "<div class='section-header span12'>",
				   'after_title' => '</div> </div> <div class="container-fluid videos">');
	register_sidebar($args);
	$args = array( 'name' => 'Custom Menu',
					'id'  =>  'custom-menu',
					'description' => "Custom Menu With Campus, Video, etc");
	register_sidebar($args);
	/******SINGLE POST*************/
	$args = array( 'name' => 'Post Page',
				   'id'   => 'single-page',
				   'before_widget' => "<div class='row-fluid'>",
				   'after_widget'  => "</div> ",
				   'before_title' => "<div class='section-header span12'>",
				   'after_title' => '</div>');
	register_sidebar($args);
	/*****FACEBOOK ACTIVITY*******/
	$args = array( 'name' => 'Facebook Activity',
				   'id'   => 'facebook',
				   'before_widget' => "<div class='row-fluid'>",
				   'after_widget'  => "</div> </div> </div>",
				   'before_title' => "</div> <div class='row-fluid'> <div class='section-header span12'>",
				   'after_title' => '</div> </div> <div class="container-fluid video videos">');
	register_sidebar($args);
}

/* Recent Videos Sidebar */
wp_register_sidebar_widget('latest-videos', 'Latest Videos', 'latest_videos_content');

function latest_videos_content($args){
	extract($args);
	echo $before_widget;
	echo $before_title."Latest Videos".$after_title;
	$args = array(
		'posts_per_page' => 8, 
		'post_status' => 'publish',
		'meta_key' => '_my_meta', 
		'meta_value' => NULL, 
		'meta_compare' => '!=');
	$query = new WP_Query($args);
	$i = 0;
	if( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
		?>
        <?php 

        	if( $i == 0 ) 
        		echo '<div class="row-fluid video first-video">';
        	else 
        		echo '<div class="row-fluid video">';
			?>

			<div class='span4 crop100'> 
				<?php the_post_thumbnail(array(100,100)); ?>
			</div> 
			<div class='span8'>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><p><?php the_title(); ?></p></a>
			</div>
        </div>
		<?php
	endwhile; endif;
	echo $after_widget;
}

/* Custom Menu Wiget 
   I do apologize for the bit of nazty code, if you think you can straighten it up, feel free */
wp_register_sidebar_widget('custom-menu', 'Custom Menu', 'custom_menu_content');

function custom_menu_content(){
	echo '<div class="navbar-wrapper" data-spy="affix" data-offset-top="450">
      <div class="extranavbar-inner">
      <div class="container">

        <div class="navbar extranav">
          <ul class="nav">
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('scroll digital') ); echo '"">Video</a>

            </li>
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('campus') ); echo '">Campus</a>
            </li>
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('news') ); echo'">News</a>
            </li>
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('entertainment') ); echo'">Entertainment</a>
            </li>
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('lifestyle') ); echo'">Lifestyle</a>
            </li>
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('sports') ); echo '">Sports</a>
            </li>
            <li class="rightborder">
              <a href="'; echo get_category_link( get_cat_ID('opinion') ); echo '">Opinion</a>
            </li>
            <li>
              <a href="'; echo get_category_link( get_cat_ID('español') ); echo '">Español</a>
            </li>
          </ul>
            <div class="control-group">
              <div class="controls">
                <div class="input-append">
                  <input class="span3" id="inputIcon" type="text">
                  <span class="add-on"><i class="icon-search"></i></span>
                </div>
              </div>
            </div>
  	      </div><!-- /.navbar -->
  	      <hr>
  	    </div> <!-- /.container -->
  	  </div>
  	</div><!-- /.navbar-wrapper -->';
}

/****************************************************
* Register Navigation
***************************************************/
add_action( 'init', 'my_custom_menus' );
function my_custom_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu' ),
            'secondary-menu' => __( 'Secondary Menu' )
        )
    );
}

/*************************************************************
* Walker Nav built to better implement Bootstrap Menus in WP
**************************************************************/

add_action( 'after_setup_theme', 'bootstrap_setup' );
 
if ( ! function_exists( 'bootstrap_setup' ) ):
 
	function bootstrap_setup(){
 
		add_action( 'init', 'register_menu' );
			
		function register_menu(){
			register_nav_menu( 'top-bar', 'Bootstrap Top Menu' ); 
		}
 
		class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {
 
			
			function start_lvl( &$output, $depth ) {
 
				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";
				
			}
 
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				
				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
				$li_attributes = '';
				$class_names = $value = '';
 
				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = ($args->has_children) ? 'dropdown' : '';
				$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
				$classes[] = 'menu-item-' . $item->ID;
 
 
				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';
 
				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
 
				//I'm ghetto-rigging this code so I can add a class to all but the last list item, feel free to fix / improve
				//Essentually I'm just checking to see if the item ID is that of the 'Pathway' page

				$item->ID == 33102 ? $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>' : $output .= $indent . '<li class="rightborder"' . $id . $value . $class_names . $li_attributes . '>';
 
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';
 
				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
				$item_output .= $args->after;
 
				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
 
			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
				
				if ( !$element )
					return;
				
				$id_field = $this->db_fields['id'];
 
				//display this element
				if ( is_array( $args[0] ) ) 
					$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
				else if ( is_object( $args[0] ) ) 
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] ); 
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'start_el'), $cb_args);
 
				$id = $element->$id_field;
 
				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
 
					foreach( $children_elements[ $id ] as $child ){
 
						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
						unset( $children_elements[ $id ] );
				}
 
				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
				}
 
				//end this element
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'end_el'), $cb_args);
				
			}
			
		}
 
	}
 
endif;

/***************************
* allow custom user profile pictures
*********************************/
//size is an array (width,height)
function get_profile_picture($id, $size){
    $image = get_user_meta($id, 'profile_picture', true);
    if($image != ''){
        return wp_get_attachment_image($image, $size);
    }else{
        return get_avatar($id, 50);
    }
}

//add additional fields to profile page in the dashboard
function add_custom_user_profile_fields( $user ) {
?>
	<table class="form-table">
        <tr>
            <th>
                <label for="profile_picture"><?php _e('Profile Picture', 'your_textdomain'); ?></label>
            </th>
            <td>
                <input type="file" id="profile_picture" name="profile_picture">
                <span class="description"><?php _e('Upload a picture of yourself', 'your_textdomain'); ?></span>
            </td>
        </tr>
        <?php
        	$img = wp_get_attachment_image_src(get_the_author_meta('profile_picture', $user->ID), array(294, 9999)); 
                if ($img[0] != ''){ ?>
                    <th><img alt="Current Picture" src="<?php echo $img[0] ?>"/></th>
                <?php } ?>
                
	</table>
	<script type="text/javascript">
	    //if you don't change the form type when you update wordpress this should cover it
	    if(document.getElementById("your-profile").encoding != "multipart/form-data"){
	        document.getElementById("your-profile").encoding = "multipart/form-data"
	    }
	</script>
<?php }

function save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	//this deals with image upload
	// If the upload field has a file in it
	if(isset($_FILES['profile_picture']) && ($_FILES['profile_picture']['size'] > 0)) {

	// Get the type of the uploaded file. This is returned as "type/extension"
		$arr_file_type = wp_check_filetype(basename($_FILES['profile_picture']['name']));
		$uploaded_file_type = $arr_file_type['type'];

		// Set an array containing a list of acceptable formats
		$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');

		// If the uploaded file is the right format
		if(in_array($uploaded_file_type, $allowed_file_types)) {

		    // Options array for the wp_handle_upload function. 'test_upload' => false
		    $upload_overrides = array( 'test_form' => false ); 

		    // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
		    $uploaded_file = wp_handle_upload($_FILES['profile_picture'], $upload_overrides);

		    // If the wp_handle_upload call returned a local path for the image
		    if(isset($uploaded_file['file'])) {

		        // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
		        $file_name_and_location = $uploaded_file['file'];
		      
		        // Generate a title for the image that'll be used in the media library
		        $file_title_for_media_library = get_the_author_meta('display_name', $user_id);

		        // Set up options array to add this file as an attachment
		        $attachment = array(
		            'post_mime_type' => $uploaded_file_type,
		            'post_title' => 'Profile Picture: ' . addslashes($file_title_for_media_library),
		            'post_content' => '',
		            'post_status' => 'inherit'
		        );

		        // Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
		        $attach_id = wp_insert_attachment( $attachment, $file_name_and_location );
		        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
		        wp_update_attachment_metadata($attach_id,  $attach_data);

		        // Before we update the author meta, trash any previously uploaded image for this post.
		        if(get_the_author_meta('profile_picture', $user_id) != '') {
		            wp_delete_attachment(get_the_author_meta('profile_picture', $user_id));
		        }
		        //add the new image path to the user meta
		        update_usermeta( $user_id, 'profile_picture', $attach_id);
		    } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.

	            $upload_feedback = 'There was a problem with your upload.';
	            update_post_meta($post_id,'_xxxx_attached_image',$attach_id);

	        }
	    } else { // wrong file type
	        $upload_feedback = 'Please upload only image files (jpg, gif or png).';
	        update_post_meta($post_id,'_xxxx_attached_image',$attach_id);
	    }

    }

}

add_action( 'show_user_profile', 		'add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 		'add_custom_user_profile_fields' );
add_action( 'personal_options_update', 	'save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_custom_user_profile_fields' );

//hide the default wp-admin bar from the front-end
show_admin_bar( false );

/*************************************************************************************
* Get the author's role
************************************************************************************/
// get author role
function get_user_role($id) {
	$user = new WP_User($id);
	return array_shift($user->roles);
}

/****************************************************************************************
* Function that will allow us to see post views
****************************************************************************************/

// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
}
function posts_custom_column_views($column_name, $id){
	if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}

/*************************** FROM OLD FILE ***************/


/*-----------------Update LDP playlist on save------------------------------*/
add_action('save_post','update_ldp_playlist');
function update_ldp_playlist(){
    
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ($_POST['post_type'] != 'ldpshow')
      return;
  if ( !current_user_can( 'edit_post', $post_id ) )
      return;
  if (!isset($_POST['_my_meta']))
	  return;
  
  //Get the data from the database
  $args = array('post_status' => 'publish',
		'meta_key' => '_my_meta', 
		'meta_value' => NULL, 
		'meta_compare' => '!=',
                'post_type' => 'ldpshow',
                'posts_per_page' => -1);
	
  query_posts($args);
  $videoIDs = array();
  
  if ( have_posts() ) : while ( have_posts() ) : the_post();
    $meta = get_post_meta(get_the_id(),'_my_meta',true);
    $videoIDs[] = intval($meta['videoid']);
  endwhile;
  endif;
  
  //send the data to brightcove
    include_once TEMPLATEPATH.'/includes/metabox/bc-mapi.php';
    $brightcove = new BCMAPI('5lr_GNp0hRNSZA31TEWbzPQgygwb6H277DEXcZLPAmbBzVJVeVE2Ig..',
        '6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..');
    try {
        $brightcove->update('playlist', array("id" => 1787101296001, "videoIds" => $videoIDs));
    } catch(Exception $error) {
        //wp_die($error);
    }
}
/*-----------------Update video playlist on save------------------------------*/
add_action('save_post','update_video_playlist');
function update_video_playlist(){
    
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
  if ($_POST['post_type'] != 'post')
      return;
  if ( !current_user_can( 'edit_post', $post_id ) )
      return;
  if (!isset($_POST['_my_meta']))
	  return;
  //Get the data from the database
 $args = array('orderby' => 'date',
                'posts_per_page' => 100,
                'post_status' => array('publish','private'),
                'meta_key' => '_my_meta',
                'meta_value' => NULL,
                'meta_compare' => '!=');
	
  query_posts($args);
  $videoIDs = array();
  
  if ( have_posts() ) : while ( have_posts() ) : the_post();
    $meta = get_post_meta(get_the_id(),'_my_meta',true);
    $videoIDs[] = intval($meta['videoid']);
  endwhile;
  endif;
  
  //send the data to brightcove
    include_once TEMPLATEPATH.'/includes/metabox/bc-mapi.php';
    $brightcove = new BCMAPI('5lr_GNp0hRNSZA31TEWbzPQgygwb6H277DEXcZLPAmbBzVJVeVE2Ig..',
        '6FHUqplUwtI-VGmNoT9_fKuqoKqNHzFmazmVce4w8SZPSiLdFLjkMQ..');
    try {
        $brightcove->update('playlist', array("id" => 1797321265001, "videoIds" => $videoIDs));
    } catch(Exception $error) {
        //wp_die($error);
    }
}