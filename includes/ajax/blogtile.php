<?php $image = get_the_post_thumbnail($post->ID, 'blog-tile-thumb'); ?>

<a href="<?php echo the_permalink();?>"><li class='blog-tile<?php if($image != NULL) echo " tile-with-image"?>'> 
    <?php 
          
          if($image != NULL){ ?>
              <? the_post_thumbnail( 'blog-tile-thumb', array('title' => get_the_title())); ?>
          <? } ?>
    <h3><?php echo the_title(); ?></h3>
    
    <?php if($image == NULL){ 
        $excerpt = get_the_excerpt();
        $excerpt = substr($excerpt, 0, 135);
        $excerpt .= '...';
        echo $excerpt;
    } ?>
    
</li></a>