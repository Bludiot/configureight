<?php
/**
 * Template tags
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Functions
 * @since      1.0.0
 */

namespace BSB_Tags;

// Import namespaced functions.
use function BSB_Init\{
	is_rtl,
	user_logged_in,
	asset_min
};
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
	global $page, $site, $url;

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
 * Site Schema
 *
 * Conditional Schema attributes for `<div id="page"`.
 *
 * @since  1.0.0
 * @return string Returns the relevant itemtype.
 */
function site_schema() {

	global $page, $site, $url;

	// Change page slugs and template names as needed.
	if ( 'profile' == $page->template() ) {
		$itemtype = 'ProfilePage';
	} elseif ( 'about' == $page->slug() || 'about-us' == $page->slug() || 'about' == $page->template() ) {
		$itemtype = 'AboutPage';
	} elseif ( 'contact' == $page->slug() || 'contact-us' == $page->slug() || 'contact' == $page->template() ) {
		$itemtype = 'ContactPage';
	} elseif ( 'faq' == $page->slug() || 'faqs' == $page->slug() || 'faq' == $page->template() ) {
		$itemtype = 'QAPage';
	} elseif ( 'cart' == $page->slug() || 'shopping-cart' == $page->slug() || 'cart' == $page->template() || 'checkout' == $page->template() ) {
		$itemtype = 'CheckoutPage';
	} elseif ( 'blog' == $url->whereAmI() || ( 'home' == $url->whereAmI() && ! $site->homepage() ) ) {
		$itemtype = 'Blog';
	// } elseif ( 'search' == $url->whereAmI() ) {
		// $itemtype = 'SearchResultsPage';
	} else {
		$itemtype = 'WebPage';
	}
	echo $itemtype;
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

	echo \Theme :: js( "assets/js/theme{$suffix}.js" );
}
