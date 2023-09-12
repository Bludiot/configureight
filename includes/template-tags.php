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

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( $L->get( 'direct-access' ) );
}

// Import namespaced functions.
use function BSB_Init\{
	is_rtl,
	user_logged_in,
	favicon_exists,
	asset_min
};

/**
 * Favicon tag
 *
 * Returns the site icon meta tag.
 *
 * @since  1.0.0
 * @return mixed Returns the icon tag or null.
 */
function favicon_tag() {

	if ( favicon_exists() ) {

		// Get the image file extension.
		$info = pathinfo( THEME_DIR . 'assets/images/' . BSB_CONFIG['head']['favicon'] );
		$type = $info['extension'];

		return sprintf(
			'<link rel="icon" href="%s" type="image/%s">',
			DOMAIN_THEME . 'assets/images/' . BSB_CONFIG['head']['favicon'],
			$type
		);
	}
	return null;
}

/**
 * Body classes
 *
 * For the class attribute on the `<body>` element.
 *
 * @since  1.0.0
 * @global object $page Page class.
 * @global object $site Site class.
 * @global object $url Url class.
 * @return string Returns a string of classes.
 */
function body_classes() {

	// Access global variables.
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

	// User toolbar.
	if ( user_toolbar() ) {
		$classes[] = 'toolbar-active';
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

	// Page templates.
	if (
		'search' != $url->whereAmI() &&
		! empty( $page->template() ) &&
		! ctype_space( $page->template() )
	) {
		$templates = explode( ' ', $page->template() );

		foreach ( $templates as $template ) {
			$classes[] = "template-{$template}";
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
 * @global object $page Page class.
 * @global object $site Site class.
 * @global object $url Url class.
 * @return string Returns the relevant itemtype.
 */
function site_schema() {

	// Access global variables.
	global $page, $site, $url;

	if ( 'search' == $url->whereAmI() ) {
			echo 'SearchResultsPage';
			return;
	}

	// Change page slugs and template names as needed.
	if ( str_contains( $page->template(), 'profile' ) ) {
		$itemtype = 'ProfilePage';

	} elseif (
		'about'    == $page->slug() ||
		'about-us' == $page->slug() ||
		str_contains( $page->template(), 'about' )
	) {
		$itemtype = 'AboutPage';

	} elseif (
		'contact'    == $page->slug() ||
		'contact-us' == $page->slug() ||
		str_contains( $page->template(), 'contact' )
	) {
		$itemtype = 'ContactPage';

	} elseif (
		'faq'  == $page->slug() ||
		'faqs' == $page->slug() ||
		str_contains( $page->template(), 'faq' )
	) {
		$itemtype = 'QAPage';

	} elseif (
		'cart'          == $page->slug() ||
		'shopping-cart' == $page->slug() ||
		str_contains( $page->template(), 'cart' ) ||
		str_contains( $page->template(), 'checkout' )
	) {
		$itemtype = 'CheckoutPage';

	} elseif (
		'blog'   == $url->whereAmI() ||
		( 'home' == $url->whereAmI() && ! $site->homepage() )
	) {
		$itemtype = 'Blog';

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
 * @global object $page Page class
 * @global object $url Url class
 * @return mixed Returns the toolbar markup or null.
 */
function get_toolbar() {

	// Access global variables.
	global $L, $page, $url;

	if ( user_logged_in() ) {
		ob_start();
		include( THEME_DIR . 'templates/utility/toolbar.php' );
		return ob_get_clean();
	}
	return null;
}

/**
 * Print user toolbar
 *
 * @since  1.0.0
 * @return mixed Returns the `get_toolbar()` function or false.
 */
function user_toolbar() {
	if ( user_logged_in() && BSB_CONFIG['toolbar'] ) {
		return get_toolbar();
	}
	return false;
}

/**
 * Print site logo
 *
 * @since  1.0.0
 * @global object $site Site class
 * @return mixed Returns null if no logo set.
 */
function site_logo() {

	// Access global variables.
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
 * Content template
 *
 * @since  1.0.0
 * @global object $page Page class
 * @global object $site Site class
 * @global object $url Url class
 * @return string Returns the relevant template.
 */
function content_template() {

	// Access global variables.
	global $page, $site, $url;

	// Blog template when a static home page is used.
	if ( 'page' == $url->whereAmI() && $page->slug() == str_replace( '/', '', $site->getField( 'uriBlog' ) ) ) {
		if ( 'grid' == BSB_CONFIG['posts_loop'] ) {
			$template = 'templates/content/posts-grid.php';
		} else {
			$template = 'templates/content/posts.php';
		}

	// Page templates.
	} elseif ( 'page' == $url->whereAmI() ) {

		// Static home page.
		if ( $site->getField( 'homepage' ) && $page->slug() == $site->getField( 'homepage' ) ) {
			$template = 'templates/content/front-page.php';

		// Page with template applied, excluding `no-sidebar` template.
		} elseif ( $page->template() ) {
			$template = 'templates/content/' . str_replace( [ ' ', 'no-sidebar', 'sidebar-bottom' ], '', $page->template() ) . '.php';

		// Static page.
		} elseif ( $page->isStatic() ) {
			$template = 'templates/content/page.php';

		// Sticky page (post).
		} elseif ( $page->sticky() ) {
			$template = 'templates/content/sticky.php';

		// Default (post) page.
		} else {
			$template = 'templates/content/post.php';
		}

	// Default to posts loop.
	} else {
		if ( 'grid' == BSB_CONFIG['posts_loop'] ) {
			$template = 'templates/content/posts-grid.php';
		} else {
			$template = 'templates/content/posts.php';
		}
	}
	return $template;
}

/**
 * Sticky icon
 *
 * @since  1.0.0
 * @param  boolean $echo Whether to echo or return the icon.
 * @param  string $class Add classes to the icon markup.
 * @param  string $title Text for the title attribute.
 * @global object $page Page class
 * @return mixed Echoes the icon, or returns the icon or empty.
 */
function sticky_icon( $echo = '', $class = '', $title = '' ) {

	// Access global variables.
	global $L, $page;

	$icon = '';
	if ( $page->sticky() ) {
		$icon = sprintf(
			'<span class="theme-icon sticky-icon %s" title="%s" role="img">%s</span><span class="screen-reader-text">%s </span>',
			$class,
			$title,
			'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M160 456c0 1.25 .2813 2.467 .8438 3.576l24 47.1c2.938 5.891 11.38 5.906 14.31 0l24-47.1C223.6 458.7 224 457 224 456V383.1H160V456zM298 214.3l-12.25-118.3H328c13.25 0 24-10.75 24-23.1V23.1C352 10.75 341.3 0 328 0h-272C42.75 0 32 10.75 32 23.1v47.1C32 85.25 42.75 95.1 56 95.1h42.22L85.97 214.3C37.47 236.8 0 277.3 0 327.1c0 13.25 10.75 23.1 24 23.1h336c13.25 0 24-10.75 24-23.1C384 276.8 346 236.6 298 214.3z"/></svg>',
			$L->get( 'Sticky Post:' )
		);
	}

	// Echo or return the icon
	if ( $echo == 'true' ) {
		echo $icon;
	} else {
		return $icon;
	}
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

	// Access global variables.
	global $content, $page;

	if ( $page->description() ) {
		$page_desc = $page->description();
	} else {
		$page_desc  = substr( strip_tags( $page->content() ), 0, 85 );
		if ( ! empty( $page->content() ) && ! ctype_space( $page->content() ) ) {
			$page_desc .= '&hellip;';
		}
	}
	return $page_desc;
}

/**
 * Page has tags
 *
 * Whether a page has tags attached.
 *
 * @since  1.0.0
 * @global object $page Page class
 * @return boolean Returns true if tags are attached.
 */
function has_tags() {

	// Access global variables.
	global $page;

	if ( $page->tags( true ) ) {
		return true;
	}
	return false;
}

/**
 * Get page author
 *
 * @since  1.0.0
 * @global object $page Page class
 * @return string
 */
function get_author() {

	// Access global variables.
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

	echo \Theme :: js( "assets/js/jquery.fitvids{$suffix}.js" );
	echo \Theme :: js( "assets/js/theme{$suffix}.js" );
}
