<?php 
/********************************
 * Register the script
 *******************************/
function enqueue_ticker_script(){	
	wp_register_script('ticker', get_template_directory_uri() . '/js/ticker.js', array('jquery'));
	//improved scroll effects
	wp_register_script('jquery-ticker', get_template_directory_uri() . '/js/gistfile1.js', array('jquery'));
}
/**************************************
 * get the content, give it to js
 **************************************/
function query_ticker_post(){
	$query = new WP_Query(array('posts_per_page' => 1,
								'orderby' => 'date',
								'meta_key' => '_ticker_value',
								'meta_compare' => '!=',
								'meta_value' => '',
								'post_status' => 'publish'));
	if($query->have_posts()){
		wp_enqueue_script('ticker'); 
		wp_enqueue_script('jquery-ticker');
		
		while($query->have_posts()){
			$query->the_post();
			global $post;
			
			$href = get_permalink();
			$current_url = 'http://www.byuicomm.net'.$_SERVER['REQUEST_URI'];
			
			$tickerHTML = get_post_meta($post->ID, '_ticker_value', true);
			
			//don't display it if they are already on that page
			if($href != $current_url)
				echo "<script type='text/javascript'>jQuery(document).ready(function(){ appendTicker(".json_encode($tickerHTML).", ".json_encode($href).") });</script>";
		}
	}
	
	if(false){
			
	}
}
/**************************************
 * metabox functions
 **************************************/
function add_ticker_box(){
	add_meta_box('ticker-box','News Ticker','create_ticker_box', '', 'side');
}
function create_ticker_box($post){
	//see if it's already set for this post
	$ticker_value = get_post_meta($post->ID, '_ticker_value', true);
	$ticker_active = ($ticker_value == '' ? false : true);
	?>
		<label for='ticker_active'>Activate news ticker for this post: </label>
        <input name='ticker_active' type='checkbox'<?php if($ticker_active) echo "checked='true'"; ?>><br />
		<textarea name='ticker_text' style="width:260px;height:150px"><?php 
				if($ticker_active) 
					echo $ticker_value;
					else echo "Enter the text you want displayed here...";
			?>
		</textarea>
	
	<?
}
function save_ticker_box($post_id){
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	  return;
	if ( !current_user_can( 'edit_post', $post_id ) )
	  return;
	  
	//!!!strip tags here
	
	if(isset($_POST['ticker_active']) && isset($_POST['ticker_text'])){
		//first delete any previous tickers
		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->postmeta
						WHERE meta_key = '_ticker_value'");
		
		update_post_meta($post_id,'_ticker_value',$_POST['ticker_text']);
	}
	else{
		delete_post_meta($post_id,'_ticker_value');
	}
} 
 
/**************************************
 * hooks
 **************************************/


add_action('wp_enqueue_scripts', 'enqueue_ticker_script');

add_action('wp_footer', 'query_ticker_post');

if(current_user_can('edit_files')){
	// check user permission level
	add_action('add_meta_boxes','add_ticker_box');
	add_action('save_post','save_ticker_box');
 
}
?>