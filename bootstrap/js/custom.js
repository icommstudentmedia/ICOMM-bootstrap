jQuery(document).ready(function(){
	jQuery('.carousel').carousel({
	  interval : 10000
	});
	jQuery("#navbar-wrapper").affix({
		offset: {top: 450}
	});
	jQuery(".navbar-wrapper").affix({
		offset: {top: 450}
	});

	// add click for full screen functionality to the galleries
	if(typeof Galleria != 'undefined'){	
		Galleria.ready(function() {
			jQuery('.galleria-image').attr('title', 'Click for full-screen').click(function(){
				jQuery('.galleria-popout').click();
			});
			jQuery('.galleria-image').css('cursor','hover');
		});
	}
});
