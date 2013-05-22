<?php global $display_name , $user_email;
      get_currentuserinfo();?>

<div class="my_meta_ldp">
	
	<p>Add Episode information</p>
	<label>Episode <span>(required)</span></label>

    <p>
    	<input type="text" name="_my_meta_ldp[episode]" value="<?php if(!empty($meta_ldp['episode'])) echo $meta_ldp['episode']; ?>"/>
    </p>
    <p>
    	<label>Select Air Date <span>(required)</span></label>
    	<input id="datepicker" type="text" name="_my_meta_ldp[date]" value="<?php if(!empty($meta_ldp['date'])) echo $meta_ldp['date']; ?>" />
    </p>
</div>
<link media="all" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery(function() {
		jQuery( "#datepicker" ).datepicker({ dateFormat: 'DD, MM d, yy' });
	});
});
</script>