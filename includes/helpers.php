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
		is_array( THEME_CONFIG['head'] ) &&
		array_key_exists( 'favicon', THEME_CONFIG['head'] ) &&
		! empty( THEME_CONFIG['head']['favicon'] )
	) {
		$favicon = THEME_DIR . 'assets/images/' . THEME_CONFIG['head']['favicon'];
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
 * @global object $pages Pages class
 * @global object $site Site class
 * @global object $url Url class
 * @return array  Returns an array of blog data.
 */
function blog_data() {

	// Access global variables.
	global $content, $L, $pages, $site, $url;

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
		'slug'        => str_replace( '/', '', $site->getField( 'uriBlog' ) ),
		'template'    => false,
		'title'       => false,
		'description' => $description,
		'cover'       => false,
	];

	if ( empty( $static_field ) || ! $pages->exists( $static_key ) ) {
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
 * Has cover image
 *
 * @since  1.0.0
 * @global object $page Page class
 * @global object $url Url class
 * @return boolean
 */
function has_cover() {

	// Access global variables.
	global $page, $url;

	$cover   = false;
	$default = THEME_CONFIG['media']['cover_image'];

	if ( 'page' == $url->whereAmI() ) {
		if ( $page->coverImage() ) {
			$cover = true;
		} elseif ( $default && file_exists( THEME_DIR . $default ) ) {
			$cover = true;
		}
	} elseif ( $default && file_exists( THEME_DIR . $default ) ) {
		$cover = true;
	}
	return $cover;
}

/**
 * Get cover image source
 *
 * @since  1.0.0
 * @global object $page Page class
 * @global object $url Url class
 * @return void
 */
function get_cover_src() {

	// Access global variables.
	global $page, $url;

	$src     = '';
	$default = THEME_CONFIG['media']['cover_image'];

	if ( 'page' == $url->whereAmI() ) {
		if ( $page->coverImage() ) {
			$src = $page->coverImage();
		} elseif ( $default && file_exists( THEME_DIR . $default ) ) {
			$src = DOMAIN_THEME . $default;
		}
	} elseif ( $default && file_exists( THEME_DIR . $default ) ) {
		$src = DOMAIN_THEME . $default;
	}
	return $src;
}

/**
 * Is full cover template
 *
 * @since  1.0.0
 * @global object $page Page class
 * @global object $url Url class
 * @return boolean Returns true if `full-cover` is found in the
 *                 page template field and the page gas a cover
 *                 image.
 */
function full_cover() {

	// Access global variables.
	global $page, $url;

	if (
		'page' == $url->whereAmI() &&
		has_cover() &&
		str_contains( $page->template(), 'full-cover' )
	) {
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
		( defined( 'THEME_CONFIG' ) && THEME_CONFIG['debug'] )
	) {
		return '';
	}
	return '.min';
}

/**
 * Read numbers
 *
 * Converts an integer to its textual representation.
 *
 * @since  1.0.0
 * @param  integer $number the number to convert to a textual representation
 * @param  integer $depth the number of times this has been recursed
 * @global object $L Language class
 * @return string Returns a word corresponding to a numeral
 */
function numbers_to_text( $number, $depth = 0 ) {

	// Access global variables.
	global $L;

	$number = (int)$number;
	$text   = '';

	// If it's any other negative, just flip it and call again.
	if ( $number < 0 ) {
		return 'negative ' + numbers_to_text( - $number, 0 );
	}

	// 100 and above.
	if ( $number > 99 ) {

		// 1000 and higher.
		if ( $number > 999 ) {
			$text .= numbers_to_text( $number / 1000, $depth + 3 );
		}

		// Last three digits.
		$number %= 1000;

		// As long as the first digit is not zero.
		if ( $number > 99 ) {
			$text .= numbers_to_text( $number / 100, 2 ) . $L->get( ' hundred' ) . "\n";
		}

		// Last two digits.
		$text .= numbers_to_text( $number % 100, 1 );

	// From 0 to 99.
	} else {

		$mod = floor( $number / 10 );

		// Ones place.
		if ( 0 == $mod ) {
			if ( 1 == $number ) {
				$text .= $L->get( 'one' );
			} elseif ( 2 == $number ) {
				$text .= $L->get( 'two' );
			} elseif ( 3 == $number ) {
				$text .= $L->get( 'three' );
			} elseif ( 4 == $number ) {
				$text .= $L->get( 'four' );
			} elseif ( 5 == $number ) {
				$text .= $L->get( 'five' );
			} elseif ( 6 == $number ) {
				$text .= $L->get( 'six' );
			} elseif ( 7 == $number ) {
				$text .= $L->get( 'seven' );
			} elseif ( 8 == $number ) {
				$text .= $L->get( 'eight' );
			} elseif ( 9 == $number ) {
				$text .= $L->get( 'nine' );
			}

		// if there's a one in the ten's place.
		} elseif ( 1 == $mod ) {
			if ( 10 == $number ) {
				$text .= $L->get( 'ten' );
			} elseif ( 11 == $number ) {
				$text .= $L->get( 'eleven' );
			} elseif ( 12 == $number ) {
				$text .= $L->get( 'twelve' );
			} elseif ( 13 == $number ) {
				$text .= $L->get( 'thirteen' );
			} elseif ( 14 == $number ) {
				$text .= $L->get( 'fourteen' );
			} elseif ( 15 == $number ) {
				$text .= $L->get( 'fifteen' );
			} elseif ( 16 == $number ) {
				$text .= $L->get( 'sixteen' );
			} elseif ( 17 == $number ) {
				$text .= $L->get( 'seventeen' );
			} elseif ( 18 == $number ) {
				$text .= $L->get( 'eighteen' );
			} elseif ( 19 == $number ) {
				$text .= $L->get( 'nineteen' );
			}

		// if there's a different number in the ten's place.
		} else {
			if ( 2 == $mod ) {
				$text .= $L->get( 'twenty' );
			} elseif ( 3 == $mod ) {
				$text .= $L->get( 'thirty' );
			} elseif ( 4 == $mod ) {
				$text .= $L->get( 'forty' );
			} elseif ( 5 == $mod ) {
				$text .= $L->get( 'fifty' );
			} elseif ( 6 == $mod ) {
				$text .= $L->get( 'sixty' );
			} elseif ( 7 == $mod ) {
				$text .= $L->get( 'seventy' );
			} elseif ( 8 == $mod ) {
				$text .= $L->get( 'eighty' );
			} elseif ( 9 == $mod ) {
				$text .= $L->get( 'ninety' );
			}

			if ( ( $number % 10 ) != 0 ) {

				// Get rid of space at end.
				$text  = rtrim( $text );
				$text .= '-';
			}
			$text .= numbers_to_text( $number % 10, 0 );
		}
	}

	if ( 0 != $number ) {
		if ( 3 == $depth ) {
			$text .= $L->get( ' thousand' ) . "\n";
		} elseif ( 6 == $depth ) {
			$text .= $L->get( ' million' ) . "\n";
		}

		if ( 9 == $depth ) {
			$text .= $L->get( ' billion' ) . "\n";
		}
	}
	return $text;
}
