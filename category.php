<?php
/**
 * This is the category page. Based off of the homepage layout.
 * _______ are changed from the homepage
 * 
 */


 get_header(); ?>

<?php
  
  $current_cat = get_query_var('cat');
  $current_cat_name = get_cat_name($current_cat);

?>

<div class="container container-narrow marketing">
  <!-- main content -->
  <div class="row-fluid vert-padding">
    <div class="span10">
      <h2 class="feature"><?php echo ucwords($current_cat_name); ?> Top Stories</h2>
    </div>
    <div class="span2 pull-right">
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
        <?php
          $args = array(
                  'cat' => $current_cat,
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
                'cat' => $current_cat,
                'posts_per_page' => 20
              );
            $query = new WP_Query($args);
            if( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
      
              include 'includes/latest-stories.php';

            endwhile; endif;
          ?>
      </div>
    </div>
    <!-- Sidebar -->
    <div class="span3 visible-desktop">
      <?php dynamic_sidebar('front-page'); ?>
    </div>
    
  </div>
</div>
<?php get_footer();?>
