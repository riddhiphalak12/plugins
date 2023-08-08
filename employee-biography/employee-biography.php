<?php
/**
 * Plugin Name: Employee Biography Plugin
 * Plugin URI: https://profiles.wordpress.org/ridhimashukla/
 * Description: Custom employee biography shortcode
 * Version: 1.0.0
 * Requires at least: 5.2
 * Tested up to: 6.2.2
 * Requires PHP: 5.6.20
 * Author: Riddhi Phalak
 * Author URI: https://profiles.wordpress.org/ridhimashukla/
 * Update URI: 
 * Text Domain: employee-biography
 *
 * @package WordPress
 * @author Riddhi Phalak
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Basic plugin definations
 * 
 * @since 1.0
 */
if( !defined( 'EMPL_BIOGRAPHY_VERSION' ) ) {
	define( 'EMPL_BIOGRAPHY_VERSION', '1.2' ); // Version of plugin
}
if( !defined( 'EMPL_BIOGRAPHY_DIR' ) ) {
	define( 'EMPL_BIOGRAPHY_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'EMPL_BIOGRAPHY_URL' ) ) {
	define( 'EMPL_BIOGRAPHY_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'EMPL_BIOGRAPHY_POST_TYPE' ) ) {
	define( 'EMPL_BIOGRAPHY_POST_TYPE', 'employee' ); // Plugin url
}
if( !defined( 'EMPL_BIOGRAPHY_META_PREFIX' ) ) {
	define( 'EMPL_BIOGRAPHY_META_PREFIX', '_empl_' ); // Plugin url
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0
 */
function empl_biography_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$empl_biography_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$empl_biography_lang_dir = apply_filters( 'empl_biography_languages_directory', $empl_biography_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'employee-biography' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'employee-biography', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( EMPL_BIOGRAPHY_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'employee-biography', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'employee-biography', false, $empl_biography_lang_dir );
	}
}

/**
 * Plugins Loaded
 * This gets the plugin loaded functions
 * 
 * @since 1.0
 */
function empl_biography_plugin_loaded() {

	empl_biography_load_textdomain();
}
add_action('plugins_loaded', 'empl_biography_plugin_loaded');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @since 1.0
 */
register_activation_hook( __FILE__, 'empl_biography_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @since 1.0
 */
register_deactivation_hook( __FILE__, 'empl_biography_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * set default values for the plugin options.
 * 
 * @since 1.0
 */
function empl_biography_install() {

	// Custom post type and taxonomy function
	empl_biography_register_post_type();
	empl_biography_register_taxonomies();

	// Need to call when custom post type is being used in plugin
	flush_rewrite_rules();
}

/**
 * Check required plugin is active or not.
 *
 * @since 1.0
 */
function empl_biography_activation_check(){
	
	// Check if ACF plugin is active
    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        // ACF is not active, so you can deactivate your plugin
        if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			// deactivate the plugin
			deactivate_plugins( plugin_basename( __FILE__ ) );
			// unset activation notice
			unset( $_GET[ 'activate' ] );
			// display notice
			add_action( 'admin_notices', 'empl_biography_admin_notices' );
		}
    }
}

add_action( 'admin_init', 'empl_biography_activation_check' );


/**
 * Admin notices
 * 
 * @since 1.0
 */
function empl_biography_admin_notices() {

	// Check if ACF plugin is active
    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
		echo '<div class="error notice is-dismissible">';
		echo '<p><strong>Employee Biography</strong> '.__('recommends Advance Custom Field plugin to use.', 'employee-biography').'</p>';
		echo '</div>';
	}
}

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete plugin options.
 * 
 * @since 1.0
 */
function empl_biography_uninstall() {

	// Need to call when custom post type is being used in plugin
	flush_rewrite_rules();
}

// Functions file
require_once( EMPL_BIOGRAPHY_DIR . '/includes/empl-biography-functions.php' );

// Script Class
require_once( EMPL_BIOGRAPHY_DIR . '/includes/class-empl-biography-script.php' );

// Public Class
require_once( EMPL_BIOGRAPHY_DIR . '/includes/class-empl-biography-public.php' );

// Post type
require_once( EMPL_BIOGRAPHY_DIR . '/includes/empl-biography-post-type.php' );

// Shortcode File
require_once( EMPL_BIOGRAPHY_DIR . '/includes/shortcode/empl-biography-display.php' );

// Load admin files
if ( is_admin() ) {
	
	// Admin Class
	require_once( EMPL_BIOGRAPHY_DIR . '/includes/admin/class-empl-biography-admin.php' );
}