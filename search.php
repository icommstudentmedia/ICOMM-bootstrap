<?php 
/**
*   Template Name: Search
*   Date: April 17, 2013
*	@author Isaac Andrade
*/

/* Description: This file is the Search Results page - That appears after typing a search in the website's search bar.
				It uses the Google Custom Search Engine for that. The JavaScript is autogenerated by Google's CSE. Right now it is tied to
				Isaac's Google account, but it will be tranferred to John Thompson's Google account so that future employees can customize
				it when needed.
*/
				
get_header();
?>

<div class="container container-narrow">
	<div class="row-fluid vert-padding">
		<div class="span10">
			<h2 class="feature">Search results for "<?php echo $_GET["q"]; ?>"</h2>
		</div> 

		<!-- This file contains the social media icons for the desktop version and also 
      the mobile version of the website -->
      <?php include_once "social-menu.php"; ?>
      
	</div>
	<hr/>
	<!-- End of Title Block -->

	<?php while ( have_posts() ) : the_post(); ?>
	
	<!-- Google CSE (Custom Search Engine) Box -->
	<div id="search-box">
		<script>
		(function() {
			var cx = '014258444359539991967:3v_qcyewfxs';
			var gcse = document.createElement('script');
			gcse.type = 'text/javascript';
			gcse.async = true;
			gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
			'//www.google.com/cse/cse.js?cx=' + cx;
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(gcse, s);
		})();
		</script>

<!-- 		<gcse:searchbox></gcse:searchbox> -->
	</div> <!-- End of Google CSE Box -->
	
<?php endwhile; // end of the loop. ?>

<!-- Google CSE Result -->
<div class="span8">
	<div id="search-results">
		<gcse:searchresults-only></gcse:searchresults-only>
	</div> <!-- End of Google CSE Result -->
</div>
</div>

<?php get_footer(); ?>
