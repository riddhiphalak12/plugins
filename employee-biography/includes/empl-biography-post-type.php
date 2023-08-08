<?php
/**
 * Register Post type functionality
 *
 * @package Employee Biography Plugin
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to register post type
 * 
 * @since 1.0.0
 */
function empl_biography_register_post_type () {

	$empl_post_labels = apply_filters( 'empl_biography_post_labels', array(
							'name' 					=> _x( 'Employee', 'post type general name', 'employee-biography' ),
							'singular_name' 		=> _x( 'Employee', 'post type singular name', 'employee-biography' ),
							'add_new' 				=> _x( 'Add New', 'employee', 'employee-biography' ),
							'add_new_item' 			=> __( 'Add New Employee', 'employee-biography' ),
							'edit_item' 			=> __( 'Edit Employee', 'employee-biography' ),
							'new_item' 				=> __( 'New Employee', 'employee-biography' ),
							'all_items' 			=> __( 'All Employees', 'employee-biography' ),
							'view_item' 			=> __( 'View Employee', 'employee-biography' ),
							'search_items' 			=> __( 'Search Employee', 'employee-biography' ),
							'not_found' 			=> __( 'No Employee Found', 'employee-biography' ),
							'not_found_in_trash'	=> __( 'No Employee Found In Trash', 'employee-biography' ),
							'parent_item_colon' 	=> '',
							'featured_image'		=> __( 'Employee Image', 'employee-biography' ),
							'set_featured_image'	=> __( 'Set Employee image', 'employee-biography' ),
							'remove_featured_image'	=> __( 'Remove Employee image', 'employee-biography' ),
							'use_featured_image'	=> __( 'Use as Employee image', 'employee-biography' ),
							'insert_into_item'		=> __( 'Insert into Employee', 'employee-biography' ),
							'uploaded_to_this_item'	=> __( 'Uploaded to this Employee', 'employee-biography' ),
							'menu_name' 			=> __( 'Employees', 'employee-biography' ),
						));

	$empl_args = array(
								'labels' 				=> $empl_post_labels,
								'public' 				=> true,
								'publicly_queryable' 	=> true,
								'show_ui' 				=> true,
								'show_in_menu' 			=> true,
								'query_var' 			=> true,
								'exclude_from_search'	=> false,
								'rewrite' 				=> array( 
																'slug' 			=> apply_filters( 'empl_biography_post_slug', 'employee' ),
																'with_front' 	=> false
															),
								'capability_type' 		=> 'post',
								'has_archive' 			=> apply_filters( 'empl_biography_archive_slug', false ),
								'hierarchical' 			=> false,
								'supports' 				=> apply_filters('empl_biography_post_supports', array( 'title', 'author' ,'editor', 'thumbnail', 'page-attributes', 'publicize', 'wpcom-markdown' )),
								'menu_position' 		=> 5,
								'menu_icon' 			=> 'dashicons-id',
							);

	// Register employee post type
	register_post_type( EMPL_BIOGRAPHY_POST_TYPE, apply_filters('empl_biography_post_type_args', $empl_args) );
}

// Action to register post type
add_action( 'init', 'empl_biography_register_post_type');

/**
 * Function to register taxonomy
 * 
 * @since 1.0.0
 */
function empl_biography_register_taxonomies() {

	$empl_cat_labels = apply_filters('empl_biography_cat_labels', array(
					'name'				=> __( 'Category', 'employee-biography' ),
					'singular_name'		=> __( 'Category', 'employee-biography' ),
					'search_items'		=> __( 'Search Category', 'employee-biography' ),
					'all_items'			=> __( 'All Category', 'employee-biography' ),
					'parent_item'		=> __( 'Parent Category', 'employee-biography' ),
					'parent_item_colon'	=> __( 'Parent Category', 'employee-biography' ),
					'edit_item'			=> __( 'Edit Category', 'employee-biography' ),
					'update_item'		=> __( 'Update Category', 'employee-biography' ),
					'add_new_item'		=> __( 'Add New Category', 'employee-biography' ),
					'new_item_name'		=> __( 'New Category Name', 'employee-biography' ),
					'menu_name'			=> __( 'Category', 'employee-biography' ),
				));

	$empl_cat_args = array(
					'hierarchical'		=> true,
					'labels'			=> $empl_cat_labels,
					'show_ui'			=> true,
					'show_admin_column'	=> true,
					'query_var'			=> true,
					'rewrite'			=> array( 'slug' => 'employee-cat' ),
				);

	// Register employee category
	register_taxonomy( 'employee-cat', array( EMPL_BIOGRAPHY_POST_TYPE ), apply_filters('empl_biography_cat_args', $empl_cat_args) );
}

// Action to register taxonomies
add_action( 'init', 'empl_biography_register_taxonomies');

/**
 * Function to update post message for employee post type
 * 
 * @since 1.0.5
 */
function empl_biography_post_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages[EMPL_BIOGRAPHY_POST_TYPE] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Employee updated. <a href="%s">View Employee</a>', 'employee-biography' ), esc_url( get_permalink( $post_ID ) ) ),
		2 => __( 'Custom field updated.', 'employee-biography' ),
		3 => __( 'Custom field deleted.', 'employee-biography' ),
		4 => __( 'Employee updated.', 'employee-biography' ),
		5 => isset( $_GET['revision'] ) ? sprintf( __( 'Employee restored to revision from %s', 'employee-biography' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Employee published. <a href="%s">View Employee</a>', 'employee-biography' ), esc_url( get_permalink( $post_ID ) ) ),
		7 => __( 'Employee saved.', 'employee-biography' ),
		8 => sprintf( __( 'Employee submitted. <a target="_blank" href="%s">Preview Employee</a>', 'employee-biography' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		9 => sprintf( __( 'Employee scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Employee</a>', 'employee-biography' ),
			date_i18n( 'M j, Y @ G:i', strtotime($post->post_date) ), esc_url(get_permalink($post_ID)) ),
		10 => sprintf( __( 'Employee draft updated. <a target="_blank" href="%s">Preview Employee</a>', 'employee-biography' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}

// Filter to update testimonial post message
add_filter( 'post_updated_messages', 'empl_biography_post_updated_messages' );