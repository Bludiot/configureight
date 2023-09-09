<?php
/**
 * Theme configuration
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed to access this file.' );
}

/**
 * Configuration constant
 *
 * @since 1.0.0
 */
if ( ! defined( 'BSB_CONFIG' ) ) {
	define(
		'BSB_CONFIG',
		[
			'debug'       => true,
			'toolbar'     => true,
			'blog_in_nav' => true,
			'home_in_nav' => true,

			// Options: `list` & `grid`.
			'posts_loop'  => 'list',

			'byline'      => true,
			'post_date'   => true,
			'read_time'   => true
		]
	);
}

// Set debug mode.
if ( BSB_CONFIG['debug'] ) {
	define( 'DEBUG_MODE', true );
	ini_set( 'display_errors', 1 );
}
