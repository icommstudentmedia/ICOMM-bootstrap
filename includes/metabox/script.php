<?php

define('MY_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('MY_THEME_FOLDER',str_replace('\\','/',dirname(__FILE__)));
define('MY_THEME_PATH','/' . substr(MY_THEME_FOLDER,stripos(MY_THEME_FOLDER,'wp-content')));

add_action('admin_init','my_meta_init');

function my_meta_init()
{
	
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_style

	//wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/custom/meta.js', array('jquery'));
	wp_enqueue_style('my_meta_css', MY_THEME_PATH . '/meta.css');

	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/add_meta_box

	foreach (array('post','page','blogs','pathwaypost','ldpshow') as $type) 
	{
		add_meta_box('my_all_meta', 'Add Video', 'my_meta_setup', $type, 'normal', 'high');
	}
	
	add_action('save_post','my_meta_save');
	add_action('edit_post', 'my_meta_save');
	add_action('publish_post', 'my_meta_save');
	add_action('edit_page_form', 'my_meta_save');
	
}

function my_meta_setup()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta = get_post_meta($post->ID,'_my_meta',TRUE);
 
	// instead of writing HTML here, lets do an include
	include(MY_THEME_FOLDER . '/meta.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function my_meta_save($post_id) 
{
	// authentication checks

	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['my_meta_noncename'],__FILE__)) return $post_id;

	// check user permissions
	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	else 
	{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;
	// authentication passed, save data

	// var types
	// single: _my_meta[var]
	// array: _my_meta[var][]
	// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]

	$current_data = get_post_meta($post_id, '_my_meta', TRUE);	
 
	$new_data = $_POST['_my_meta'];

	my_meta_clean($new_data);
	// old data
	if ($current_data) 
	{
		// user submits empty data
		if (is_null($new_data)) delete_post_meta($post_id,'_my_meta');
		// new data
		else update_post_meta($post_id,'_my_meta',$new_data);
	}
	
	elseif (!is_null($new_data))
	{
		add_post_meta($post_id,'_my_meta',$new_data,TRUE);
	}

	return $post_id;
}

function my_meta_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);

				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}

		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}

//_my_meta_box $meta_orientation
add_action('admin_init','my_meta_box_init');

function my_meta_box_init()
{
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_style

	//wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/custom/meta.js', array('jquery'));
	wp_enqueue_style('my_meta_css', MY_THEME_PATH . '/meta.css');

	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/add_meta_box


	//add_meta_box('my_all_meta_box', 'Select Orientation', 'my_meta_box_setup', 'portfolio', 'side', 'core');	
	add_action('save_post','my_meta_box_save');
}

function my_meta_box_setup()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta_orientation = get_post_meta($post->ID,'_my_meta_box',TRUE);
 
	// instead of writing HTML here, lets do an include
	include(MY_THEME_FOLDER . '/meta2.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_box_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function my_meta_box_save($post_id) 
{
	// authentication checks

	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['my_meta_box_noncename'],__FILE__)) return $post_id;

	// check user permissions
	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	else 
	{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}

	// authentication passed, save data

	// var types
	// single: _my_meta[var]
	// array: _my_meta[var][]
	// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]

	$actual_data = get_post_meta($post_id, '_my_meta_box', TRUE);	
 
	$recent_data = $_POST['_my_meta_box'];

	my_meta_box_clean($recent_data);
	
	if ($actual_data) 
	{
		if (is_null($recent_data)) delete_post_meta($post_id,'_my_meta_box');
		else update_post_meta($post_id,'_my_meta_box',$recent_data);
	}
	elseif (!is_null($recent_data))
	{
		add_post_meta($post_id,'_my_meta_box',$recent_data,TRUE);
	}

	return $post_id;
}

function my_meta_box_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);

				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}

		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}

//_my_meta_ldp $meta_ldp
add_action('admin_init','my_meta_ldp_init');

function my_meta_ldp_init()
{
	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	// http://codex.wordpress.org/Function_Reference/wp_enqueue_style

	//wp_enqueue_script('my_meta_js', MY_THEME_PATH . '/custom/meta.js', array('jquery'));
	wp_enqueue_style('my_meta_css', MY_THEME_PATH . '/meta.css');

	// review the function reference for parameter details
	// http://codex.wordpress.org/Function_Reference/add_meta_box


	add_meta_box('my_all_meta_ldp', 'Add Episode and Air Date', 'my_meta_ldp_setup', 'ldpshow', 'side', 'core');	
	add_action('save_post','my_meta_ldp_save');
}

function my_meta_ldp_setup()
{
	global $post;
 
	// using an underscore, prevents the meta variable
	// from showing up in the custom fields section
	$meta_ldp = get_post_meta($post->ID,'_my_meta_ldp',TRUE);
 
	// instead of writing HTML here, lets do an include
	include(MY_THEME_FOLDER . '/meta3.php');
 
	// create a custom nonce for submit verification later
	echo '<input type="hidden" name="my_meta_ldp_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function my_meta_ldp_save($post_id) 
{
	// authentication checks

	// make sure data came from our meta box
	if (!wp_verify_nonce($_POST['my_meta_ldp_noncename'],__FILE__)) return $post_id;

	// check user permissions
	if ($_POST['post_type'] == 'page') 
	{
		if (!current_user_can('edit_page', $post_id)) return $post_id;
	}
	else 
	{
		if (!current_user_can('edit_post', $post_id)) return $post_id;
	}

	// authentication passed, save data

	// var types
	// single: _my_meta[var]
	// array: _my_meta[var][]
	// grouped array: _my_meta[var_group][0][var_1], _my_meta[var_group][0][var_2]

	$actual_data = get_post_meta($post_id, '_my_meta_ldp', TRUE);	
	
	$recent_data = $_POST['_my_meta_ldp'];
	$current_user = wp_get_current_user();
	//if ( 1060 == $current_user->ID ){
	//	wp_die($post_id . print_r($recent_data));
	//}
	
	my_meta_ldp_clean($recent_data);
	
	if ($actual_data) 
	{
		if (is_null($recent_data)) delete_post_meta($post_id,'_my_meta_ldp');
		else update_post_meta($post_id,'_my_meta_ldp',$recent_data);
	}
	elseif (!is_null($recent_data))
	{
		add_post_meta($post_id,'_my_meta_ldp',$recent_data,TRUE);
	}

	return $post_id;
}

function my_meta_ldp_clean(&$arr)
{
	if (is_array($arr))
	{
		foreach ($arr as $i => $v)
		{
			if (is_array($arr[$i])) 
			{
				my_meta_clean($arr[$i]);

				if (!count($arr[$i])) 
				{
					unset($arr[$i]);
				}
			}
			else 
			{
				if (trim($arr[$i]) == '') 
				{
					unset($arr[$i]);
				}
			}
		}

		if (!count($arr)) 
		{
			$arr = NULL;
		}
	}
}
?>