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

//Clean Header
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
//thumbnail Support. These sizes are generated for each new image uploaded
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' ); // enable feature
	add_image_size( 'custom-post-image', 400, 225, true ); // custom size
        //add_image_size('blog-tile-thumb', 150, 9999); //fixed width of 150, varies in height. scaled, not cropped
        //add_image_size('blog-popular-thumb', 200, 9999);
		add_image_size('boxes', 230, 9999);
}

// disable the admin bar
show_admin_bar(false);    
//Metabox Script
include (TEMPLATEPATH . '/includes/metabox/script.php');
//Custom Post Types and Taxonomies Script
include (TEMPLATEPATH . '/includes/post_type.php');
//Ajax functions
include (TEMPLATEPATH . '/includes/ajax.php');
//Custom Members fields
include (TEMPLATEPATH . '/includes/members.php');
//Profile metaboxes
include (TEMPLATEPATH . '/includes/metabox/profile-meta.php');
//image attachment management metabox
include (TEMPLATEPATH . '/includes/metabox/images-meta.php');
//post visits counting functions
include (TEMPLATEPATH . '/includes/visit-count.php');
//Breaking news ticker
include (TEMPLATEPATH . '/includes/ticker.php');
//Remove [...] from excerpt
function new_excerpt_more($more) {
	return '.....';
}
add_filter('excerpt_more', 'new_excerpt_more');
// short urls generator
function getsupr($url) {
$supr = file_get_contents("http://su.pr/api?url=".$url);
return $supr;
}
function replace_uploaded_image($image_data) {
    // if there is no large image : return
    if (!isset($image_data['sizes']['large'])) return $image_data;

    // paths to the uploaded image and the large image
    $upload_dir = wp_upload_dir();
    $uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
    $large_image_location = $upload_dir['path'] . '/'.$image_data['sizes']['large']['file'];

    // delete the uploaded image
    unlink($uploaded_image_location);

    // rename the large image
    rename($large_image_location,$uploaded_image_location);

    // update image metadata and return them
    $image_data['width'] = $image_data['sizes']['large']['width'];
    $image_data['height'] = $image_data['sizes']['large']['height'];
    unset($image_data['sizes']['large']);

    return $image_data;
}
add_filter('wp_generate_attachment_metadata','replace_uploaded_image');

function themeoptions_admin_menu()  
{  
    // here's where we add our theme options page link to the dashboard sidebar  
	add_menu_page("Equipment", "Equipment", 'edit_posts', 'equipment_tools', 'equipment_tools_page', '/equipment-request-icon.png');  
	add_menu_page("Facility", "Facilities", 'edit_posts', 'facility_tools', 'facility_tools_page', '/facility-request-icon.png');  
}
function equipment_tools_page() 
{ 
    echo '<iframe src="http://www.byuicomm.net/admin/makereq.php" width="100%" height="800">
  	<p>Your browser does not support iframes.</p>
	</iframe>';
} 
function facility_tools_page() 
{ 
    echo '<iframe src="http://www.byuicomm.net/admin/makefacreq.php" width="100%" height="800">
  	<p>Your browser does not support iframes.</p>
	</iframe>';
} 

add_action('admin_menu', 'themeoptions_admin_menu');
if ( current_user_can('contributor') && !current_user_can('upload_files') )
    add_action('admin_init', 'allow_contributor_uploads');

function allow_contributor_uploads() {
    $contributor = get_role('contributor');
    $contributor->add_cap('upload_files');
}
/**
*	function get_profile_picture($id, $size){
*	    $image = get_user_meta($id, 'profile_picture', true);
*	    if($image != ''){
*	        return wp_get_attachment_image($image, array($size, $size));
*	    }else{
*	        return get_avatar($id, $size);
*	        
*	    }
*	}
*/
function improved_trim_excerpt($text) {
        global $post;
        if ( '' == $text ) {
                $text = get_the_content('');
                $text = apply_filters('the_content', $text);
                $text = str_replace('\]\]\>', ']]&gt;', $text);
                $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
				$text = preg_replace('@<style[^>]*?>.*?</style>@si', '', $text);
                $text = strip_tags($text, '<p>');
                $excerpt_length = 80;
                $words = explode(' ', $text, $excerpt_length + 1);
                if (count($words)> $excerpt_length) {
                        array_pop($words);
                        array_push($words, '...');
                        $text = implode(' ', $words);
                }
        }
        return $text;
}
function blog_excerpt($text) {
        global $post;
        if ( '' == $text ) {
                $text = get_the_content('');
                $text = apply_filters('the_content', $text);
                $text = str_replace('\]\]\>', ']]&gt;', $text);
                $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
                $text = strip_tags($text, '<p><br>');
                $excerpt_length = 250;
                $words = explode(' ', $text, $excerpt_length + 1);
                if (count($words)> $excerpt_length) {
                        array_pop($words);
                        array_push($words, '...');
                        $text = implode(' ', $words);
                }
        }
        return $text;
}

//remove_filter('get_the_excerpt', 'wp_trim_excerpt');
//add_filter('get_the_excerpt', 'improved_trim_excerpt');

//add additional fields to profile page in the dashboard
function fb_add_custom_user_profile_fields( $user ) {
?>
	<table class="form-table">
		<tr>
			<th>
				<label for="address"><?php _e('From', 'your_textdomain'); ?>
			</label></th>
			<td>
				<input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter where you are from', 'your_textdomain'); ?></span>
			</td>
		</tr>
                <tr>
                    <th>
                        <label for="profile_picture"><?php _e('Profile Picture', 'your_textdomain'); ?></label>
                    </th>
                    <td>
                        <input type="file" id="profile_picture" name="profile_picture">
                        <span class="description"><?php _e('Please select a picture of you', 'your_textdomain'); ?></span>
                    </td>
                </tr>
                <?php $img = wp_get_attachment_image_src(get_the_author_meta('profile_picture', $user->ID), array(294, 9999)); 
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
function fb_save_custom_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	update_usermeta( $user_id, 'address', $_POST['address'] );
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
add_action( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );
//Pagination
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1; 
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}

//notification
function my_admin_notice() {
	$user = wp_get_current_user();
	$id = $user->ID;
	$image = get_user_meta($id, 'profile_picture', true);
    if($image == ''){
  echo "
	<div id='my_admin_notice' class='updated fade'><p><strong>".__('Please update your profile picture and description...')."</strong> ".sprintf(__('<a href="http://www.byuicomm.net/wp-admin/profile.php">Click here </a> <a href="http://www.byuicomm.net/author/kmardmore/" target="_blank">- See an example</a>'))."</p></div>
	   ";
	   }
  }
add_action('admin_notices', 'my_admin_notice');

//tag cloud configuration 
function custom_tag_cloud_widget($tagargs) {
	$tagargs['number'] = 25; //number tag
	return $tagargs;
}
add_filter( 'widget_tag_cloud_args', 'custom_tag_cloud_widget' );

?>
<?php
//Login fail redirect filter
add_action( 'wp_login_failed', 'my_front_end_login_fail' ); // hook failed login
function my_front_end_login_fail( $username ) {
$referrer = $_SERVER['HTTP_REFERER']; // where did the post submission come from?
// if there’s a valid referrer, and it’s not the default log-in screen
if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
    //remove previous login failures from URL
        $referrer = str_replace('?login=failed', '', $referrer);
        $referrer = str_replace('&login=failed', '', $referrer);
        $referrer = str_replace('?register=failed', '', $referrer);
        $referrer = str_replace('&register=failed', '', $referrer);
        //use '?' if first GET param or '&' otherwise
        if(strstr($referrer, '/?')){
            $char = '&';
        }else{
            $char = '?';
        }
        
        
	wp_redirect( $referrer . $char . 'login=failed' ); // let’s append some information (login=failed) to the URL for the theme to use ?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
    	alert('error login in');
	});
    </script>
<?php exit;
    }
}
//login success redirect
add_action('wp_login', 'login_success');

function login_success(){
	$referer = $_SERVER['HTTP_REFERER'];
	if (strstr($referer, 'wp-login') || strstr($referer, 'wp-register')){
		$referer = 'http://byuicomm.net/';	
	}
	//strip off GET params in the URL
	$params_position = strpos($referer, '?');
	if($params_position)
		wp_redirect(substr($referer, 0, $params_position -1));
	else 
		wp_redirect($referer);	
	exit;
}

?>
<?php 
add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php }?>
<?php
/******************************************************
    Adding extra registration fields
********************************************************/
// This function shows the form field on registration page
add_action('register_form','show_first_name_field');

// This is a check to see if you want to make a field required
add_action('register_post','check_fields',10,3);

// This inserts the data
add_action('user_register', 'register_extra_fields');


// this adds the form fields to the registration form
function show_first_name_field(){
?>
<p>
<label>First Name<br />
<input id="first_name" class="input" type="text" tabindex="20" size="25" value="<?php echo $_POST['first']; ?>" name="first"/>
</label>
</p>

<p>
<label>Last Name<br />
<input id="last_name" class="input" type="text" tabindex="21" size="25" value="<?php echo $_POST['last']; ?>" name="last"/>
</label>
</p>

<p id="emailtwo">
<label>Email 2<br />
<input id="emailtwo" class="input" type="text" tabindex="22" size="25" value="<?php echo $_POST['emailtwo']; ?>" name="emailtwo"/>
</label>
</p>

<style>
    #emailtwo{
        display: none;
    }
</style>
<?php
}

// This function checks to see if they didn't enter them
// If no first name or last name display Error
function check_fields($login, $email, $errors) {
	global $firstname, $lastname;
	if ($_POST['first'] == '') {
		$errors->add('nofirstname', "<strong>ERROR</strong>: Please enter your first name");
	} else {
		$firstname = $_POST['first'];
	}
	if ($_POST['last'] == '') {
		$errors->add('nolastname', "<strong>ERROR</strong>: Please enter your last name");
	} else {
		$lastname = $_POST['last'];
	}
	if ($_POST['emailtwo'] != ''){
		$errors->add('bot', "<strong>ERROR</strong>: You are a bot");
	}
        if($errors->get_error_code() != ""){ // if there are errors
            $referrer = $_SERVER['HTTP_REFERER']; // where did the post submission come from?
// if there’s a valid referrer, and it’s not the default log-in screen
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
    //remove previous registration failures from URL
        $referrer = str_replace('?register=failed', '', $referrer);
        $referrer = str_replace('&register=failed', '', $referrer);
        $referrer = str_replace('?login=failed', '', $referrer);
        $referrer = str_replace('&login=failed', '', $referrer);
        //use '?' if first GET param or '&' otherwise
        if(strstr($referrer, '/?')){
            $char = '&';
        }else{
            $char = '?';
        }
	wp_redirect( $referrer . $char . 'register=failed' );
        }
//        die('errors');
//        else die('no errors');
    }
	

}
// This is where the first and last name are stored in the end
function register_extra_fields($user_id, $password="", $meta=array())  {

// Gotta put all the info into an array
$userdata = array();
$userdata['ID'] = $user_id;

// First name
$userdata['first_name'] = $_POST['first'];

// Last Name
$userdata['last_name'] = $_POST['last'];

// Enters into DB
wp_update_user($userdata);

// This is for custom meta data "gender" is the custom key and M is the value
// update_usermeta($user_id, 'gender','M');

}
//this function detects if the user is using ie, for outputting IE specific code.
function using_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return true;
    else
        return false;
}

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
/*******************
This hides the pointers that get in the way and can't be closed
*******************/

add_filter('admin_head','disable_pointers');
function disable_pointers(){
	?>
    <style type='text/css'>
		.wp-pointer{
			visibility: hidden !important;	
		}
	</style>
    <?php	
}

?>

