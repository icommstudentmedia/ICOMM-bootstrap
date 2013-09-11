<?php

/**
 * POST_TYPE.PHP
 * 
 * Description: Called in functions.php line 619
 *              This file will add customized post-types to the back-end.
 *              They are visible in the left column all preceded by the 
 *              drawing of a pinpoint. Posts from customized post-types
 *              will not automatically be on the website.
 *
 * 
 *
 * @author Lyle Palagar (), Isaac Andrade (), Gizmo (guilherme.guizmo@gmail.com)
 *
 */


/**
* Feedback Custom Post Type
* Aug 20 , 2013
*
* These are for Tester to send feedback to the developers
* Only Developers should have admin access to the feedback.
*
* @author Lyle Palagar, Isaac Andrade
* @param
* @return
*/
add_action( 'init','feedback_custom_type');
function feedback_custom_type(){
	register_post_type('feedback',
		array(
            //customize all labels for a post-type with correspondent values
			'labels'=>array(
						'name'=>'Feedback',
						'singular'=>'Feedback',
						'all_items'=>'All Feedback',
						'add_new'=>'Add New',
						'add_new_item'=>'New Feedback',
						'edit_item'=>'Edit',
						'new_item'=>'New Feedback',
						'view_item'=>'View Feedback',
						'search_items'=>'Search Feedback',
						'not_found'=>'No Feedback Found',
						'not_found_in_trash'=>'No feedback Found in Trash',
						'parent'=>'Parent Feedback',
						'menu_name'=>'Feedback',
						),
			'supports'=>array('title','editor','author','thumbnail','trackbacks','custom-fields','revision', 'comments'),
			'description' => 'These are for Beta-Testers to send feedback to the Developers.',
			'public' => true, // keep the custom type shown on the wp-admin page
			'menu_position' => 25,
			)
		);
}

/**
*   Feedback Categories
* 	These are taxonomies(jargon used by wp to refer to 'categories') that will be changed initially by the author,
* 	but afterwards it will be changed ONLY by administrators
*	They will be used to represent the status of the feedback, if it's been
*	worked on, ignored, or still have pending work to do. 
*
*   @author Isaac Andrade
*   @param
*   @return
*/
add_action('init', 'feedback_taxonomies', 0);
function feedback_taxonomies() {
	//customize all labels
	$labels = array(
					'name' => 'Status',
					'all_items' => 'All Statuses',
					'menu_name' => 'Status',
					'edit_item' => 'Edit Status',
					'view_item' => 'View Status',
					'update_item' => 'Update Status',
					'add_new_item' => 'Create New Status',
					'new_item_name' => 'New Status Name',
					'search_items' => 'Search',
					);
	//this taxonomy is called status
	register_taxonomy('status', 'feedback', 
		array(
			'hierarchical' => true,
			'labels' => $labels,
			'query_var' => false,
			'rewrite' => false,
			));
}

/**
*  DevPress Edit Feedback Columns
*      This function will edit what information will be displayed in the tab "All Feedback"
*
* @author Isaac & Gizmo
* @param $columns_feedback (the columns currently under post-type 'feedback')
* @return $columns_feedback (the edited new columns)
**/
//Custom Columns
add_filter( 'manage_edit-feedback_columns', 'devpress_edit_feedback_columns' ) ;
function devpress_edit_feedback_columns( $columns_feedback ) {

	$columns_feedback += array(
		'cb'=>__('<input type="checkbox" />'),
		'title' => __( 'Title' ),
		'status' => __( 'Status' ),
		'author'=>__('Author'),
		'date' => __( 'Date' )
	);

	return $columns_feedback;
}

/**
*  DevPress Manage Feedback Columns 
*     This function will manage what information will be inside each collumn displayed in the tab "All Feedback"
*
* @author Isaac & Gizmo
* @param $column_feedback (edited columns), &post_id (current)
* @return
*/
add_action( 'manage_feedback_posts_custom_column', 'devpress_manage_feedback_columns', 10, 2 );
function devpress_manage_feedback_columns( $column_feedback, $post_id ) {
	global $post;
	switch( $column_feedback ) {
		/* If displaying the 'status' column. */
		case 'status' :
			/* Get the status for the post. */
			$terms_feedback = get_the_terms( $post_id, 'status' );
			/* If terms were found. */
			if ( !empty( $terms_feedback ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				//allows more than one status(taxonomy) per post
				foreach ( $terms_feedback as $term_feedback ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'status' => $term_feedback->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term_feedback->name, $term_feedback->term_id, 'status', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Status' );
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}





/**
*  Portfolio Custom Type 
*     Portfolio is a custom type that is currently defined in the left column
*     as Agency
* @author
* @param
* @return
*/
//Custom Post Type 
add_action('init','portfolio_custom_type');
function portfolio_custom_type(){
	register_post_type('portfolio',
		array(
			
			'labels'=>array(
						'name'=>'Portfolios',
						'singular'=>'Portfolio',
						'all_items'=>'All Portfolios',
						'add_new'=>'Add New',
						'add_new_item'=>'Add New',
						'edit_item'=>'Edit Porfolio',
						'new_item'=>'New Portfolio',
						'view_item'=>'View Portfolio',
						'search_items'=>'Search Portfolios',
						'not_found'=>'No Portfolios Found',
						'not_found_in_trash'=>'No Portfolios Found in Trash',
						'parent'=>'Parent Portfolio',
						'menu_name'=>'Agency',
						),
			'supports'=>array('title','editor','author','thumbnail','trackbacks','custom-fields','revision'),
			'public'=>true,
			'rewrite'=>array('slug'=>'agency','with_front'=>false),
		)
	);
}


/**
*  Portfolio Taxonomies
*    Taxonomy will be labeled 'Categories'
*
* @author
* @param
* @return
**/
//Taxonomy Hierarchical
add_action('init','portfolio_taxonomies');
function portfolio_taxonomies(){
	register_taxonomy('group','portfolio',
		array(
			'hierarchical'=>true,
			'labels'=>array(
						'name'=>'Categories',
						'singular_name'=>'Category',
						'search_items'=>'Search Categories',
						'popular_items'=>'Popular Categories',
						'all_items'=>'All Categories',
						'parent_item'=>'Parent Category',
						'parent_item_colon'=>'Parent Category',
						'edit_item'=>'Edit Category',
						'update_item'=>'Update Category',
						'add_new_item'=>'Add New Category',
						'new_item_name'=>'New Category Name',
						)
		)
	);
}

/**
*  DevPress Edit Portfolio Columns
*    Edit the information columns displayed in 'All Portfolios'
*
* @author
* @param $columns (current columns)
* @return &columns (update columns)
*/
//Custom Columns
add_filter( 'manage_edit-portfolio_columns', 'devpress_edit_portfolio_columns' ) ;
function devpress_edit_portfolio_columns( $columns ) {

	$columns = array(
		'cb'=>__('<input type="checkbox" />'),
		'title' => __( 'Title' ),
		'group' => __( 'Category' ),
		'author'=>__('Author'),
		'date' => __( 'Date' )
	);

	return $columns;
}

/**
*  DevPress Manage Portfolio Columns 
*    Manage the information inside the columns under 'All Portfolios'
*
* @author
* @param &column, $post_id (edited columns and current post)
* @return
**/
add_action( 'manage_portfolio_posts_custom_column', 'devpress_manage_portfolio_columns', 10, 2 );
function devpress_manage_portfolio_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		/* If displaying the 'group' column. */
		case 'group' :
			/* Get the groups for the post. */
			$terms = get_the_terms( $post_id, 'group' );
			/* If terms were found. */
			if ( !empty( $terms ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'group' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'group', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Category' );
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

/**
* ldpShow Custom Type
*     Latter-Day Profiles were custom post-types used in the old version.
*     It is still uknown if a page will be created for them in the new version
* @author
* @param
* @return
**/
//Custom Post Type 
add_action('init','ldpshow_custom_type');
function ldpshow_custom_type(){
	register_post_type('ldpshow',
		array(
			 //customize labels
			'labels'=>array(
						'name'=>'Latter-day Profiles',
						'singular'=>'Latter-day Profile',
						'all_items'=>'All Episodes',
						'add_new'=>'Add New Episode',
						'add_new_item'=>'Add New Episode',
						'edit_item'=>'Edit Episode',
						'new_item'=>'New Episode',
						'view_item'=>'View Episode',
						'search_items'=>'Search Episode',
						'not_found'=>'No Episodes Found',
						'not_found_in_trash'=>'No Episodes Found in Trash',
						'parent'=>'Parent Episode',
						'menu_name'=>'Latter-day Profiles',
						),
			'supports'=>array('title','editor','author','thumbnail','trackbacks','custom-fields','revision', 'comments'),
			'public'=>true,
			'rewrite'=>array('slug'=>'ldp','with_front'=>true),
		)
	);
}

/**
*  ldpShow Taxonomies
*     Taxonomy will be called 'Seasons'
*
* @author
* @param
* @return
**/
//Taxonomy Hierarchical
add_action('init','ldpshow_taxonomies');
function ldpshow_taxonomies(){
	register_taxonomy('ldpseason','ldpshow',
		array(
			'hierarchical'=>true,
			'labels'=>array(
						'name'=>'Seasons',
						'singular_name'=>'Season',
						'search_items'=>'Search Seasons',
						'popular_items'=>'Popular Seasons',
						'all_items'=>'All Seasons',
						'parent_item'=>'Parent Season',
						'parent_item_colon'=>'Parent Season',
						'edit_item'=>'Edit Season',
						'update_item'=>'Update Seasons',
						'add_new_item'=>'Add New Seasons',
						'new_item_name'=>'New Seasons',
						)
		)
	);
}

/**
*  DevPress Edit ldpShow Columns
*     Edit the information columns displayed in 'All Episodes'
*
* @author
* @param $columns_ldpshow (information columns)
* @return &columns_ldpshow (updated columns)
**/
//Custom Columns
add_filter( 'manage_edit-ldpshow_columns', 'devpress_edit_ldpshow_columns' ) ;
function devpress_edit_ldpshow_columns( $columns_ldpshow ) {

	$columns_ldpshow = array(
		'cb'=>__('<input type="checkbox" />'),
		'title' => __( 'Title' ),
		'ldpseason' => __( 'Season' ),
		'author'=>__('Author'),
		'date' => __( 'Date' )
	);

	return $columns_ldpshow;
}

/**
*  DevPress Manage ldpShowColumns 
*     Manage the information inside the columns displayed in 'All Episodes'
*
* @author
* @param &column_ldpshow, $post_id (edited columns and current post)
* @return
*/
add_action( 'manage_ldpshow_posts_custom_column', 'devpress_manage_ldpshow_columns', 10, 2 );
function devpress_manage_ldpshow_columns( $column_ldpshow, $post_id ) {
	global $post;
	switch( $column_ldpshow ) {
		/* If displaying the 'seasons' column. */
		case 'ldpseason' :
			/* Get the seasons for the post. */
			$terms_ldpshow = get_the_terms( $post_id, 'ldpseason' );
			/* If terms were found. */
			if ( !empty( $terms_ldpshow ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms_ldpshow as $term_ldpshow ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'ldpseason' => $term_ldpshow->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term_ldpshow->name, $term_ldpshow->term_id, 'ldpseason', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Seasons' );
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}


/**
* Blogs Custom Type
*
*
* @author
* @param
* @return
**/
//Custom Post Type 
add_action('init','blogs_custom_type');
function blogs_custom_type(){
	register_post_type('blogs',
		array(
			
			'labels'=>array(
						'name'=>'Blogs',
						'singular'=>'Blogs',
						'all_items'=>'All Blogs',
						'add_new'=>'Add New',
						'add_new_item'=>'Add New',
						'edit_item'=>'Edit Blog',
						'new_item'=>'New Blog',
						'view_item'=>'View Blogs',
						'search_items'=>'Search Blogs',
						'not_found'=>'No Blogs Found',
						'not_found_in_trash'=>'No Blogs Found in Trash',
						'parent'=>'Parent Blogs',
						'menu_name'=>'Blogs',
						),
			'supports'=>array('title','editor','author','thumbnail','trackbacks','custom-fields','revision','comments'),
			'public'=>true,
			'rewrite'=>array('slug'=>'blog','with_front'=>true),
		)
	);
}


/**
*  Blogs Taxonomies
*
*
* @author
* @param
* @return
**/
//Taxonomy Hierarchical
add_action('init','blogs_taxonomies');
function blogs_taxonomies(){
	register_taxonomy('blogscat','blogs',
		array(
			'hierarchical'=>true,
			'labels'=>array(
						'name'=>'Categories',
						'singular_name'=>'Category',
						'search_items'=>'Search Categories',
						'popular_items'=>'Popular Categories',
						'all_items'=>'All Categories',
						'parent_item'=>'Parent Category',
						'parent_item_colon'=>'Parent Category',
						'edit_item'=>'Edit Category',
						'update_item'=>'Update Category',
						'add_new_item'=>'Add New Category',
						'new_item_name'=>'New Category Name',
						)
		)
	);
}

/**
*  PathwayPost Custom Type
*
*
* @author
* @param
* @return
**/
//Custome Post Type 
add_action('init','pathwaypost_custom_type');
function pathwaypost_custom_type(){
	register_post_type('pathwaypost',
		array(
			
			'labels'=>array(
						'name'=>'Pathway Post',
						'singular'=>'Pathway',
						'all_items'=>'All Stories',
						'add_new'=>'Add Story ',
						'add_new_item'=>'Add New Story',
						'edit_item'=>'Edit Story',
						'new_item'=>'New Story',
						'view_item'=>'View Story',
						'search_items'=>'Search Stories',
						'not_found'=>'No Stories Found',
						'not_found_in_trash'=>'No Stories Found in Trash',
						'parent'=>'Parent Stories',
						'menu_name'=>'Pathway',
						),
			'supports'=>array('title','editor','author','thumbnail','trackbacks','custom-fields','revision'),
			'public'=>true,
			'rewrite'=>array('slug'=>'pathway','with_front'=>true),
		)
	);
}


/**
*  PathwayPost Taxonomies
*        Taxonomy will be called 'Locations'
*
* @author
* @param
* @return
*/
//Taxonomy Hierarchical
add_action('init','pathwaypost_taxonomies');
function pathwaypost_taxonomies(){
	register_taxonomy('pathwayloc','pathwaypost',
		array(
			'hierarchical'=>true,
			'labels'=>array(
						'name'=>'Locations',
						'singular_name'=>'Location',
						'search_items'=>'Search Locations',
						'popular_items'=>'Popular Locations',
						'all_items'=>'All Locations',
						'parent_item'=>'Parent Location',
						'parent_item_colon'=>'Parent Location',
						'edit_item'=>'Edit Locations',
						'update_item'=>'Update Location',
						'add_new_item'=>'Add New Location',
						'new_item_name'=>'New Location',
						)
		)
	);
}


/**
*  DevPress Edit PathwayPost Columns
*
*
* @author
* @param
* @return
**/
//Custom Columns
add_filter( 'manage_edit-pathwaypost_columns', 'devpress_edit_pathwaypost_columns' ) ;
function devpress_edit_pathwaypost_columns( $columns_pathwaypost ) {

	$columns_pathwaypost = array(
		'cb'=>__('<input type="checkbox" />'),
		'title' => __( 'Title' ),
		'pathwayloc' => __( 'Location' ),
		'author'=>__('Author'),
		'date' => __( 'Date' )
	);

	return $columns_pathwaypost;
}


/**
*  DevPress Manage PathwayPost Columns
*
*
* @author
* @param
* @return
**/
add_action( 'manage_pathwaypost_posts_custom_column', 'devpress_manage_pathwaypost_columns', 10, 2 );
function devpress_manage_pathwaypost_columns( $column_pathwaypost, $post_id ) {
	global $post;
	switch( $column_pathwaypost ) {
		/* If displaying the 'location' column. */
		case 'pathwayloc' :
			/* Get the locations for the post. */
			$terms_pathwaypost = get_the_terms( $post_id, 'pathwayloc' );
			/* If terms were found. */
			if ( !empty( $terms_pathwaypost ) ) {
				$out = array();
				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms_pathwaypost as $term_pathwaypost ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'pathwayloc' => $term_pathwaypost->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term_pathwaypost->name, $term_pathwaypost->term_id, 'pathwayloc', 'display' ) )
					);
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}
			/* If no terms were found, output a default message. */
			else {
				_e( 'No Locations' );
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
?>