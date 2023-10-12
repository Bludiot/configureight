<?php
/**
 * Helper functions
 *
 * @package    Configure 8
 * @subpackage Includes
 * @category   Functions
 * @since      1.0.0
 */

namespace CFE_Func;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

/**
 * Minimum Bludit version
 *
 * @example Use `if ( bludit_min( 4 ) ) { … }`
 * 			to check for Bludit 4.0+.
 *
 * @since  1.0.0
 * @return boolean
 */
function bludit_min( $ver = 3 ) {

	if ( defined( BLUDIT_VERSION ) && BLUDIT_VERSION >= $ver ) {
		return true;
	}
	return false;
}

/**
 * Site class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $site Site class
 * @return object
 */
function site() {
	global $site;
	return $site;
}

/**
 * Url class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $url Url class
 * @return object
 */
function url() {
	global $url;
	return $url;
}

/**
 * Language class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $L Language class
 * @return object
 */
function lang() {
	global $L;
	return $L;
}

/**
 * Page class object
 *
 * Function to use inside other functions and
 * methods rather than calling the global.
 *
 * @since  1.0.0
 * @global object $page Page class
 * @return object
 */
function page() {
	global $page;
	return $page;
}

/**
 * Theme plugin
 *
 * This function is used to get settings from the
 * theme plugin instead of the provided global
 * variable. The variable changes in Bludit 4.0.
 *
 * @since  1.0.0
 * @return object
 */
function theme() {

	$theme = false;
	if ( getPlugin( site()->theme() ) ) {
		$theme = getPlugin( site()->theme() );
	}
	return $theme;
}

if ( ! isset( $themePlugin ) ) {
	$themePlugin = theme();
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
 * @return string Returns the modified string or the string
 *                as is if the variable is not found.
 */
function text_replace( $get = '', $string = '' ) {

	if ( strstr( lang()->get( $get ), '%replace%' ) ) {
		return str_replace( '%replace%', $string, lang()->get( $get ) );
	}
	return lang()->get( $get );
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
 * @return boolean Returns true if in a blog loop.
 */
function is_blog_page() {

	$blog_page = false;

	if ( 'blog' == url()->whereAmI() ) {
		$blog_page = true;
	}
	return $blog_page;
}

/**
 * Blog URL
 *
 * @since  1.0.0
 * @return string Returns the URL of the blog page(s).
 */
function blog_url() {

	$site_url = site()->getField( 'url' );
	$blog_uri = site()->getField( 'uriBlog' );
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
 * @global object $pages Pages class
 * @return array  Returns an array of blog data.
 */
function loop_data() {

	// Access global variables.
	global $content, $pages;

	// Null if in search results (global errors).
	if ( 'search' == url()->whereAmI() ) {
		return null;
	}

	// Posts loop style.
	$loop_style = 'blog';
	if ( THEME_CONFIG['loop']['style'] && 'news' === THEME_CONFIG['loop']['style'] ) {
		$loop_style = 'news';
	}

	// Blog not on front page.
	$static_field = site()->getField( 'uriBlog' );
	$static_key   = str_replace( '/', '', $static_field );

	// Default blog description.
	$description = sprintf(
		'%s %s',
		lang()->get( 'blog-data-description' ),
		site()->title()
	);

	// Default data array.
	$data = [
		'post_count'  => $pages->count(),
		'show_posts'  => site()->getField( 'itemsPerPage' ),
		'location'    => 'home',
		'key'         => false,
		'url'         => blog_url(),
		'slug'        => str_replace( '/', '', site()->getField( 'uriBlog' ) ),
		'template'    => false,
		'style'       => $loop_style,
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
 * Get navigation position
 *
 * Returns the position of the main
 * navigation menu.
 *
 * @since  1.0.0
 * @return string
 */
function get_nav_position() {

	$position = 'after';
	if (
		'hide'   === THEME_CONFIG['main_nav']['position'] ||
		'hidden' === THEME_CONFIG['main_nav']['position']
	) {
		$position = 'hidden';

	// Main navigation above site branding.
	} elseif ( 'above' === THEME_CONFIG['main_nav']['position']	) {
		$position = 'above';

	// Main navigation below site branding.
	} elseif ( 'below' === THEME_CONFIG['main_nav']['position']	) {
		$position = 'below';

	// Main navigation before site branding.
	} elseif (
		'left'   === THEME_CONFIG['main_nav']['position'] ||
		'before' === THEME_CONFIG['main_nav']['position']
	) {
		$position = 'before';

	// Main navigation after site branding (default).
	} else {
		$position = 'after';
	}
	return $position;
}

/**
 * Has cover image
 *
 * @since  1.0.0
 * @return boolean
 */
function has_cover() {

	$cover   = false;
	$default = 'assets/images/' . THEME_CONFIG['media']['cover_image'];

	if ( 'page' == url()->whereAmI() ) {
		if ( page()->coverImage() ) {
			$cover = true;
		} elseif ( $default ) {
			if ( filter_var( $default, FILTER_VALIDATE_URL ) ) {
				$cover = true;
			} elseif ( file_exists( THEME_DIR . $default ) ) {
				$cover = true;
			}
		}
	} elseif ( $default ) {
		if ( filter_var( $default, FILTER_VALIDATE_URL ) ) {
			$cover = true;
		} elseif ( file_exists( THEME_DIR . $default ) ) {
			$cover = true;
		}
	}
	return $cover;
}

/**
 * Blog is static
 *
 * Checks if the the blog URI option
 * is set and if a static page slug
 * matches the URI setting.
 *
 * @since  1.0.0
 * @return boolean
 */
function blog_is_static() {

	$blog = loop_data();

	if ( $blog['key'] && ! empty( $blog['key'] ) ) {
		return true;
	}
	return false;
}

/**
 * Get cover image source
 *
 * @since  1.0.0
 * @return string
 */
function get_cover_src() {

	$src     = '';
	$default = 'assets/images/' . THEME_CONFIG['media']['cover_image'];

	// If in blog pages.
	if ( 'blog' == url()->whereAmI() ) {
		if ( blog_is_static() && page()->coverImage() ) {
			$src = page()->coverImage();
		} elseif ( $default ) {
			if ( filter_var( $default, FILTER_VALIDATE_URL ) ) {
				$src = $default;
			} elseif ( file_exists( THEME_DIR . $default ) ) {
				$src = DOMAIN_THEME . $default;
			}
		}

	// If on a singular page.
	} elseif ( 'page' == url()->whereAmI() ) {
		if ( page()->coverImage() ) {
			$src = page()->coverImage();
		} elseif ( $default ) {
			if ( filter_var( $default, FILTER_VALIDATE_URL ) ) {
				$src = $default;
			} elseif ( file_exists( THEME_DIR . $default ) ) {
				$src = DOMAIN_THEME . $default;
			}
		}

	// Default.
	} elseif ( $default ) {
		if ( filter_var( $default, FILTER_VALIDATE_URL ) ) {
			$src = $default;
		} elseif ( file_exists( THEME_DIR . $default ) ) {
			$src = DOMAIN_THEME . $default;
		}
	}
	return $src;
}

/**
 * Is full cover template
 *
 * @since  1.0.0
 * @return boolean Returns true if `full-cover` is found in the
 *                 page template field and the page gas a cover
 *                 image.
 */
function full_cover() {

	// Get blog data.
	$blog = loop_data();

	// No full cover if URL has the page parameter.

	if ( 'blog' == url()->whereAmI() && blog_is_static() ) {
		$build = buildPage( $blog['key'] );

		if (
			$build->isStatic() &&
			str_contains( $build->template(), 'full-cover' )
		) {
			if ( isset( $_GET['page'] ) ) {
				return false;
			}
			return true;
		}
	}

	if (
		'page' == url()->whereAmI() &&
		has_cover() &&
		str_contains( page()->template(), 'full-cover' )
	) {
		return true;
	}
	return false;
}

/**
 * Include sidebar
 *
 * When to include the aside template.
 *
 * @since  1.0.0
 * @return boolean
 */
function include_sidebar() {

	$include = false;
	if ( 'search' == url()->whereAmI() ) {
		$include = true;
	} elseif ( 'blog' == url()->whereAmI() ) {
		if ( false !== THEME_CONFIG['loop']['sidebar'] ) {
			$include = true;
		}
	} elseif ( 'page' == url()->whereAmI() ) {
		if ( ! str_contains( page()->template(), 'no-sidebar' ) ) {
			$include = true;
		}
	}
	return $include;
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
 * Get config styles
 *
 * Gets override styles from the theme config file.
 *
 * @since  1.0.0
 * @return array Returns an array of CSS properties & values.
 */
function get_config_styles() {

	$styles = [];

	if ( THEME_CONFIG['media']['cover_color'] ) {
		$merge  = [ 'cover_color' => THEME_CONFIG['media']['cover_color'] ];
		$styles = array_merge( $styles, $merge );
	}

	if ( THEME_CONFIG['media']['cover_opacity'] ) {
		$merge  = [ 'cover_opacity' => THEME_CONFIG['media']['cover_opacity'] ];
		$styles = array_merge( $styles, $merge );
	}

	return $styles;
}

/**
 * Word count
 *
 * Returns the word count of the page content,
 * meaning content from the admin editor.
 *
 * @since  1.0.0
 * @param  string Key of the page to count.
 * @return integer
 */
function get_word_count( $key = '' ) {

	if ( empty( $key ) ) {
		$key = $page->key();
	}

	$build = buildPage( $key );

	return str_word_count( strip_tags( $build->content() ) );
}

/**
 * Convert a 3- or 6-digit hexadecimal color to an associative RGB array.
 *
 * @param string $color The color in hex format.
 * @param bool   $opacity Whether to return the RGB color is opaque.
 *
 * @return string
 */
function hex_to_rgb( $color, $opacity = false ) {

	if ( empty( $color ) ) {
		return false;
	}

	if ( '#' === $color[0] ) {
		$color = substr( $color, 1 );
	}

	if ( 6 === strlen( $color ) ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( 3 === strlen( $color ) ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return null;
	}
	$rgb = array_map( 'hexdec', $hex );

	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';

	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	return $output;
}

/**
 * Read numbers
 *
 * Converts an integer to its textual representation.
 *
 * @since  1.0.0
 * @param  integer $number the number to convert to a textual representation
 * @param  integer $depth the number of times this has been recursed
 * @return string Returns a word corresponding to a numeral
 */
function numbers_to_text( $number, $depth = 0 ) {

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
			$text .= numbers_to_text( $number / 100, 2 ) . lang()->get( ' hundred' ) . "\n";
		}

		// Last two digits.
		$text .= numbers_to_text( $number % 100, 1 );

	// From 0 to 99.
	} else {

		$mod = floor( $number / 10 );

		// Ones place.
		if ( 0 == $mod ) {
			if ( 1 == $number ) {
				$text .= lang()->get( 'one' );
			} elseif ( 2 == $number ) {
				$text .= lang()->get( 'two' );
			} elseif ( 3 == $number ) {
				$text .= lang()->get( 'three' );
			} elseif ( 4 == $number ) {
				$text .= lang()->get( 'four' );
			} elseif ( 5 == $number ) {
				$text .= lang()->get( 'five' );
			} elseif ( 6 == $number ) {
				$text .= lang()->get( 'six' );
			} elseif ( 7 == $number ) {
				$text .= lang()->get( 'seven' );
			} elseif ( 8 == $number ) {
				$text .= lang()->get( 'eight' );
			} elseif ( 9 == $number ) {
				$text .= lang()->get( 'nine' );
			}

		// if there's a one in the ten's place.
		} elseif ( 1 == $mod ) {
			if ( 10 == $number ) {
				$text .= lang()->get( 'ten' );
			} elseif ( 11 == $number ) {
				$text .= lang()->get( 'eleven' );
			} elseif ( 12 == $number ) {
				$text .= lang()->get( 'twelve' );
			} elseif ( 13 == $number ) {
				$text .= lang()->get( 'thirteen' );
			} elseif ( 14 == $number ) {
				$text .= lang()->get( 'fourteen' );
			} elseif ( 15 == $number ) {
				$text .= lang()->get( 'fifteen' );
			} elseif ( 16 == $number ) {
				$text .= lang()->get( 'sixteen' );
			} elseif ( 17 == $number ) {
				$text .= lang()->get( 'seventeen' );
			} elseif ( 18 == $number ) {
				$text .= lang()->get( 'eighteen' );
			} elseif ( 19 == $number ) {
				$text .= lang()->get( 'nineteen' );
			}

		// if there's a different number in the ten's place.
		} else {
			if ( 2 == $mod ) {
				$text .= lang()->get( 'twenty' );
			} elseif ( 3 == $mod ) {
				$text .= lang()->get( 'thirty' );
			} elseif ( 4 == $mod ) {
				$text .= lang()->get( 'forty' );
			} elseif ( 5 == $mod ) {
				$text .= lang()->get( 'fifty' );
			} elseif ( 6 == $mod ) {
				$text .= lang()->get( 'sixty' );
			} elseif ( 7 == $mod ) {
				$text .= lang()->get( 'seventy' );
			} elseif ( 8 == $mod ) {
				$text .= lang()->get( 'eighty' );
			} elseif ( 9 == $mod ) {
				$text .= lang()->get( 'ninety' );
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
			$text .= lang()->get( ' thousand' ) . "\n";
		} elseif ( 6 == $depth ) {
			$text .= lang()->get( ' million' ) . "\n";
		}

		if ( 9 == $depth ) {
			$text .= lang()->get( ' billion' ) . "\n";
		}
	}
	return $text;
}
