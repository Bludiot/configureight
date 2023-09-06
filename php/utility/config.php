<?php
/**
 * Theme configuration
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

/**
 * Configuration constant
 *
 * @since 1.0.0
 */
define(
	'BSB_CONFIG',
	[
		'debug'   => true,
		'toolbar' => true
	]
);

// Set debug mode.
if ( BSB_CONFIG['debug'] ) {
	define( 'DEBUG_MODE', true );
}
