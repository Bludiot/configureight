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

// Get the theme configuration file.
require_once( THEME_DIR_PHP . 'utility/config.php' );

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

/**
 * Body classes
 *
 * For the class attribute on the `<body>` element.
 *
 * @since  1.0.0
 * @global object $site Site class.
 * @global object $url  Url class.
 * @global object $page Page class.
 * @return string Returns a string of classes.
 */
function body_classes() {

	// Get global variables.
	global $site, $url, $page;

	// Set up classes.
	$classes = [];

	// Language direction.
	if ( is_rtl() ) {
		$classes[] = 'rtl';
	} else {
		$classes[] = 'ltr';
	}

	// User logged in/out.
	if ( user_logged_in() ) {
		$classes[] = 'user-logged-in';
	} else {
		$classes[] = 'user-logged-out';
	}

	// Home page.
	if ( 'home' == $url->whereAmI() ) {
		$classes[] = 'home';

		// If home is not static.
		if ( ! $site->homepage() ) {
			$classes[] = 'blog';
		}
	}

	// If blog, not home.
	if ( 'blog' == $url->whereAmI() ) {
		$classes[] = 'blog';
	}

	// If singular content.
	if ( 'page' == $url->whereAmI() ) {

		// If static content.
		if ( $page->isStatic() ) {
			$classes[] = 'page';

		// If not static content.
		} else {
			$classes[] = 'post';
		}
	}

	// Return a string of space-separated classes.
	return implode( ' ', $classes );
}

/**
 * Get user toolbar
 *
 * Includes the toolbar file if user is
 * logged in and config value is true.
 *
 * @since  1.0.0
 * @global object $L Language class
 * @return mixed Returns the toolbar markup or null.
 */
function get_toolbar() {

	// Get any globals used in the toolbar template.
	global $L;

	if ( user_logged_in() ) {
		ob_start();
		include( THEME_DIR_PHP . 'utility/toolbar.php' );
		return ob_get_clean();
	}
	return null;
}

/**
 * Print user toolbar
 *
 * Echoes `get_toolbar()`.
 *
 * @since  1.0.0
 * @return void
 */
function user_toolbar() {
	echo get_toolbar();
}

/**
 * Print site logo
 *
 * @since  1.0.0
 * @global $site
 * @return mixed Returns null if no logo set.
 */
function site_logo() {

	global $site;

	if ( empty( $site->logo() ) ) {
		return null;
	}

	?>
	<div class="site-logo">
		<figure>
			<a href="<?php echo $site->url(); ?>">
				<img src="<?php echo $site->logo(); ?>" alt="<?php echo $site->title(); ?>" width="80">
			</a>
			<figcaption class="screen-reader-text"><?php echo $site->title(); ?></figcaption>
		</figure>
	</div>
	<?php
}

/**
 * Page description
 *
 * Gets the page description or
 * an excerpt of the content.
 *
 * @since  1.0.0
 * @global object $page Page class
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
 * Get page author
 *
 * @since  1.0.0
 * @global $page
 * @return string
 */
function get_author() {

	global $page;

	$user   = $page->username();
	$author = new \User( $user );

	if ( $author->nickname() ) {
		$name = $author->nickname();
	} elseif ( $author->firstName() && $author->lastName() ) {
		$name = sprintf(
			'%s %s',
			$author->firstName(),
			$author->lastName()
		);
	} elseif ( $author->firstName() ) {
		$name = $author->firstName();
	} else {
		$name = ucwords( str_replace( [ '-', '_', '.' ], ' ', $user ) );
	}

	return $name;
}

/**
 * Pint footer scripts
 *
 * @since  1.0.0
 * @return void
 */
function footer_scripts() {

	$suffix = asset_min();

	echo \Theme :: js( "js/theme{$suffix}.js" );
}
