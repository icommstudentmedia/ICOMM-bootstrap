<?php
	/* Template Name: Latter-Day Profiles */
	get_header();
?>

<div class="container container-narrow marketing">
  <!-- main content -->
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature-lead pull-left">Latter-Day Profiles</h2>
    </div>
    <div class="span2 pull-right visible-desktop">
      <div class="row-fluid social-pics">
        <div class="span4">
          <a href="https://www.facebook.com/icomm.student.media?fref=ts"><img src="<?php bloginfo( 'template_url' ); ?>/img/f_logo.png"></a>
        </div>
        <div class="span4">
          <a href="https://twitter.com/byuicomm"><img src="<?php bloginfo( 'template_url' ); ?>/img/twitter_logo.png"></a>
        </div>
        <div class="span4">
          <a href="http://pinterest.com/byuicomm/"><img src="<?php bloginfo( 'template_url' ); ?>/img/pinterest_logo.png"></a>
        </div>
      </div>
    </div>
  </div>
  <hr>
  <div class="row-fluid">
  	<div class="span9">
  		<!-- Brightcove Video goes here -->
  		<div id="display">
  			<!-- Start of Brightcove Player, on page load it plays a playlist of the most recent videos  -->
  			<div style="display:none">
  			</div>

  			<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>
			<!--<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/api/SmartPlayerAPI.js"></script>-->
			<div id="video">
			    <object id="myExperience" class="BrightcoveExperience">
			      <param name="bgcolor" value="#FFFFFF" />
			      <param name="width" value="640" />
			      <param name="height" value="360" />
			      <param name="playerID" value="1712672607001" />
			      <param name="playerKey" value="AQ~~,AAAAocwtmPk~,RTQzrMOt-UALXHtL2PlWeZOSgFGzOPMk" />
			      <param name="isVid" value="true" />
			      <param name="isUI" value="true" />
			      <param name="dynamicStreaming" value="true" />
			      <param name="includeAPI" value="true" /> 
			      <param name="templateLoadHandler" value="myTemplateLoaded" />
				  <param name="htmlFallback" value="true" />
			
			    </object>
			</div>

			<!-- 
				This script tag will cause the Brightcove Players defined above it to be created as soon
				as the line is read by the browser. If you wish to have the player instantiated only after
				the rest of the HTML is processed and the page load is complete, remove the line.
			-->
			<script type="text/javascript">brightcove.createExperiences();</script>
  		</div>
  	</div>

  	<div class="span3">
  		<div id="sidebar">
  			<h1></h1>
  			<div id="content"></div>
  			<div id="meta"></div>
  		</div>
  	</div>
  </div>
  <div class="row-fluid">
  	<div class="span12">
		<div id="seasons">
			<?php
		        $post_data = array(); //this will be filled with post data in the loop,
		        // given to javascript, and used to fill content when user selects a video
			/**************** THE LOOP ****************/
			//get all the categories
			$args = array(
				'type' => 'ldpshow',
				'orderby' => 'id',
				'order' => 'DESC',
				'hide_empty' => '1',
				'taxonomy' => 'ldpseason'
			);
			$seasons = get_categories($args);
			foreach($seasons as $season){
			//loop each category for posts
				
				$args = array(
					'post_type' => 'ldpshow',
					'posts_per_page' => -1,
					'orderby' => 'date',
					'order' => 'DESC',
					'ldpseason' => $season->name
				);
				$query = new WP_Query($args);
			?>
				<div class='season'>
				<div class='season-title'><h1><?php echo $season->name ?></h1></div>
				<ul class='episodes'>
				<?php 
				while($query->have_posts()) : $query->the_post();
				//fill the array to give to javascript later
		                $ldp_meta = get_post_meta($post->ID, '_my_meta_ldp', true);
		                $video_meta = get_post_meta($post->ID, '_my_meta', true);
		                
		                $post_data[$video_meta['videoid']] = array('title' => get_the_title(),
							'content' => get_the_content(),
							'date' => $ldp_meta['date'],
							'season' => $season->name,
							'link' => get_permalink()
							);
							
				// post content
				$image_id = get_post_thumbnail_id();  
		   		$image_url = wp_get_attachment_image_src($image_id,'thumbnail');
				if ($image_url == NULL) $image_url = 'http://www.byuicomm.net/wp-content/themes/icomm/images/default.jpg'; else $image_url = $image_url[0];
				?>		
				<?php if($query->current_post % 2 == 0){?>
		                    <div class="two-episodes">
		                <?php }?>
				<li class='episode'>
		                    <a href="<?php the_permalink() ?>" id="<?php echo $video_meta['videoid'] ?>">
		                        <img src="<?php bloginfo('template_directory') ?>/images/button_grey_play.png" class="play">
		                        <img src="<?php bloginfo('template_directory'); ?>/includes/timthumb.php?src=<?php echo $image_url ?>&h=77&w=115&zc=1" class="image" alt="<?php the_title(); ?>" />
		                        <p><?php the_title() ?></p>
		                    </a>
		                </li>
		                <?php if($query->current_post % 2 == 1 || $query->current_post + 1 == $query->post_count){ // end two episode wrapper ?>
		                    </div>
		                <?php } ?>
				<?php
				//echo get_the_term_list( $post->ID, 'ldpseason', '-', ' ', '-' );
				endwhile; // end current season
				?></ul></div><?php
			} //end all seasons
				?>
		</div>


  	</div>
  </div>
 <?php get_footer();
    echo "<script>var postArray = ".json_encode($post_data)."</script>";
?>