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
 * @var   array
 */
if ( ! defined( 'SITE_CONFIG' ) ) {
	define(
		'SITE_CONFIG',
		[
			'debug'       => true,
			'head'        => [

				/**
				 * Favicon (bookmark icon)
				 *
				 * No Bludit constants and no
				 * directory, only the file name
				 * and extension (e.g. favicon.png ).
				 *
				 * Place the icon in the Bludit
				 * root directory or in this theme's
				 * `assets/images` directory and the
				 * theme will find it.
				 *
				 * The theme looks first in the root
				 * directory so a file there will override
				 * a file in `assets/images`.
				 */
				'favicon'  => 'favicon.gif',
				'keywords' => []
			],
			'toolbar'     => true,
			'blog_in_nav' => true,
			'home_in_nav' => true,

			// No Bludit constants, only dir/file.
			'cover_image' => 'assets/images/cover.jpg',
			'aside'       => [
				'no_sidebar'     => false,
				'sidebar_bottom' => false
			],

			// Options: `list` & `grid`.
			'posts_loop'  => 'list',

			// Options: `prev_next` & `numerical`.
			'pagination'  => 'numerical',
			'byline'      => true,
			'post_date'   => true,
			'read_time'   => true,
			'copyright'   => true
		]
	);
}

// Set debug mode.
if ( SITE_CONFIG['debug'] ) {
	define( 'DEBUG_MODE', true );
	ini_set( 'display_errors', 1 );
}
