<?php
/**
 * Helper functions
 *
 * @package    BS Bludit
 * @subpackage Includes
 * @category   Functions
 * @since      1.0.0
 */

namespace BSB_Func;

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
 * Text replace
 *
 * Replaces the `%replace%` variable in
 * a language file string.
 *
 * @param  string $get The language string to get.
 * @param  string $string The string to replace the variable.
 * @global object $L Language class
 * @return string Returns the modified string or the string
 *                as is if the variable is not found.
 */
function text_replace( $get = '', $string = '' ) {

	// Access global variables.
	global $L;

	if ( strstr( $L->get( $get ), '%replace%' ) ) {
		return str_replace( '%replace%', $string, $L->get( $get ) );
	}
	return $L->get( $get );
}

/**
 * Favicon exists
 *
 * Checks the theme config file to
 * find the icon file.
 *
 * @since  1.0.0
 * @return boolean Returns true if the icon file is found.
 */
function favicon_exists() {

	// Look for icons in the CMS root directory.
	$favicon_png = PATH_ROOT . 'favicon.png';
	$favicon_gif = PATH_ROOT . 'favicon.gif';
	$favicon_ico = PATH_ROOT . 'favicon.ico';

	$root_favicon = false;
	if ( file_exists( $favicon_png ) ) {
		$root_favicon = true;
	} elseif ( file_exists( $favicon_gif ) ) {
		$root_favicon = true;
	} elseif ( file_exists( $favicon_ico ) ) {
		$root_favicon = true;
	}

	// Return true if an icon in the root.
	if ( $root_favicon ) {
		return true;
	}

	/**
	 * Look for icon the the theme's image directory
	 * as set in the config file.
	 */
	if (
		is_array( BSB_CONFIG['head'] ) &&
		array_key_exists( 'favicon', BSB_CONFIG['head'] ) &&
		! empty( BSB_CONFIG['head']['favicon'] )
	) {
		$favicon = THEME_DIR . 'assets/images/' . BSB_CONFIG['head']['favicon'];
	}

	// Return true if an icon in the root.
	if ( file_exists( $favicon ) ) {
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
 * @global object $page Page class
 * @global object $site Site class
 * @global object $url Url class
 * @return boolean Returns true if in a blog loop.
 */
function is_blog_page() {

	// Access global variables.
	global $page, $site, $url;

	$blog_page = false;

	if ( 'blog' == $url->whereAmI() ) {
		$blog_page = true;
	}
	return $blog_page;
}

/**
 * Blog URL
 *
 * @since  1.0.0
 * @global object $site SIte class
 * @return string Returns the URL of the blog page(s).
 */
function blog_url() {

	// Access global variables.
	global $site;

	$site_url = $site->getField( 'url' );
	$blog_uri = $site->getField( 'uriBlog' );
	$blog_url = $site_url;

	if ( ! empty( $blog_uri ) ) {
		$blog_url = sprintf(
			'%s%s/',
			$site_url,
			str_replace( '/', '', $blog_uri )
		);
	}
	return $blog_url;
}

/**
 * Blog data
 *
 * Gets data for the blog, especially when
 * using a static front page.
 *
 * @since  1.0.0
 * @global array  $content array of site content
 * @global object $L Language class
 * @global object $page Page class
 * @global object $site Site class
 * @global object $url Url class
 * @return array  Returns an array of blog data.
 */
function blog_data() {

	// Access global variables.
	global $content, $L, $page, $pages, $site, $url;

	// Null if in search results (global errors).
	if ( 'search' == $url->whereAmI() ) {
		return null;
	}

	$static_field = $site->getField( 'uriBlog' );
	$static_key   = str_replace( '/', '', $static_field );

	// Default blog description.
	$description = sprintf(
		'%s %s',
		$L->get( 'blog-data-description' ),
		$site->title()
	);

	// Default data array.
	$data = [
		'post_count'  => $pages->count(),
		'show_posts'  => $site->getField( 'itemsPerPage' ),
		'location'    => 'home',
		'key'         => false,
		'url'         => blog_url(),
		'slug'        => false,
		'template'    => false,
		'title'       => false,
		'description' => $description,
		'cover'       => false,
	];

	if ( empty( $static_field ) ) {
		return $data;

	} else {

		// Get data from the static blog page.
		$blog_page = buildPage( $static_key );

		// Description from the static blog page.
		if (
			! empty( $blog_page->description() ) ||
			! ctype_space( $blog_page->description() )
		) {
			$description = $blog_page->description();
		}

		$data['location']    = 'page';
		$data['key']         = $blog_page->key();
		$data['slug']        = $blog_page->slug();
		$data['template']    = $blog_page->template();
		$data['title']       = $blog_page->title();
		$data['description'] = $description;
		$data['cover']       = $blog_page->coverImage();
	}
	return $data;
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
