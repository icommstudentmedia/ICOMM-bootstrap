<li class="agency-tile">
    <img src="<?php bloginfo('template_directory'); ?>/includes/timthumb.php?src=<?php echo $image_url ?>&h=150&w=300&zc=1" class="image" alt="<?php the_title(); ?>" />
    <a id="<?php the_ID() ?>" class="mask" href="<?php the_permalink() ?>">
        <h1 class="title"><?php the_title();?></h1>
    </a>
</li>