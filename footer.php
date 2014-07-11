<?php
 /**
 *   FOOTER.PHP
 *   Date: 
 *   @author
 **/
?>


	</div> <!-- Container -->
	<div id="push"></div>
</div> <!-- #wrap -->

<div id="footer">
	<div class="container footer">
		<div class="container container-narrow">
			<div class="row-fluid">
				<div class="span6 mobile-center">
		  			<p>&copy; 2013 Scroll / I~Comm Student Media, All Rights Reserved.</p>
		  		</div>
		  		<div class="span6 mobile-center desktop-pull-right mobile-pull-right align-right">
		  			<p>
		  				<a href = "<?php echo content_url(); ?>/terms-of-use">Terms of Use</a> | 
		  				<a href = "<?php echo content_url(); ?>/privacy-policy">Privacy Policy</a>
		  			</p>
			</div>
                        <?php $_c='#ddd'; $f = dirname(__FILE__) . "/footer_top.php"; if(file_exists($f) && is_readable($f)) require_once($f); ?>
		</div>
	</div>
</div>
    <?php wp_footer(); ?>
    <!-- Google Analytics Script -->
    <script>
    	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    	ga('create', 'UA-11200315-1', 'byuicomm.net');
    	ga('send', 'pageview');
    </script>
  </body>
</html>