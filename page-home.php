<?php
/**
 * Template Name: Home-Page + Carousel
 *
 * @author
 * @package WP-Bootstrap
 * @since WP-Bootstrap 0.5
 *
 */


 get_header(); ?>
 <!-- Begin Carousel -->

<div id="myCarousel" class="carousel slide visible-desktop">
 <div class="carousel-inner">

    <?php query_posts('post_type=post') ?>

    <?php
      $args = array('tag'=>'featured',
              'post_count'=>5);
      $query = new WP_Query($args);
      $i = 0;
      if( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        $i++;
    ?>
  
  <div class="item<?php if($i == 1) echo " active"; ?>">
    <?php
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
      $url = $thumb['0'];
    ?>
    <img src="<?php echo $url; ?>" alt="Text_2" />
    <div class="carousel-caption">
      <h4><?php the_title(); ?></h4>
      <p><?php the_excerpt(); ?></p>
      <a class="btn btn-primary" href="<?php the_permalink(); ?>">Read More</a>
    </div>
  </div>
  <?php endwhile; endif; ?>

  </div>
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
</div>

<!-- End Carousel -->

<!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
 <?php include_once "social-menu.php"; ?>

<div class="container container-narrow marketing mobile-body mobile-margin">
  <!-- main content -->
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature-lead pull-left visible-desktop">Top Stories</h2>
    </div>
    
  </div>


  <div class="row-fluid">

      <!-- Top Stories  3 Across-->
    <div class="span9">

      <div class="row-fluid visible-desktop">
      </div>

      <div class="row-fluid visible-desktop">
        <?php
          $args = array(
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
            function filter_where($where = '') {
              //posts in the last 30 days
              $where .= " AND post_date > '" . date('Y-m-d', strtotime('-30 days')) . "'";
              return $where;
            }
            add_filter('posts_where', 'filter_where');
            $args = array(
                'posts_per_page' => 10,
                'orderby' => 'date',
                'order' => 'DESC'
              );
            $query = new WP_Query($args);
            remove_filter('posts_where', 'filter_where');
            if( $query->have_posts() ) {
              $i = 0;
              while ( $query->have_posts() ) {
                // add a Site Ad after the 3rd post
                if ($i == 3){
                  ad_control("among_posts", ""); //home
                  // include 'includes/postGoogleAd.php';
                } else {
                  $query->the_post();
                  // this way it can be included anywhere 
                  include 'includes/latest-stories.php';
                }
                $i++;              
              } 
            } 
            wp_reset_query(); //just in case
          ?>

          <!-- I am commenting pagination out for now, until I have time to fix it. If you want to work on it, please do! - Isaac Andrade -->
          <!-- <div class="pull-left"><?php previous_posts_link(); ?></div>
          <div class="pull-right"><?php next_posts_link(); ?></div> -->

      </div>
    </div>
    <!-- Sidebar -->
    <div class="span3 visible-desktop videos">

      <!-- Site Ads -->
        <?php ad_control("sidebar", ""); //home?>
        
      <?php dynamic_sidebar('front-page'); ?>
    </div>
    
  </div>
</div>
<?php get_footer();?>
