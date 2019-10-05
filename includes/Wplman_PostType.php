<?php
include dirname( __FILE__ ) . '/library/class-wplman-post-type-generator.php';


$post_type_names = array(
	'post_type_name'    => 'shortlink',
	'singular'          => 'Short link',
	'plural'            => 'Short links',
	'slug'              => 'shortlink'
);

$post_type_options = array(
	'supports' => array('title'),
	'public' => true,
	'exclude_from_search' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_nav_menus' => false,
	'show_in_menu' => true,
	'show_in_admin_bar' => false,
//	@todo select from plugin options
	'query_var' => 'shortlink'
);

/**
 * Create post type object
 */
$short_links =  new Wplman_Post_Type_Generator( $post_type_names, $post_type_options );


/**
 * Config the post type
 * - columns
 * - taxonomy
 * - add meta fields
 */

$short_links->register_taxonomy('link_group');