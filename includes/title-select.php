<?php 
		if ( is_home() ) {
	echo 'Student Media';
	} else {?>
		<?php
        if (is_page('scroll')) { ?>
        I~Comm Student Media
        <?php } ?>
        <?php
        if (is_page('live')) { ?>
        Graduation - Live event
        <?php } ?>
        <?php
        if (is_page('videos')) { ;?>
        Videos
        <?php } ?>
        
        <?php
        if (is_page('contact')) { ;?>
        Contact
        <?php } ?>
        
        <?php
        if (is_page('news')) { ;?>
        News
        <?php } ?>
        
        <?php
        if (is_page('digital')) { ;?>
        Scroll Digital
        <?php } ?>
        
		<?php
        if (is_page('photography')) { ;?>
        Photography
        <?php } ?>
        		
        <?php
        if (is_page('agency')) { ;?>
        Agency
        <?php } ?>
        
        <?php
        if (is_page('pathway')) { ;?>
        Pathway
        <?php } ?>
        
         <?php
        if (is_page('slideshows')) { ;?>
        Slideshows
        <?php } ?>
        
        <?php
        if (is_page('projects')) { ;?>
        Projects
        <?php } ?>
        
        <?php
        if (is_page('local')) { ;?>
        Local
        <?php } ?>
        
        <?php
        if (is_page('campus')) { ;?>
        campus
        <?php } ?>
        
        <?php
        if (is_page('entertainment')) { ;?>
        entertainment
        <?php } ?>
        
        <?php
        if (is_page('sports')) { ;?>
        Sports
        <?php } ?>
        
        <?php
        if (is_page('blogs')) { ;?>
        blog
        <?php } ?>
        
        <?php
        if (is_page('lifestyle')) { ;?>
        lifestyle
        <?php } ?>
        
		<?php
        if (is_page('opinion')) { ;?>
        opinion
        <?php } ?>
        
		<?php
        if (is_page('spanish')) { ;?>
        spanish
        <?php } ?>
        
		<?php
        if (is_page('special')) { ;?>
        special
        <?php } ?>
        
		<?php
        if (is_page('international')) { ;?>
        international
        <?php } ?>
        
        <?php
        if (is_page('national')) { ;?>
        national
        <?php } ?>
        
        <?php
        if (is_page('services')) { ;?>
        services
        <?php } ?>
        
        <?php
        if (is_page('ldp')) { ;?>
        Latter-Day Profiles
        <?php } ?>
        
        <?php
        if (isset($_GET['s']) || isset($_GET['q'])) { ;?>
        search
        <?php } ?>
        
        <?php
        if (is_page('about')) { ;?>
        about us
        <?php } ?>
        
        <?php
        if (is_tag()) { ?>
        tags
        
        <?php 
        }else if(is_author()) { ?>
        author
        
        <?php
        }else if (is_archive()) { ;//tag and author pages also count as archive pages, so the else prevents it from printing both titles?> 
        archives
        <?php } ?>
        
        <?php
        if (is_singular('post')) { ;?>
        <?php the_category("  ") ?>
        <?php } ?>
	
	<?php }?>