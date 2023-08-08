<?php
/**
 * Plugin generic functions file
 *
 * @package Employee Biography Plugin
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Escape Tags & Slashes
 *
 * Handles escapping the slashes and tags
 *
 * @since 1.0
 */
function empl_biography_esc_attr($data) {
	return esc_attr( stripslashes($data) );
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 1.0
 */
function empl_biography_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'empl_biography_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash($data);
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 1.0
 */
function empl_biography_clean_number( $var, $fallback = null ) {
	$data = absint( $var );
	return ( empty($data) && $fallback ) ? $fallback : $data;
}

/**
 * Sanitize URL
 * 
 * @since 1.0
 */
function empl_biography_clean_url( $url ) {
	return esc_url_raw( trim($url) );
}

/**
 * Sanitize text area value
 *
 * @since 1.0
 */
function empl_biography_clean_html($data = array(), $flag = false) {

	if($flag != true) {
		$data = empl_biography_nohtml_kses($data);
	}
	$data = stripslashes_deep($data);
	return $data;
}
