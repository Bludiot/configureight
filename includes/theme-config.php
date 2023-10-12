<?php
/**
 * Theme configuration
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

namespace CFE_Theme;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

/**
 * Require theme plugin
 *
 * @todo Link to website or direct to plugin README.
 *
 * @since  1.0.0
 */
$plugin_notice  = '<!DOCTYPE html><html><body>';
$plugin_notice .= '<h1>Plugin Required</h1>';
$plugin_notice .= '<p>The Configure 8 theme requires the Configure 8 plugin to be active. If it is not installed, please read the instructions at <a href="https://github.com/ControlledChaos/configureight-plugin" target="_blank" rel="noopener noreferrer">https://github.com/ControlledChaos/configureight-plugin</a>.</p>';
$plugin_notice .= '</body></html>';

if ( ! getPlugin( $site->theme() ) ) {
	exit( $plugin_notice );
}

/**
 * Get configuration file
 *
 * Looks first in the Bludit root directory
 * for `theme-config.json`, then in this theme's
 * `includes` directory for `theme-config.json`.
 *
 * This is a starter theme and you may create
 * more than one theme from this boilerplate.
 * For this reason the ability to access a common
 * configuration file is provided to you.
 *
 * To add the configuration file to the root,
 * simply copy the `theme-config.json` file in
 * the `includes` directory and paste into the
 * root Bludit directory where you find the
 * content, kernel, plugins, themes directories.
 */
if ( file_exists( PATH_ROOT . 'theme-config.json' ) ) {
	$get_json = file_get_contents( PATH_ROOT . 'theme-config.json' );
} else {
	$get_json = file_get_contents( THEME_DIR . 'includes/theme-config.json' );
}

// Convert JSON to PHP array.
$theme_config = json_decode( $get_json, true );

/**
 * Configuration constant
 *
 * @since 1.0.0
 * @var   array
 */
if ( ! defined( 'THEME_CONFIG' ) ) {
	define( 'THEME_CONFIG', $theme_config );
}

// Set debug mode.
if ( THEME_CONFIG['debug'] ) {
	ini_set( 'html_errors', 1 );
	ini_set( 'display_errors', 1 );
	error_reporting( E_ERROR | E_WARNING | E_PARSE | E_NOTICE );
}
