<?php 
	add_action('wp_footer', 'simple_visit_counter');
	
	/**************Counts every visit to a post****************/
	function simple_visit_counter($is_special = false){
		if((is_single() && !is_page()) || $is_special){
			global $post;
			$old_value = get_post_meta($post->ID, '_simple_count', true);
			$new_value;
			if($old_value == '')
				$new_value = 1;
			else $new_value = 1 + intval($old_value);
			update_post_meta($post->ID, '_simple_count', $new_value);
			weekly_visit_counter();
		}
	}
	/**************Counts every visit, clears after a week**********/
	function weekly_visit_counter(){
		global $post;
		global $wpdb;
		//query to add a _visit_time meta
		$wpdb->query(//using a custom query so I can access the now() function of mysql
			$wpdb->prepare(
				"
				INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value)
				VALUES ('%d', '_visit_time', now())
				",
				$post->ID
			)
		);
		//set the _weekly_count meta equal to the count() of _visit_times
		$count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*)
				FROM $wpdb->postmeta
				WHERE post_id = %d
				AND meta_key = '_visit_time'
				AND meta_value > NOW() - INTERVAL 1 WEEK"
			, $post->ID)
		);
		
		update_post_meta($post->ID, '_weekly_count', $count);
				
	}
	
	/******************Display the popular posts Widget*****************/
	function popular_posts_content($args) {
		?>
        <div id="popular-posts">
		<h1>Popular Posts</h1>
			<ul id="tabs">
				<li id="weekly" class="selected">This Week</li>
				<li id="all-time">All Time</li>
				<li id="recent">Recent</li>
			</ul>
			<img src="<?php bloginfo('template_directory') ?>/images/blue-load.gif" alt="Loading..." id="popular-load">
			<div id="exchangeble"><!--Content is loaded dynamically here --></div>
		</div>	
		<script type="text/javascript">
		
			function popularQueries(tab){
				jQuery('#exchangeble').empty();
				jQuery('#popular-load').css('visibility','visible');
				jQuery.ajax({
					url: ajaxurl,
					type: "POST",
					data: {
						action: 'popular_widget',
						tab: tab
					}
				}).done(function(data){
					jQuery('#popular-load').css('visibility','hidden');
					if(data != 0){
						jQuery('#exchangeble').html(data);
						percentBars();
					}
					else jQuery('#exchangeble').html("Loading Failed");
				});
			}
			
			function percentBars(){
				var spanArray = jQuery("span.num-views");
				var numArray = new Array();
				var total = 0;
				for(i = 0; i < spanArray.length; i++){
					numArray[i] = parseInt(jQuery(spanArray[i]).html());
					total += numArray[i];
				}
				for(i = 0; i < spanArray.length; i++){
					var percent = numArray[i] / total;
					var roundedUp = parseInt(percent * 100);
					jQuery(spanArray[i]).parents('li').find('.percent-bar').css('width', roundedUp + '%');
				}
			}
		
			jQuery(document).ready(function(){
				popularQueries('weekly');
			
				jQuery("#tabs li").click(function(){
					jQuery('#tabs .selected').removeClass('selected');
					jQuery(this).addClass('selected');
					popularQueries(jQuery(this).attr('id'));
				});
			});
				
			
		</script>
        <?php
		
		
	}//end function

	wp_register_sidebar_widget(
		'icom_popular',        // your unique widget id
		'Popular Posts',          // widget name
		'popular_posts_content',  // callback function
		array(                  // options
			'description' => 'Display popular posts for this week, all-time, and the latest posts'
		)
	);
?>