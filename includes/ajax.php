<?php
function jpb_template_redirect(){
  if(is_page(array('scroll','campus','entertainment','sports','opinion','lifestyle','news','spanish','special','digital','live','photography')) ){
    wp_enqueue_script( 'random-loop-base','/wp-content/themes/icomm/js/ajax-scroll.js', array( 'jquery' ), '1.0' );
  }
  elseif(is_page('blogs') ){
     wp_enqueue_script( 'random-loop','/wp-content/themes/icomm/js/ajax-blog.js', array( 'jquery' ), '1.0' );
  }
  elseif(is_page('about') ){
    wp_enqueue_script( 'random-loop','/wp-content/themes/icomm/js/ajax-agency-members.js', array( 'jquery' ), '1.0' );
  }
  elseif(is_page('ldp') ){
    wp_enqueue_script( 'random-loop','/wp-content/themes/icomm/js/ajax-ldp.js', array( 'jquery' ), '1.0' );
  }
  elseif(is_page('pathway') ){
    wp_enqueue_script( 'random-loop','/wp-content/themes/icomm/js/ajax-pathway.js', array( 'jquery' ), '1.0' );
  }
  elseif(is_page('videos') ){
    wp_enqueue_script( 'random-loop','/wp-content/themes/icomm/js/ajax-videos.js', array( 'jquery' ), '1.0' );
  }
}

/* ------------------------------------------- */
/* ----------- Scroll pages AJAX Calls ------- */
/* ------------------------------------------- */
function loop_scroll_cb() {
	$ajax_paged = intval( $_POST['ajax_paged'] );
	$category = $_POST['category'];
	$popular = $_POST['popular'];
	$args = array('orderby' => 'ASC', 
		'posts_per_page' => 21, 
		'post_status' => 'publish', 
		'paged' => $ajax_paged);
	if($category != 'all'){
		$args['category_name'] = $category;
	}
	//elseif($category == 'all' && $ajax_paged == 1){
		//$args['posts_per_page'] = 20; // this makes room for the poll bars
	//}
	if($popular == 'true'){
		$args['order'] = 'DESC';
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = '_weekly_count';
	}
	$random_query = new wp_query($args);
	$total_pages = $random_query->max_num_pages;
	
  	query_posts($args);
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$category = get_the_category();
		$image_id = get_post_thumbnail_id();  
   		$image_url = wp_get_attachment_image_src($image_id,'medium');  
		if ($image_url == NULL) $image_url = 'http://www.byuicomm.net/wp-content/themes/icomm/images/default.jpg'; else $image_url = $image_url[0];
		include (TEMPLATEPATH . '/includes/ajax/random.php');
	endwhile;
	endif;
	if($ajax_paged == 1 && $total_pages > 1){
            echo "<script>buildAdd = true</script>";
        }
	if ($ajax_paged == $total_pages && $total_pages != 1) {
		 echo "<script>remove_add();</script>";
	}
	
	die();
}

function loop_members_cb() {
	include (TEMPLATEPATH . '/includes/ajax/ajax_agency_members.php');
	die();
}
/* ------------------------------------------- */
/* ----------- Pathway Page AJAX Calls ----------- */
/* ------------------------------------------- */
function loop_pathway_cb() {
	$ajax_paged = intval( $_POST['ajax_paged'] );
	$category = $_POST['category'];
	$args = array('orderby' => 'ASC', 
		'posts_per_page' => 18, 
        'post_type' => 'pathwaypost',
		'post_status' => 'publish', 
		'paged' => $ajax_paged);
	if($category != 'all'){
            $args['pathwayloc'] = $category;
	}
	$random_query = new wp_query($args);
	$total_pages = $random_query->max_num_pages;
	
  	query_posts($args);
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$category = get_the_category();
		$image_id = get_post_thumbnail_id();  
   		$image_url = wp_get_attachment_image_src($image_id,'medium');  
		if ($image_url == NULL) $image_url = 'http://www.byuicomm.net/wp-content/themes/icomm/images/default.jpg'; else $image_url = $image_url[0];
		include (TEMPLATEPATH . '/includes/ajax/ajax_pathway.php');
	endwhile;
	endif;
	if($ajax_paged == 1 && $total_pages > 1){
            echo "<script>buildAdd = true</script>";
        }
	if ($ajax_paged == $total_pages && $total_pages != 1) {
		 echo "<script>remove_add();</script>";
	}
	
	die();
}

/* ------------------------------------------- */
/* ----------- LDP Page AJAX Calls ----------- */
/* ------------------------------------------- */
function loop_ldp_cb() {
	$ajax_paged = intval( $_POST['ajax_paged'] );
	$category = $_POST['category'];
	$args = array('orderby' => 'ASC', 
		'posts_per_page' => 18, 
        'post_type' => 'ldpshow',
		'post_status' => 'publish', 
		'paged' => $ajax_paged);
	if($category != 'all'){
            $args['ldpseason'] = $category;
	}
	$random_query = new wp_query($args);
	$total_pages = $random_query->max_num_pages;
	
  	query_posts($args);
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$category = get_the_category();
		$image_id = get_post_thumbnail_id();  
   		$image_url = wp_get_attachment_image_src($image_id,'medium');  
		if ($image_url == NULL) $image_url = 'http://www.byuicomm.net/wp-content/themes/icomm/images/default.jpg'; else $image_url = $image_url[0];
		include (TEMPLATEPATH . '/includes/ajax/ajax_ldp.php');
	endwhile;
	endif;
	if($ajax_paged == 1 && $total_pages > 1){
            echo "<script>buildAdd = true</script>";
        }
	if ($ajax_paged == $total_pages && $total_pages != 1) {
		 echo "<script>remove_add();</script>";
	}
	
	die();
}

/* ------------------------------------------- */
/* ---------- Video Page AJAX Calls ---------- */
/* ------------------------------------------- */
function loop_videos_cb() {
	$ajax_paged = intval( $_POST['ajax_paged'] );
	$category = $_POST['category'];
	$args = array('orderby' => 'ASC', 
		'posts_per_page' => 21, 
		'post_status' => 'publish', 
		'paged' => $ajax_paged, 
		'meta_key' => '_my_meta', 
		'meta_value' => NULL, 
		'meta_compare' => '!=');
	if($category != 'video' && $category != 'popular'){
		$args['category_name'] = $category;
	} else if($category == 'popular'){
		$args = array(
			'posts_per_page' => 21,
			'post_status' => 'publish',
			'paged' => $ajax_paged,
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'meta_key' => '_weekly_count',
			'meta_query' => array( array(
					'key' => '_my_meta',
					'value' => NULL,
					'meta_compare' => '!='
				))
			);
	}
	$random_query = new wp_query($args);
	$total_pages = $random_query->max_num_pages;
	
  	query_posts($args);
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$category = get_the_category();
		$image_id = get_post_thumbnail_id();  
   		$image_url = wp_get_attachment_image_src($image_id,'medium');  
		if ($image_url == NULL) : $image_url = 'http://www.byuicomm.net/wp-content/themes/icomm/images/default.jpg'; else : $image_url = $image_url[0]; endif;
		global $post;
		$meta = get_post_meta($post->ID,'_my_meta',TRUE);
		$video = $meta['videoid'];
		include (TEMPLATEPATH . '/includes/ajax/ajax_videos.php');
		
	endwhile;
	else : echo 'Sorry, there are no posts in this category';
	endif;
	if($ajax_paged == 1 && $total_pages > 1){
            echo "<script>buildAdd = true</script>";
        }
	if ($ajax_paged == $total_pages && $total_pages != 1) {
		 echo "<script>remove_add();</script>";
	}
	
	die();
}

function loop_blog_cb(){
    $ajax_paged = intval($_POST['ajax_paged']);
    
    $args = array('posts_per_page'=>10, 'post_type'=>'blogs', 'paged' => $ajax_paged, 'post_status' => array('publish')); 
    $blog_query = new wp_query($args);
    
    // fill the template with details
    $count = 1;
    query_posts($args);
    if ( have_posts() ) : while ( have_posts() ) : the_post();
		include (TEMPLATEPATH . '/includes/ajax/blogtile.php');
                $count++;
	endwhile;
        endif;
        
        // reset the counter variable if it's on the last page
    if($ajax_paged == $blog_query->max_num_pages){
        echo "<script>setPageCounter(1);</script>";
    }    
    
    die();
}

function unattached_images(){
    echo "<div id='top-wrapper'><p>Please save your post when you are done selecting images to attach them.</p><button class='button' id='close-attach-images'>Close</button></div>";
    $args = array('post_parent' => 0,
                    'post_status' => 'inherit',
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'order' => 'DESC',
                    'orderby' => 'date');
    $attachments = get_children($args);
    foreach($attachments as $attachment){ ?>
        <div class='unattached-image-wrap' id="<?php echo $attachment->ID ?>">
        <?php echo wp_get_attachment_image( $attachment->ID, array(150,150)); ?>
        <button class='attach button'>Attach</button>
        </div>
        <?php
		}
}

function ajax_poll(){
    if (function_exists('vote_poll') && !in_pollarchive()){ 
            get_poll();
	};
    die();
}

/* ------------------------------------------- */
/* ---------- Popular Widget        ---------- */
/* ------------------------------------------- */
function popular_widget(){
	$tab = $_POST['tab'];
	$args = array();
	$meta_type = '';
	switch($tab){
		case 'weekly':
			$args = array(
				'post_type' => 'any',
				'posts_per_page' => 5,
				'order' => 'DESC',
				'orderby' => 'meta_value_num',
				'meta_key' => '_weekly_count',
				'post_status' => 'publish'
			);
			$meta_type = '_weekly_count';
			break;
		case 'all-time':
			$args = array(
				'post_type' => 'any',
				'posts_per_page' => 5,
				'order' => 'DESC',
				'orderby' => 'meta_value_num',
				'meta_key' => '_simple_count',
				'post_status' => 'publish' 
			);
			$meta_type = '_simple_count';
			break;
		default: //should be recent only
			$args = array(
				'post_type' =>'any',
				'posts_per_page' => 5,
				'post_status' => 'publish'
			);
			$meta_type = '_simple_count';
			break;
	}
	$query = new WP_Query($args);
	?>
	<ul id="popular-list">
	<?php 
		while($query->have_posts()): $query->the_post();
		$view_count = get_post_meta(get_the_ID(),$meta_type,true);
		if($view_count == '')
			$view_count = 0;
	?>
		<li>
			<a href="<?php the_permalink();?>" title="See the full post">
				<p class="popular-title"><?php the_title();?></p>
				<p class="views"><span class="num-views"><?php echo $view_count; ?></span> views - <?php echo get_the_date( 'F j, Y'); ?></p>
                <div class="percent-bar"></div>
			</a>
		</li>
	<?php endwhile; ?>
	</ul>
<?php 
		die();
	}


function new_guest(){
    print_r($_POST);
/* config start */
    
$emailAddress = 'rykerblunck@gmail.com';
/* config end */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/icomm/includes/contact/phpmailer/class.phpmailer.php';

//session_name("fancyform");
//session_start();

foreach($_POST as $k=>$v)
{
	if(ini_get('magic_quotes_gpc'))
	$_POST[$k]=stripslashes($_POST[$k]);
	
	$_POST[$k]=htmlspecialchars(strip_tags($_POST[$k]));
}


//$err = array();
//
//if(!checkLen('name'))
//	$err[]='The name field is too short or empty!';
//
//if(!checkLen('email'))
//	$err[]='The email field is too short or empty!';
//else if(!checkEmail($_POST['email']))
//	$err[]='Your email is not valid!';
//
//if(!checkLen('subject'))
//	$err[]='You have not selected a subject!';
//
//if(!checkLen('message'))
//	$err[]='The message field is too short or empty!';
//
//if((int)$_POST['captcha'] != $_SESSION['expected']){
//	$err[]='The captcha code is wrong!';
//}
//
//if(count($err))
//{
//	if($_POST['ajax'])
//	{
//		echo '-1';
//	}
//
//	else if($_SERVER['HTTP_REFERER'])
//	{
//		$_SESSION['errStr'] = implode('<br />',$err);
//		$_SESSION['post']=$_POST;
//		
//		header('Location: '.$_SERVER['HTTP_REFERER']);
//	}
//
//	exit;
//}




$msg=
$_POST['name'].',<br />
Your reservation has successfully been placed for the following events:<br /><br />
'.$eventsList.'<br /><br /><br />
We look forward to seeing you,<br />

I~Comm Student Media Staff and Alumni Association
';


$mail = new PHPMailer();
$mail->IsMail();

$mail->AddAddress($emailAddress);
$mail->SetFrom('byui.icomm@gmail.com');
$mail->Subject = "Reservation Confirmation | Scroll / I~Comm Student Media Reunion";

$mail->MsgHTML($msg);

//$mail->Send();

$insertSuccess = writeGuest($_POST['name'], $_POST['staffYears'], $_POST['email'], $_POST['phone'], $_POST['twitterName'], $_POST['address1'], $_POST['address2']
                        , $_POST['city'], $_POST['state'], $_POST['zipcode'], $_POST['spouseName'], $_POST['children'], $_POST['numberChildren'], $_POST['reception'], $_POST['tours']
                        , $_POST['dinner'], $_POST['universityEvents']);
echo $insertSuccess;

unset($_SESSION['post']);

//if($_POST['ajax'])
//{
//        
//	echo '1';
//}
//else
//{
//	$_SESSION['sent']=1;
//	
//	if($_SERVER['HTTP_REFERER'])
//		header('Location: '.$_SERVER['HTTP_REFERER']);
//	
//	exit;
//}

}
function writeGuest($name, $staffYears, $email, $phone, $twitterName, $address1, $address2, $city, $state
                    , $zipcode, $spouseName, $children, $numberChildren, $reception, $tours, $dinner, $universityEvents){
    
                        require $_SERVER['DOCUMENT_ROOT'].'/byuicomm.net/wp-blog-header.php';
                        return x;
                        //print_r($wpdb);
//    return $wpdb->query($wpdb->prepare(
//            "
//                INSERT INTO $wpdb->icom_rsvp_guests
//                (name)
//                VALUES(%s)
//            ",
//            $name
//            ));
}

add_action( 'template_redirect', 'jpb_template_redirect' );

add_action( 'wp_ajax_loop_scroll', 'loop_scroll_cb' );
add_action( 'wp_ajax_nopriv_loop_scroll', 'loop_scroll_cb' );

add_action('wp_ajax_loop_blog', 'loop_blog_cb');
add_action('wp_ajax_nopriv_loop_blog', 'loop_blog_cb');

add_action('wp_ajax_new_guest', 'new_guest');
add_action('wp_ajax_nopriv_new_guest', 'new_guest');

add_action( 'wp_ajax_loop_popular', 'loop_popular_cb' );
add_action( 'wp_ajax_nopriv_loop_popular', 'loop_popular_cb' );

// Agency 

add_action( 'wp_ajax_loop_portfolio', 'loop_portfolio_cb' );
add_action( 'wp_ajax_nopriv_loop_portfolio', 'loop_portfolio_cb' );

add_action( 'wp_ajax_loop_members', 'loop_members_cb' );
add_action( 'wp_ajax_nopriv_loop_members', 'loop_members_cb' );

//Pathway

add_action( 'wp_ajax_loop_pathway', 'loop_pathway_cb' );
add_action( 'wp_ajax_nopriv_loop_pathway', 'loop_pathway_cb' );

//Latter day Profiles

add_action( 'wp_ajax_loop_ldp', 'loop_ldp_cb' );
add_action( 'wp_ajax_nopriv_loop_ldp', 'loop_ldp_cb' );

add_action( 'wp_ajax_loop_ldp_popular', 'loop_ldp_popular_cb' );
add_action( 'wp_ajax_nopriv_loop_ldp_popular', 'loop_ldp_popular_cb' );

// Videos

add_action( 'wp_ajax_loop_videos', 'loop_videos_cb' );
add_action( 'wp_ajax_nopriv_loop_videos', 'loop_videos_cb' );

add_action( 'wp_ajax_loop_videos_popular', 'loop_videos_popular_cb' );
add_action( 'wp_ajax_nopriv_loop_videos_popular', 'loop_videos_popular_cb' );

add_action('wp_ajax_ajax_poll', 'ajax_poll');
add_action('wp_ajax_nopriv_ajax_poll', 'ajax_poll');

//image metabox

add_action( 'wp_ajax_unattached_images', 'unattached_images' );
add_action( 'wp_ajax_nopriv_unattached_images', 'unattached_images' );

//popular posts widget

add_action('wp_ajax_popular_widget', 'popular_widget');
add_action('wp_ajax_nopriv_popular_widget', 'popular_widget');
?>