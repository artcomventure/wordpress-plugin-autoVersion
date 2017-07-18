<?php

/**
 * Register settings options.
 */
add_action( 'admin_init', 'autoversion__admin_init' );
function autoversion__admin_init() {
	register_setting( 'autoversion', 'autoversion' );
}

/**
 * Register auto version's admin page.
 */
add_action( 'admin_menu', 'autoversion__admin_menu' );
function autoversion__admin_menu() {
	add_options_page( __( 'Auto Version', 'autoversion' ), __( 'Auto Version', 'autoversion' ), 'manage_options', 'autoversion-settings', 'autoversion_settings_page' );
}

/**
 * Settings page markup.
 */
function autoversion_settings_page() {
	wp_enqueue_script( 'autoversion-settings', AUTOVERSION_PLUGIN_URL . 'js/settings.min.js', array(), '20170718' );
	include( AUTOVERSION_PLUGIN_DIR . 'inc/settings.form.php' );
}

/**
 * Retrieve (default) settings.
 * @return array
 */
function autoversion_get_settings() {
	$settings = get_option( 'autoversion', array() ) + array( 'css' => array(), 'js' => array() );
	$settings['css'] += array( 'status' => 0, 'ver' => '' );
	$settings['js'] += array( 'status' => 0, 'ver' => '' );

	return $settings;
}
