<?php
/**
 * This is the page for authors
 * 
 * @author
 */


 get_header(); 

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) :
	get_userdata(intval($author));
 ?>
    
    <!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
      <?php include_once "social-menu.php"; ?>
      
<div class="container-fluid mobile-margin">	
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature">Author</h2>
    </div>
  </div>

	<hr class="no-margin">
	<div class="row-fluid">
		<div class="span3 feature">
			<?php echo get_avatar( $curauth->ID, 128 ); ?>
			<div class="container">
				<h3><?php echo ucwords($curauth->first_name.' '.$curauth->last_name); ?></h3>
			</div>
			<h4 class='author-about section-title responsive-about-title'>ABOUT</h4>
			<hr class="no-margin">
			<p class="responsive-author"><?php echo $curauth->user_description; ?></p>
			<h4 class='author-about section-title responsive-about-title'>OTHER SITES</h4>
			<hr class="no-margin">
			<a href="<?php echo $curauth->user_url; ?>"<p><?php echo $curauth->user_url; ?></p></a>
			<h4 class='author-about section-title responsive-about-title'>DATE JOINED</h4>
			<hr class="no-margin">
			<p class="responsive-author"><?php echo date("n/j/Y", strtotime($curauth->user_registered)); ?></p>
			<h4 class='author-about section-title responsive-about-title'>POSTS</h4>
			<hr class="no-margin">
			<p class="responsive-author"><?php the_author_posts(); ?></p>



		</div>
		<?php rewind_posts() ?>
		<div class="span9 feature marketing">
			<h2>Posts by <?php echo ucwords($curauth->first_name.' '.$curauth->last_name); ?></h2>
	
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
	            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
	            //$url = $thumb[0];
	            $url = get_template_directory_uri().'/includes/timthumb.php?src='.$thumb[0].'&h=150&w=270&zc=1';
	            ?>
	            <div class="row-fluid">
	
	              <div class="span3">
	                <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>">
	                  <img class="pull-left" src="<?php echo $url; ?>" alt="">
					        </a>
	              </div>
	              <div class="span9">
	                <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>">
	                  <h3><?php the_title(); ?></h3>
	                </a>

	                    <div class="navbar author no-margin">
      					  <div class="navbar-inner no-margin">
      					    <ul class="nav">
      					      <li class="rightborder-dark left"><a href="<?php echo $author_link ?>"><?php the_author(); ?></a></li>
      					      <li><p>
      					        <?php echo (get_the_date() != "" ? "posted ".get_the_date() : "") ?>
      					      </p></li>
      					    </ul>
      					  </div>
      					</div>

	                    <p class="lead">
        					<?php echo get_the_excerpt(); ?>
      					</p>
	              </div>
	            </div>
	
	            <?php endwhile; else: ?>
	            	<p><?php _e('No posts by this author.' ); ?></p>
	
	            <?php endif; ?>
	
		</div>
	
	
	</div>
</div>	
<?php get_footer();?>