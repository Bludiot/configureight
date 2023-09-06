<?php
/**
 * Initialize theme
 *
 * @package    BS Bludit
 * @subpackage Functions
 * @since      1.0.0
 */

namespace BS_Init;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed to access this file.' );
}

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
 * Page description
 *
 * Gets the page description or
 * an excerpt of the content.
 *
 * @since  1.0.0
 * @global object $page
 * @return string Returns the description.
 */
function page_description() {

	global $page;

	if ( $page->description() ) {
		$page_desc = $page->description();
	} else {
		$page_desc  = substr( strip_tags( $page->content() ), 0, 85 );
		$page_desc .= '&hellip;';
	}
	return $page_desc;
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
 * Body classes
 *
 * For the class attribute on the `<body>` element.
 *
 * @since  1.0.0
 * @return string Returns a string of classes.
 */
function body_classes() {

	global $site, $url, $page;

	$classes = [];

	if ( is_rtl() ) {
		$classes[] = 'rtl';
	} else {
		$classes[] = 'ltr';
	}

	if ( user_logged_in() ) {
		$classes[] = 'user-logged-in';
	} else {
		$classes[] = 'user-logged-out';
	}

	if ( 'home' == $url->whereAmI() ) {
		$classes[] = 'home';

		if ( ! $site->homepage() ) {
			$classes[] = 'blog';
		}
	}

	if ( 'blog' == $url->whereAmI() ) {
		$classes[] = 'blog';
	}

	if ( 'page' == $url->whereAmI() ) {
		if ( $page->isStatic() ) {
			$classes[] = 'page';
		} else {
			$classes[] = 'post';
		}
	}

	return implode( ' ', $classes );
}
