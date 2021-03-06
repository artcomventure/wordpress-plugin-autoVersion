<?php

/**
 * Plugin Name: Auto Version
 * Plugin URI: https://github.com/artcomventure/wordpress-plugin-autoVersion
 * Description: Auto-Versioning CSS and JavaScript files
 * Version: 1.1.0
 * Text Domain: autoversion
 * Author: artcom venture GmbH
 * Author URI: http://www.artcom-venture.de/
 */

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

	$wp_plugin_dir = WP_PLUGIN_DIR;
	$wp_theme_dir = dirname( TEMPLATEPATH );

	$settings = autoversion_get_settings();

	// get all css and js files
	foreach ( array( 'css' => wp_styles(), 'js' => wp_scripts() ) as $filetype => $files ) {
		// disabled
		if ( !$settings[$filetype]['status'] ) continue;

		// loop through files
		foreach ( $files->registered as $data ) {
			// no version at all
			if ( is_null( $data->ver ) ) continue;
			// no file source OR is no local file
			if ( ! is_string( $data->src ) || ! preg_match( '/^(\/|' . $home_url . ')/', $data->src ) ) continue;
			// don't override existing version numbers
			if ( $settings[$filetype]['status'] == 1 && is_string( $data->ver ) ) continue;

			// relative path from root
			$file_src = $home_path . preg_replace( '/^' . $home_url . '/', '', $data->src );

			// loop through files to ignore
			foreach ( $settings['ignore'] as $type => $list ) {
				foreach ( array_filter( $list ) as $file => $ignore ) {
					if ( ( $pattern = dirname( $file ) ) == '.' ) $pattern = $file;

					if ( preg_match( '#^' . ( $type == 'plugins' ? $wp_plugin_dir : ( $type == 'themes' ? $wp_theme_dir : $home_path ) ) . '/(' . $pattern . ')#', $file_src ) ) {
						$file_src = false;
						continue;
					}
				}

				if ( !$file_src ) continue;
			}

			if ( !$file_src ) continue;

			$version = $data->ver;

			// change version number
			// to entered version _number_ or to file's modification timestamp
			$data->ver = $settings[$filetype]['ver'] === ''
				? @filemtime( $file_src )
				: $settings[$filetype]['ver'];

			// revert
			if ( is_null( $data->ver ) ) $data->ver = $version;
		}
	}
}

/**
 * Remove update notification (since this plugin isn't listed on https://wordpress.org/plugins/).
 */
add_filter( 'site_transient_update_plugins', 'autoversion__site_transient_update_plugins' );
function autoversion__site_transient_update_plugins( $value ) {
	$plugin_file = plugin_basename( __FILE__ );

	if ( isset( $value->response[ $plugin_file ] ) ) {
		unset( $value->response[ $plugin_file ] );
	}

	return $value;
}

/**
 * Change details link to GitHub repository.
 */
add_filter( 'plugin_row_meta', 'autoversion__plugin_row_meta', 10, 2 );
function autoversion__plugin_row_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $file );

		$links[2] = '<a href="' . $plugin_data['PluginURI'] . '">' . __( 'Visit plugin site' ) . '</a>';

		$links[] = '<a href="' . admin_url( 'options-general.php?page=autoversion-settings' ) . '">' . __( 'Settings' ) . '</a>';
	}

	return $links;
}

/**
 * Includes.
 */

include( AUTOVERSION_PLUGIN_DIR . 'inc/settings.php' );

/**
 * Delete traces on deactivation.
 */
register_deactivation_hook( __FILE__, 'autoversion_deactivate' );
function autoversion_deactivate() {
	delete_option( 'autoversion' );
}
