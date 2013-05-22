<?php global $display_name , $user_email;
      get_currentuserinfo();?>

<div class="my_meta_portfolio">
	
	<p>Select the Orientation of the Portfolio</code>.</p>
	<label>Orientation <span>(required)</span></label>

	<p>
		<select type="text" name="_my_meta_box[orientation]" id="orientation">
        	<option  selected="selected" value="<?php if(!empty($meta_orientation['orientation'])) echo $meta_orientation['orientation']; ?>"><?php if(!empty($meta_orientation['orientation'])) echo $meta_orientation['orientation']; ?></option>
        	<option value="horizontal">Horizontal</option>
            <option value="vertical">Vertical</option>
        </select>
	</p>
</div>

