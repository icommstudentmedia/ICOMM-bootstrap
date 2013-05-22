<?php
/**
 * Template Name: Home-Page + Carousel
 *
 *
 * @package WP-Bootstrap
 * @since WP-Bootstrap 0.5
 *
 * Last Revised: July 16, 2012
 * I think that we are using rows wrong. I'm going to switch them to row-fluid and see what happens
 */


 get_header(); ?>
<!--
* This is the jumbotron as it appears on the main bootstrap page, I copied/pasted it in and added correct links to make sure I have the formatting right.
* From here we just implement the loop.
* For Clarification purposes, we want to grab the 5 most recent articles and their thumbnails for the carousel at the top right?
* -Shane
-->

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


<div class="container container-narrow marketing">
  <!-- main content -->
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature-lead pull-left">Top Stories</h2>
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
            $args = array(
                'posts_per_page' => 20
              );
            $query = new WP_Query($args);
            if( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
              // this way it can be included anywhere 
              include 'includes/latest-stories.php';

            endwhile; endif;
          ?>
      </div>
    </div>
    <!-- Sidebar -->
    <div class="span3 visible-desktop videos">
      <?php dynamic_sidebar('front-page'); ?>
    </div>
    
  </div>
</div>
<?php get_footer();?>
