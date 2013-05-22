<?php 
		if ( is_home() ) {
	wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'vertical-menu') );
	} else {?>
		<?php
        if (is_page(array('scroll','campus', 'digital','sports','entertainment','lifestyle','news','opinion','spanish','special','videos','slideshows','photography'))) { ;?>
	<?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'vertical-menu') );?>
        <?php } ?>
        
        <?php
        if (is_page('ldp')) { ;?>
	<?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'ldp-menu') );?>
        <?php } ?>
        
        <?php
        if (is_page(array('agency','services','about', 'blog'))) { ;?>
	<?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'agency-menu') );?>
        <?php } ?>
        
        <?php
        if (is_page('pathway')) { ;?>
    <?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'pathway-menu') );?>
        <?php } ?>
        
        <?php
        if (is_page('projects')) { ;?>
    <?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'projects-menu') );?>
        <?php } ?>
        
        <?php
        if (is_singular('post')) { ;?>
    <?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'vertical-menu') );?>
        <?php } ?>
        
        <?php
        if (is_singular('portfolio')) { ;?>
    <?php wp_nav_menu( array( 'container' =>false, 'items_wrap' => '%3$s', 'theme_location' => 'agency-menu') );?>
        <?php } ?>
   
        
       
	
	<?php }?>