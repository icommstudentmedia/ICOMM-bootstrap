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

	<!-- Page Aside -->
        <aside class="span3">
          <img src="<?php echo content_url();?>/themes/icomm-bootstrap/img/icomm.png" alt="Advertise With Us Image">

          <h3 class="iComm">I~Comm Student Media</h3>
          <h5>Contact Information</h5>
          <hr class="line-height-no-margins">
          <p><strong>Scroll Advertising Sales</strong></p>
          <ul class="ul-nostyle">
            <li><a href="mailto:scrollads@byui.edu">scrollads@byui.edu</a></li> <!-- This enables the user to send an email by clicking on it. -->
            <li>Office: <span class="office-number">208-496-3730</span></li>
            <li>Fax: <span class="fax-number">208-496-5411</span></li>
          </ul>   
        </aside>

	<div class="row-fluid responsive-about" >
		<div id="post-content" class="span5">
					
			<?php 
				if(have_posts() ) : while (have_posts() ) : the_post();
				the_content();
				endwhile; endif;
			?>
			
		</div>
		<div class ="span4">
		<?php google_ad("sidebar"); ?>

	</div>
	</div>



	<div class="row-fluid"></div> <!-- I added this div so that I can push the footer on the bottom (LP) -->


<?php get_footer();?>
