<?php

/**
 * Plugin Name: Auto Version
 * Plugin URI: https://github.com/artcomventure/wordpress-plugin-autoVersion
 * Description: Auto-versioning CSS and JavaScript files in WordPress.
 * Version: 1.0.0
 * Text Domain: autoversion
 * Author: artcom venture GmbH
 * Author URI: http://www.artcom-venture.de/
 */

if ( ! defined( 'AUTOVERSION_PLUGIN_FILE' ) ) {
	define( 'AUTOVERSION_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'AUTOVERSION_PLUGIN_URL' ) ) {
	define( 'AUTOVERSION_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'AUTOVERSION_PLUGIN_DIR' ) ) {
	define( 'AUTOVERSION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * t9n.
 */
add_action( 'after_setup_theme', 'autoversion__after_setup_theme' );
function autoversion__after_setup_theme() {
	load_theme_textdomain( 'autoversion', AUTOVERSION_PLUGIN_DIR . 'languages' );
}

/**
 * Auto version number for scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'autoversion_scripts', 19820511 );
function autoversion_scripts() {
	$home_path = ( function_exists( 'get_home_path' ) ? get_home_path() : $_SERVER['DOCUMENT_ROOT'] );
	$home_url  = preg_quote( get_home_url(), '/' );

	$settings = autoversion_get_settings();

	// get all css and js files
	foreach ( array( 'css' => wp_styles(), 'js' => wp_scripts() ) as $type => $files ) {
		// disabled
		if ( !$settings[$type]['status'] ) continue;

		// loop through files
		foreach ( $files->registered as $file ) {
			// no version at all
			if ( is_null( $file->ver ) ) continue;
			// no file source OR is no local file
			if ( ! is_string( $file->src ) || ! preg_match( '/^(\/|' . $home_url . ')/', $file->src ) ) continue;
			// don't override existing version numbers
			if ( $settings[$type]['status'] == 1 && is_string( $file->ver ) ) continue;

			// change version number
			// to entered version _number_ or to file's modification timestamp
			$file->ver = $settings[$type]['ver'] === '' ? @filemtime( $home_path . $file->src ) : $settings[$type]['ver'];
		}
	}
}

/**
 * Includes.
 */

include( AUTOVERSION_PLUGIN_DIR . 'inc/settings.php' );
