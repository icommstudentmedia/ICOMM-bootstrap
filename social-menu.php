<?php
/**
* Social Bar containing social media links
*     - Facebook
*     - Twitter
*     - Pinterest
*     - Instagram
* Hidden on the mobile version
*/
?>
<div class="span2 pull-right visible-desktop social-block">
	<div class="row-fluid social-pics">
		<div class="span3 social-icons">
			<a href="https://www.facebook.com/icomm.student.media?fref=ts"><img src="<?php bloginfo( 'template_url' ); ?>/img/f_logo.png"></a>
		</div>
		<div class="span3 social-icons">
			<a href="https://twitter.com/byuiscroll"><img src="<?php bloginfo( 'template_url' ); ?>/img/twitter_logo.png"></a>
		</div>
		<div class="span3 social-icons">
			<a href="http://pinterest.com/byuicomm/"><img src="<?php bloginfo( 'template_url' ); ?>/img/pinterest_logo.png"></a>
		</div>
		<div class="span3 social-icons">
			<a href="http://instagram.com/byuiscroll/"><img src="<?php bloginfo( 'template_url' ); ?>/img/instagram_icon_large.png"></a>
		</div>
	</div>
</div>

<?php 
/**
* Mobile version of the social bar
* 
* Hidden on the desktop version
*/
?>
<div id="mobile-social" class="span2"> <!-- mobile-social is found in the custom.css -->
	<div class="row-fluid social-pics">
			<a href="https://www.facebook.com/icomm.student.media?fref=ts"><img src="<?php bloginfo( 'template_url' ); ?>/img/mobile-social-icons/facebook-mobile.png"></a>
			<a href="https://twitter.com/byuiscroll"><img src="<?php bloginfo( 'template_url' ); ?>/img/mobile-social-icons/twitter-mobile.png"></a>
			<a href="http://pinterest.com/byuicomm/"><img src="<?php bloginfo( 'template_url' ); ?>/img/mobile-social-icons/pinterest-mobile.png"></a>
			<a href="http://instagram.com/byuiscroll/"><img src="<?php bloginfo( 'template_url' ); ?>/img/mobile-social-icons/instagram-mobile.png"></a>
	</div>
</div>