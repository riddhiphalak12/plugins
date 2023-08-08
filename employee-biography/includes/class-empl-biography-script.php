<?php
/**
 * Script Class 
 *
 * Handles the script and style functionality of plugin
 *
 * @package Employee Biography Plugin
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Empl_Biography_Script {

	function __construct() {

		// Action to add style and script in backend
		add_action( 'wp_enqueue_scripts', array( $this, 'empl_biography_register_assets' ) );

	}

	/**
	 * Function to register scripts and styles
	 * 
	 * @since 1.0
	 */
	function empl_biography_register_assets( $hook ) {

		// Registring public css
		wp_register_style( 'empl-public-css', EMPL_BIOGRAPHY_URL.'assets/css/empl-public.css', array(), EMPL_BIOGRAPHY_VERSION );
	}
}

$empl_biography_script = new Empl_Biography_Script();