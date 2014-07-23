<?php
/**
 * This is the category page. Based off of the homepage layout.
 * _______ are changed from the homepage
 * 
 * @author
 */


 get_header(); ?>

<?php
  
  //Get the category ID
  $cat_id = get_query_var('cat');
  //Use the category ID to get the category object
  $cat_object = get_category($cat_id);
  //Get the name attribute of the category object
  $cat_name = $cat_object->name;
  //Get the slug attribute of the category object
  $cat_slug = $cat_object->slug;

?>

  
      <!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
      <?php include_once "social-menu.php"; ?>
      
<div class="container container-narrow marketing mobile-margin">
  <!-- main content -->
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature"><?php echo ucwords($cat_name); ?> Top Stories</h2>      
    </div>
  </div>

  <div class="row-fluid">
      <!-- Top Stories  3 Across-->
    <div class="span9">
      <div class="row-fluid visible-desktop">
        <?php
          $args = array(
                  'cat' => $cat_id,
                  'post_type' => 'any',
                  'posts_per_page' => 3,
                  'order' => 'DESC',
                  'orderby' => 'meta_value_num',
                  'meta_key' => '_weekly_count',
                  'post_status' => 'publish'
          );
          $query = new WP_Query($args);
          if( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
            $url = $thumb['0'];
            ?>
                        <div class="span4">
                          <a class="thumbnail-wrapper" href="<?php echo get_permalink(); ?>" title="<?php echo the_title();?>">
                            <img src="<?php echo $url; ?>" alt="">
                            <div class="description">
                                <p class='description-content'> <?php the_title(); ?></p>
                            </div>
                          </a>
                        </div>
                  <?php
                    endwhile; endif; 
                  ?>
        </div>

      <hr>
      <!-- Latest stories, Endless content, one story per line -->
      <div class="feature">

        <h2 class="feature-lead">Latest Stories</h2>
          <?php
            if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
            elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
            elseif ( isset($_GET['paged']) ) { $paged = $_GET['paged']; }
            else { $paged = 1; }
            $args = array(
              'posts_per_page' => 10, 
              'paged' => $paged,
              'cat' => $cat_id 
              );

            $query = new WP_Query($args);
                      
            if( $query->have_posts() ) {
              $i = 0;
              while ( $query->have_posts() ) {
                // add a Site Ad after the 3rd post
                if ($i == 3){
                  // Display our ad placeholders only to categories different than these
                  if($cat_slug != "opinion") {
                    ad_control("among_posts", ""); //$cat_slug
                  }
                    // Display Google Ads to the rest (by simply not passing any argument for the second parameter) 
                  else {
                    ad_control("among_posts", "");
                  }
                } else {
                  $query->the_post();
                  // this way it can be included anywhere 
                  include 'includes/latest-stories.php';
                }
                $i++;
              }
            }
          ?>
          <div class="pull-left"><?php previous_posts_link(); ?></div>
          <div class="pull-right"><?php next_posts_link(); ?></div>
      </div>
    </div>
    <!-- Sidebar -->
    <div class="span3 visible-desktop">
      <?php 
        // Display our ad placeholders only to categories different than these
        if($cat_slug != "opinion") {
          ad_control("sidebar", ""); //$cat_slug
        }
        // Display Google Ads to the rest (by simply not passing any argument for the second parameter) 
        else {
          ad_control("sidebar", "");
        }
      ?>
      <?php dynamic_sidebar('front-page'); ?>
    </div>
    
  </div>
</div>
<?php get_footer();?>
