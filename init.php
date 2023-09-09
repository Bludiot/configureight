<?php
/**
 * Initialize theme
 *
 * @package    BS Bludit
 * @subpackage Functions
 * @since      1.0.0
 */

namespace BSB_Init;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed to access this file.' );
}

/**
 * Constant: Minimum PHP version
 *
 * @since 1.0.0
 */
define( 'BSB_MIN_PHP_VERSION', '7.4' );

/**
 * Minimum PHP version
 *
 * Checks the PHP version running on the current host
 * against the minimum version required by this theme.
 *
 * @since  1.0.0
 * @return boolean Returns false if the minimum is not met.
 */
function min_php_version() {

	if ( version_compare( phpversion(), BSB_MIN_PHP_VERSION, '<' ) ) {
		return false;
	}
	return true;
}

// Die if PHP minimum is not met.
if ( ! min_php_version() ) {

	$die = sprintf(
		$L->get( 'Minimum PHP version of %s is not met.' ),
		BSB_MIN_PHP_VERSION
	);
	die( $die );
}

// Get the theme configuration file.
require_once( THEME_DIR . 'includes/config.php' );

/**
 * User logged in
 *
 * @since  1.0.0
 * @return boolean Returns true if the current user is logged in.
 */
function user_logged_in() {

	$login = new \Login();

	if ( $login->isLogged() ) {
		return true;
	}
	return false;
}

/**
 * Is RTL language
 *
 * @since  1.0.0
 * @return boolean Returns true if site is in RTL language.
 */
function is_rtl() {

	$rtl = [
		'ar',
		'fa',
		'he',
		'ks',
		'ku',
		'pa',
		'ps',
		'sd',
		'ug',
		'ur'
	];
	$lang = \Theme :: lang();

	if ( in_array( $lang, $rtl ) ) {
		return true;
	}
	return false;
}

/**
 * Is blog page
 *
 * Whether the current page is displaying
 * a blog posts loop.
 *
 * @since  1.0.0
 * @global object $page
 * @global object $site
 * @global object $url
 * @return boolean Returns true if in a blog loop.
 */
function is_blog_page() {

	global $page, $site, $url;

	$blog_page = false;

	if ( 'page' == $url->whereAmI() && $page->slug() == str_replace( '/', '', $site->getField( 'uriBlog' ) ) ) {
		$blog_page = true;
	} elseif ( 'home' == $url->whereAmI() && 'page' != $url->whereAmI() ) {
		$blog_page = true;
	}
	return $blog_page;
}

/**
 * Asset file suffix
 *
 * Gets minified file if not in debug mode.
 * Third party (e.g. jQuery) may be exempted.
 *
 * @since  1.0.0
 * @return string Returns an empty string or
 *                `.min` string.
 */
function asset_min() {

	// Get non-minified file if in debug mode.
	if (
		( defined( 'DEBUG_MODE' ) && DEBUG_MODE ) ||
		( defined( 'BSB_CONFIG' ) && BSB_CONFIG['debug'] )
	) {
		return '';
	}
	return '.min';
}

require_once( THEME_DIR . 'includes/template-tags.php' );
