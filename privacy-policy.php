<?php
/**
* This has to go on it to be a template page
* Template Name: Privacy Policy
*
* @author Guilherme Bentim <guilherme.guizmo@gmail.com>
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
			<h2 class="feature"> Privacy Policy </h2>
		</div> 						  
	</div>
</div>

	<hr/>

	<div class="row-fluid responsive-about">
		<div class="span3">	<!-- Left Sidebar-->
			<figure>
		<img src="/wp-content/themes/icomm-bootstrap/img/icomm.png" width="120" height="164"/>
			</figure>
		</div>				<!-- End Left Sidebar -->

		<div id="post-content" class="span5"> 	<!-- Content -->
			
			<h3> Sub Header</h3>
			<p>Content
			</p>
						
			<h3> Sub Header </h3>
			<p> Content
			<p/>
		</div>						<!-- End Content -->
	</div>

	<div class="row-fluid"></div> <!-- I added this div so that I can push the footer on the bottom (LP) -->


<?php get_footer();?>
