<ul>
    <?php while (have_posts()) : the_post(); ?>
                            
 			<li class="wide-tile <?php $category = get_the_category(); if( $category[0]->cat_name == 'scroll digital') { echo 'digital'; } else { echo $category[0]->cat_name; } ?>">
                            <h4>
                                <?php if(get_post_type() == 'ldpshow'){ ?>
                                <a href="http://byuicomm.net/ldp/" title="See all Latter-Day profiles">latter-day profile</a>
                                <?php }elseif(get_post_type() == 'blogs'){?>
                                <a href="http://byuicomm.net/blogs/" title="See all blogs">blog</a>
                                <?php }elseif(get_post_type() == 'portfolio'){?>
                                <a href="http://byuicomm.net/agency/" title="See all portfolios">agency</a>
                                <?php }else echo the_category(" "); ?>
                                
                            </h4>
         <div class="wide-left">
                   <?php if ( function_exists("has_post_thumbnail")) { ?>
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail(array(160, 160), array("class" => "alignleft post_thumbnail img-news-decor")); ?>
                        </a>
                    <?php } ?>
                    <p class="postmetadata"><?php echo "Published: " ?><?php the_time('F jS, Y') ?><br />
             <?php the_tags('Tags: ', ', ', '<br />'); ?> 
         </div>          
         <div class="wide-right">             
            <?php $title = get_the_title(); $keys= explode(" ",$s); $title = preg_replace('/('.implode('|', $keys) .')/iu', '<strong class="search-excerpt">\0</strong>', $title); ?>
       		<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo $title; ?></a></h1>
        		<?php the_excerpt(); ?> 	
         </div>
                            <div class="wide-bottom">
                    <p class="bottom-left"><?php if(get_the_author_meta('first_name') != ''){?>by <?php the_author_posts_link();};?>
                    <span class="comments-counter"><?php comments_number( '0', '  1 ', ' % ' ); ?><em></em></span></p>
                    <div class="bottom-right"><a class="fb-share" name="fb_share" type="button" share_url="<?php the_permalink()?>">Share</a>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="<?php the_permalink()?>" data-text="<?php $title?>">Tweet</a>
                    <div class="gplus-wrap"><g:plusone href="<?php the_permalink();?>" size="small"></g:plus></div></div>
                                    </div>
              			</li>
                            <?php endwhile; ?>
                            </ul>
<div class="navigation">
      <?php if (function_exists("pagination")) {
      pagination($additional_loop->max_num_pages);} ?>
</div>