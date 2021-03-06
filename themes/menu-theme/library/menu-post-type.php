<?php
/* Bones Custom Post Type Example
This page walks you through creating
a custom post type and taxonomies. You
can edit this one or copy the following code
to create another one.

I put this in a separate file so as to
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

// Flush rewrite rules for custom post types
add_action( 'after_switch_theme', 'bones_flush_rewrite_rules' );
wp_set_object_terms( 123, 'menu', 'menu_cat' );
// Flush your rewrite rules
function bones_flush_rewrite_rules() {
	flush_rewrite_rules();
}

// let's create the function for the custom type
function menu() {
	// creating (registering) the custom type
	register_post_type( 'menu', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array( 'labels' => array(
			'name' => __( 'menu', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'menu', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'All menu Items', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Add New', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Add New Item', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Item', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'New Item', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'View Item', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Search menu', 'bonestheme' ), /* Search Custom Type Title */
			'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ), /* This displays if there are no entries yet */
			'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This is my menu', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
			'show_in_rest' => true,
  		'rest_base' => 'menu',
			//'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'menu'), /* you can specify its url slug */
			'has_archive' => false, /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky')
		) /* end of options */
	); /* end of register post type */

	/* this adds your post categories to your custom post type */
	//register_taxonomy_for_object_type( 'category', 'menu' );
	/* this adds your post tags to your custom post type */
	//register_taxonomy_for_object_type( 'post_tag', 'menu' );

}

function add_custom_types_to_tax( $query ) {
if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

// Get all your post types
$post_types = get_post_types();

$query->set( 'menu', $post_types );
return $query;
}
}
add_filter( 'pre_get_posts', 'add_custom_types_to_tax' );

	// adding the function to the Wordpress init
	// add_action( 'init', 'menu');
	add_action( 'init', 'menu' );
	function sb_post_type_rest_support() {
			global $wp_post_types;
			//be sure to set this to the name of your post type!
			$post_type_name = 'menu';
			if( isset( $wp_post_types[ $post_type_name ] ) ) {
					$wp_post_types[$post_type_name]->show_in_rest = true;
					$wp_post_types[$post_type_name]->rest_base = $post_type_name;
					$wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
			}
	}

	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/

	// now let's add custom categories (these act like categories)
	register_taxonomy( 'menu_cat',
		array('menu'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => array(
				'name' => __( 'Categories', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Category', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Categories', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Categories', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Category', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Category:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Category', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Category', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Category', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Category Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'menu_cat' ),
			'show_in_rest' => true,
  		// 'rest_base' => 'genre',
		)
	);

	// now let's add custom tags (these act like categories)
	register_taxonomy( 'menu_tag',
		array('menu'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => false,    /* if this is false, it acts like tags */
			'labels' => array(
				'name' => __( 'Tags', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Tag', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Tags', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Tags', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Tag', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Tag:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Tag', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Tag', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Tag', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Tag Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true,
			'show_ui' => true,
			'query_var' => true,
			'show_in_rest' => true,
			// 'rest_base' => 'genre',
		)
	);



	/*
		looking for custom meta boxes?
		check out this fantastic tool:
		https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	*/


?>
