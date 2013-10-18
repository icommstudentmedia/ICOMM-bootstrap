<?php
   /**
    *   Template Name: Info Page
    *   Date: October 16,2013
    *   @author Guilherme Bentim, Isaac Andrade
    **/	
get_header();
?>

    <!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
      <?php include_once "social-menu.php"; ?>
      
<div class="container container-narrow">
	<div class="row-fluid vert-padding">
		<!-- todo -->
		<div class="span10">
			<!-- change and style -->
			<h2 class="feature"> <?php wp_title("", true); ?></h2>
		</div> 						  
	</div>
</div>

	<hr/>

	<div class="row-fluid responsive-about">
		<div id="post-content" class="span5">
			
			<?php 
				if(have_posts() ) : while (have_posts() ) : the_post();
				the_content();
				endwhile; endif;
			?>
			
		</div>
	</div>

	<div class="row-fluid"></div> <!-- I added this div so that I can push the footer on the bottom (LP) -->


<?php get_footer();?>
