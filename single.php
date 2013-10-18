<?php 

/**
*   Template Name: Single
*   Date: 
*	@author 
*/


	get_header();

	if(have_posts() ) : while (have_posts() ) : the_post();

	/**** get the info about the post here, so it can be used throughout the page ****/
	// category name and ID
	$cats = get_the_category();
	//make sure we only get one category
	$cat_name = $cats[0]->cat_name;
	//capitalize first letter of each work
	$cat_name = ucwords($cat_name);
	//get the id
	$cat_id = $cats[0]->cat_ID;

	// author name, link and image
	$author_id = get_the_author_meta('ID');
	$author_name = get_the_author();
	$author_link = get_author_posts_url($author_id);
	$author_image = get_profile_picture($author_id,array(170,9999)); 

	$role = get_user_role($author_id);

	setPostViews(get_the_ID());

	// complete the class name for the buttons displayed below by getting the id of the category
	if($cat_id == 3) {
		$cat_class = 'campus';
	} elseif($cat_id == 1062) {
		$cat_class = 'scroll-digital';
	} elseif ($cat_id == 213) {
		$cat_class = 'news';
	} elseif ($cat_id == 1529) {
		$cat_class = 'special';
	} elseif ($cat_id == 306) {
		$cat_class = 'photography';
	} elseif ($cat_id == 4) {
		$cat_class = 'entertainment';
	} elseif ($cat_id == 5) {
		$cat_class = 'lifestyle';
	} elseif ($cat_id == 8) {
		$cat_class = 'sports';
	} elseif ($cat_id == 7) {
		$cat_class = 'opinion';
	} elseif ($cat_id == 1063) {
		$cat_class = 'spanish';
	}
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];
	if(!d.getElementById(id)){js=d.createElement(s);js.id=id;
		js.src="https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script>

<?php include_once "social-menu.php"; ?>

<div class="container container-narrow mobile-margin">

<div class="container-fluid">	
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature"><?php echo $cat_name; ?> Section</h2>
    </div> 

    <!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
      <?php include_once "social-menu.php"; ?>
      
  </div>

  <hr class="no-margin">
	<div class="row-fluid">
		<div class="span9">
			<div class="postinfo">
				<h2><?php the_title() ?></h2>
				<p>By <a href="<?php echo $author_link; ?>"><?php echo $author_name; ?>,</a> Scroll</p>
				<p><?php echo (get_the_date() != "" ? "posted ".get_the_date() : "") ?></p>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<!-- left sidebar -->
		<div class="span2 mobile-social-box">
			<!-- category -->
			<div class="postinfo-box postinfo-box-top postinfo-box-top-<?php echo $cat_class; ?> mobile-social-pos2">
				<a class="btn btn-<?php echo $cat_class; ?>" href="<?php echo get_category_link($cat_id)?>"><?php echo strtoupper($cat_name); ?></a>
			</div>
			<div class="postinfo-box mobile-social-pos1">
				<!-- author -->
				<a href="<?php echo $author_link; ?>" title="More from this author">
					<?php 
						echo $author_image;
						echo '<br />';
						echo strtoupper($author_name); 
					?>
				</a>
				<p class="role">
					<?php echo ucfirst($role); ?>
				</p>
				<p class="date">
					<?php echo get_the_date(); ?>
				</p>
				<p class="views">
					<?php echo getPostViews(get_the_ID()); ?>
				</p>
				<div class="row-fluid">
					<div class="span12 social-like">
						<div class="fb-like" data-send="false" data-layout="box_count" data-width="450" data-show-faces="true"></div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12 social-like">
						<div class="g-plusone" data-size="tall"></div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12 social-like">
						<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-count="vertical">Tweet</a>
					</div>
				</div>
			</div>
		</div>
	
		<!--the post -->
		<div id="post-content" class="span7">
			<?php //check for video, if none, show feat. image
				$meta = get_post_meta($post->ID,'_my_meta',true);
				$video_id = $meta['videoid'];
				if($video_id != ''){ ?>
					<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js">
					</script>
	            	<object id="myExperience" class="BrightcoveExperience">
						<param name="bgcolor" value="#FFFFFF" />
						<param name="width" value="100%" />
						<param name="height" value="360" />
						<param name="playerID" value="970265183001" />
						<param name="playerKey" value="AQ~~,AAAAocwtmPk~,RTQzrMOt-UDibiFyq2BFUUaXsdcirLOC" />
						<param name="videoID" value="<?php echo $video_id ?>" />
						<param name="isVid" value="true" />
						<param name="isUI" value="true" />
						<param name="dynamicStreaming" value="true" />
						<param name="htmlFallback" value="true" />
					</object>          
	                <script type="text/javascript">brightcove.createExperiences();</script>
				<?php } ?>
			<!-- the text of the post -->
			<?php the_content(); ?>
			<div class="post-nav">
				<?php previous_post_link('%link', '<< Previous Post ', FALSE); ?> -
				<?php next_post_link('%link', ' Next Post >>', FALSE); ?>
			</div>
			<hr>
			<!-- the comments -->
			<?php comments_template(); ?>
		</div>
	
		<!-- Right sidebar -->
		<div class="span3">
			<?php google_ad("sidebar"); ?>
			<?php dynamic_sidebar('front-page'); ?>
			<?php dynamic_sidebar('facebook'); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<?php 
	endwhile;
	endif;
	get_footer(); 
?>