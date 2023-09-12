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
	die( $L->get( 'direct-access' ) );
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
			'head'        => [
				'favicon'  => 'favicon.gif',
				'keywords' => [
					'one',
					'two',
					'three'
				]
			],
			'toolbar'     => true,
			'blog_in_nav' => true,
			'home_in_nav' => true,
			'aside'       => [
				'no_sidebar'     => false,
				'sidebar_bottom' => false
			],

			// Options: `list` & `grid`.
			'posts_loop'  => 'list',
			'byline'      => true,
			'post_date'   => true,
			'read_time'   => true,
			'copyright'   => true
		]
	);
}

// Set debug mode.
if ( BSB_CONFIG['debug'] ) {
	define( 'DEBUG_MODE', true );
	ini_set( 'display_errors', 1 );
}
