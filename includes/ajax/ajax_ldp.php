<li class="boxgrid slidedown tile <?php $category = get_the_category(); if( $category[0]->cat_name == 'scroll digital') { echo 'digital'; } else { echo $category[0]->cat_name; } ?>">
    <div class="cover">	
		<div class="box-top">
		<a href="<?php the_permalink() ?>">
			<img src="<?php bloginfo('template_directory'); ?>/includes/timthumb.php?src=<?php echo $image_url ?>&h=150&w=300&zc=1" class="image" alt="<?php the_title(); ?>" />
		</a>
		</div>
		<div class="box-middle">
			<h4 class="box-category"><?php the_category("  ") ?></h4>
            <h3 class="title"><a href="<?php the_permalink() ?>"><?php the_title();?></a></h3>
        </div>
		<div class="box-bottom">
                        	
							<p><?php $text = strip_tags(get_the_excerpt());if(strlen($text) > 200) { $text = substr($text , 0, 200);}echo $text."...";?></p>
							
							<div class="meta">
								<div class="icon" hidden="hidden"><?php if( $category[0]->cat_name == 'scroll digital'|| $category[0]->cat_name == 'spanish') { echo '<span class="film-icon"></span>'; } else { echo '<span class="story-icon"></span>'; } ?></div>
								<div class="byline">
									<span>
									<span class="by">by</span>
									<a href="<?php echo get_author_posts_url(get_the_author_meta('ID'))?>"><?php the_author_meta( 'first_name'); ?> <?php the_author_meta('last_name'); ?></a>
									<span class="comments-counter" hidden="hidden"><?php comments_number( '0', '  1 ', ' % ' ); ?><em></em></span>
                </span>
            </div>
        </div>
		</div>					
   </div>

    </li>