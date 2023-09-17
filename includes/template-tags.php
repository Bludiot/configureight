<?php
/**
 * Template tags
 *
 * @package    BS Bludit
 * @subpackage Includes
 * @category   Templates
 * @since      1.0.0
 */

namespace BSB_Tags;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( $L->get( 'direct-access' ) );
}

// Import namespaced functions.
use function BSB_Func\{
	is_rtl,
	user_logged_in,
	text_replace,
	favicon_exists,
	blog_data,
	has_cover,
	full_cover,
	asset_min,
	numbers_to_text
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

		$favicon_png = PATH_ROOT . 'favicon.png';
		$favicon_gif = PATH_ROOT . 'favicon.gif';
		$favicon_ico = PATH_ROOT . 'favicon.ico';

		if ( file_exists( $favicon_png ) ) {
			$favicon = DOMAIN_BASE . 'favicon.png';
		} elseif ( file_exists( $favicon_gif ) ) {
			$favicon = DOMAIN_BASE . 'favicon.gif';
		} elseif ( file_exists( $favicon_ico ) ) {
			$favicon = DOMAIN_BASE . 'favicon.ico';
		} else {
			$favicon = DOMAIN_THEME . 'assets/images/' . THEME_CONFIG['head']['favicon'];
		}

		// Get the image file extension.
		$info = pathinfo( $favicon );
		$type = $info['extension'];

		return sprintf(
			'<link rel="icon" href="%s" type="image/%s">',
			$favicon,
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
		$classes[] = 'blog blog-not-home';

		// Get blog data.
		$blog_data = blog_data();

		// Templates for the static blog page.
		if ( $blog_data['template'] ) {
			$templates = explode( ' ', $blog_data['template'] );

			foreach ( $templates as $template ) {
				$classes[] = "template-{$template}";
			}
		}
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
		'page' == $url->whereAmI() &&
		! empty( $page->template() ) &&
		! ctype_space( $page->template() )
	) {
		if ( $page->template() ) {
			$templates = explode( ' ', $page->template() );

			foreach ( $templates as $template ) {

				// Exclude `full-cover` template if no cover image.
				if (
					! has_cover() &&
					str_contains( $page->template(), 'full-cover' )
				) {
					$classes[] = '';
				} else {
					$classes[] = "template-{$template}";
				}
			}
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
	echo 'https://schema.org/' . $itemtype;
}

/**
 * Page header
 *
 * Returns the page title and description
 *
 * @since  1.0.0
 * @global object $page Page class
 * @global object $site Site class
 * @global object $url Url class
 * @return string Returns the header markup.
 */
function page_header() {

	// Access global variables.
	global $L, $page, $site, $url;

	$wrapper     = 'header';
	$heading     = 'h1';
	$description = $page->description();
	$sticky_icon = '';
	$scroll_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M192 384c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L192 306.8l137.4-137.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-160 160C208.4 380.9 200.2 384 192 384z"/></svg>';

	/**
	 * Do not use `<header>` element for the
	 * `full-cover` page template because
	 * this will be used inside the site
	 * header; a `<header>` element must not
	 * contain another `<header>` element.
	 */
	if ( full_cover() ) {
		$wrapper = 'div';
	}

	// Site title is `h1` on front page; only one per page.
	if ( 'page' == $url->whereAmI() ) {
		if ( $page->key() == $site->getField( 'homepage' ) ) {
			$heading = 'h2';
		}
	}

	// If the page is sticky.
	if ( $page->sticky() ) {
		$sticky_icon = sticky_icon( 'false', 'sticky-icon-heading' ) . ' ';
	}

	$html = sprintf(
		'<%s class="page-header" data-page-header>',
		$wrapper
	);
	$html .= sprintf(
		'<%s class="page-title">%s%s</%s>',
		$heading,
		$sticky_icon,
		$page->title(),
		$heading
	);

	if ( ! empty( $description ) && ! ctype_space( $description ) ) {
		$html .= sprintf(
			'<p class="page-description page-description-single">%s</p>',
			$description
		);
	}

	if ( full_cover() ) {
		$html .= sprintf(
			'<a href="#content" class="button intro-scroll hide-if-no-js"><span class="screen-reader-text">%s</span>%s</a>',
			$L->get( 'Scroll to content' ),
			$scroll_icon
		);
	}
	$html .= "</{$wrapper}>";

	return $html;
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
		include( THEME_DIR . 'views/utility/toolbar.php' );
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
	if ( user_logged_in() && THEME_CONFIG['toolbar']['display'] ) {
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
	<div class="site-logo" data-site-logo>
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
 * Page ID
 *
 * Returns an ID based on the page type
 * and the page key.
 *
 * @since  1.0.0
 * @global object $page Page class
 * @global object $url Url class
 * @return mixed Returns the page ID or null.
 */
function page_id() {

	// Access global variables.
	global $page, $url;

	// Null if in search results (global errors).
	if ( 'search' == $url->whereAmI() ) {
		return null;
	}

	// Conditional page ID, static or not.
	$id = '';
	if (
		( 'blog' == $url->whereAmI() && 'home' != $url->whereAmI() ) ||
		( 'home' == $url->whereAmI() && 'page' != $url->whereAmI() )
	) {
		$id = 'blog-page';
		if ( ! isset( $_GET['page'] ) ) {
			$id .= '-' . 1;
		} else {
			$id .= '-' . $_GET['page'];
		}

	} elseif ( $page->isStatic() && 'blog' != $url->whereAmI() ) {
		$id = 'page-' . $page->key();
	} else {
		$id = 'post-' . $page->key();
	}

	// String replace not necessary but just in case...
	return strtolower( str_replace( [ '_', ' ' ], '-', $id ) );
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
		if ( 'grid' == THEME_CONFIG['posts']['loop'] ) {
			$template = 'views/content/posts-grid.php';
		} else {
			$template = 'views/content/posts.php';
		}

	// Page templates.
	} elseif ( 'page' == $url->whereAmI() ) {

		// Static home page.
		if ( $site->getField( 'homepage' ) && $page->slug() == $site->getField( 'homepage' ) ) {
			$template = 'views/content/front-page.php';

		/**
		 * Page with template applied, excluding some templates.
		 * Sidebar templates are excluded because sidebar location
		 * is achieved with CSS based on body class.
		 *
		 * @see body_classes()
		 *
		 * The `full-cover` template is excluded because a different
		 * site header is used prior to calling this function.
		 */
		} elseif ( $page->template() ) {
			$template = 'views/content/' . str_replace( [ ' ', 'full-cover', 'no-sidebar', 'sidebar-bottom' ], '', $page->template() ) . '.php';
			if ( file_exists( THEME_DIR . $template ) ) {
				$template = $template;
			} else {
				$template = 'views/content/page.php';
			}

		// Static page.
		} elseif ( $page->isStatic() ) {
			$template = 'views/content/page.php';

		// Sticky page (post).
		} elseif ( $page->sticky() ) {
			$template = 'views/content/sticky.php';

		// Default (post) page.
		} else {
			$template = 'views/content/post.php';
		}

	// Default to posts loop.
	} else {
		if ( 'grid' == THEME_CONFIG['posts']['loop'] ) {
			$template = 'views/content/posts-grid.php';
		} else {
			$template = 'views/content/posts.php';
		}
	}
	return $template;
}

/**
 * Posts loop header
 *
 * prints a header section in a posts loop
 * page: posts, category, tag.
 *
 * @since  1.0.0
 * @global object $L Language class
 * @global object $site Site class.
 * @global object $url Url class.
 * @return mixed
 */
function posts_loop_header() {

	// Access global variables.
	global $L, $site, $url;

	// Null if in search results (global errors).
	if ( 'search' == $url->whereAmI() ) {
		return null;
	}

	// Header variables.
	$heading     = '';
	$description = '';
	$class       = '';
	$format_slug =  ucwords( str_replace( [ '-', '_' ], '', $url->slug() ) );
	$blog_data   = blog_data();
	$blog_page   = '';

	// If on a blog page other than the first.
	if ( isset( $_GET['page'] ) && $_GET['page'] > 1 ) {
		$blog_page = sprintf(
			' &rsaquo; %s %s',
			$L->get( 'page' ),
			$_GET['page']
		);
	}

	// Conditional heading & description.
	if (
		'blog' == $url->whereAmI() &&
		'page' == $blog_data['location']
	) {
		$class       = 'blog-page-description';
		$heading     = $blog_data['title'];
		$description = $blog_data['description'];

	} elseif ( 'blog' == $url->whereAmI() ) {
		$class       = 'blog-page-description';
		$heading     = ucwords( $blog_data['slug'] . $blog_page );
		$description = sprintf(
			'%s %s',
			$L->get( 'posts-loop-desc-blog' ),
			$site->title()
		);

	} elseif ( 'category' == $url->whereAmI() ) {
		$get_cat     = new \Category( $url->slug() );
		$class       = 'category-page-description';
		$heading     = $get_cat->name();
		$description = text_replace( 'posts-loop-desc-cat', $get_cat->name() );

	} elseif ( 'tag' == $url->whereAmI() ) {
		$get_tag     = new \Tag( $url->slug() );
		$class       = 'tag-page-description';
		$heading     = $get_tag->name();
		$description = text_replace( 'posts-loop-desc-tag', $get_tag->name() );
	}

	// SEt up the header markup.
	$html = '<header class="page-header">';

	if ( ! empty( $heading ) && ! ctype_space( $heading ) ) {
		$html .= sprintf(
			'<h1>%s</h1>',
			$heading
		);
	}

	if ( ! empty( $description ) && ! ctype_space( $description ) ) {
		$html .= sprintf(
			'<p class="page-description %s">%s</p>',
			$class,
			$description
		);
	}
	$html .= '</header>';

	// Print nothing if site home or singular page.
	if (
		'home' == $url->whereAmI() ||
		'page' == $url->whereAmI()
	) {
		return '';
	}
	return $html;
}

/**
 * Sticky icon
 *
 * @since  1.0.0
 * @param  boolean $echo Whether to echo or return the icon.
 * @param  string $class Add classes to the icon markup.
 * @param  string $title Text for the title attribute.
 * @global object $L Language class
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
