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
 * @param  integer $ver The minimum version for which to check.
 * @return boolean
 */
function bludit_min( $ver = 3 ) {

	if ( defined( 'BLUDIT_VERSION' ) && BLUDIT_VERSION >= $ver ) {
		return true;
	}
	return false;
}

/**
 * Helper class instance
 *
 * Theme helper class is changed tp
 * HTML in Bludit version 4.0.
 *
 * @since  1.0.0
 * @return object
 */
function helper() {

	if ( bludit_min( 4 ) ) {
		$helper_class = new \HTML;
	} else {
		$helper_class = new \Theme;
	}
	return $helper_class;
}

/**
 * Plugins hook
 *
 * @since  1.0.0
 * @param  string $name The hook name.
 * @return mixed
 */
function plugins_hook( $name = '' ) {

	if ( bludit_min( 4 ) ) {
		$hook = execPluginsByHook( $name );
	} else {
		$hook = helper()->plugins( $name );
	}
	return $hook;
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
 * Website domain
 *
 * Returns the site URL setting or
 * the DOMAIN_BASE constant.
 *
 * @since  1.0.0
 * @return string
 */
function site_domain() {

	if ( site()->url() ) {
		return site()->url();
	}
	return DOMAIN_BASE;
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
 * Current language
 *
 * The language from site settings.
 *
 * @since  1.0.0
 * @return string
 */
function current_lang() {
	return lang()->currentLanguageShortVersion();
}

/**
 * Is RTL language
 *
 * @since  1.0.0
 * @param  mixed $langs Arguments to be passed.
 * @param  array $rtl Default arguments.
 * @return boolean Returns true if site is in RTL language.
 */
function is_rtl( $langs = null, $rtl = [] ) {

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

	// Maybe override defaults.
	if ( is_array( $langs ) && $langs ) {
		$langs = array_merge( $rtl, $langs );
	} else {
		$langs = $rtl;
	}

	if ( in_array( current_lang(), $rtl ) ) {
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
 * Is loop page
 *
 * Whether the current page is displaying
 * a posts loop.
 *
 * @since  1.0.0
 * @return boolean Returns true if in a loop.
 */
function is_loop_page() {

	$loop_page = false;

	if ( 'blog' == url()->whereAmI() ) {
		$loop_page = true;
	}
	return $loop_page;
}

/**
 * Blog URL
 *
 * @since  1.0.0
 * @return string Returns the URL of the loop page(s).
 */
function loop_url() {

	$site_url = site()->getField( 'url' );
	$loop_uri = site()->getField( 'uriBlog' );
	$loop_url = $site_url;

	if ( ! empty( $loop_uri ) ) {
		$loop_url = sprintf(
			'%s%s/',
			$site_url,
			str_replace( '/', '', $loop_uri )
		);
	}
	return $loop_url;
}

/**
 * Loop data
 *
 * Gets data for the loop, especially when
 * using a static front page.
 *
 * @since  1.0.0
 * @global array  $content array of site content
 * @global object $pages Pages class
 * @return array  Returns an array of loop data.
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
	if ( theme() && 'news' == theme()->loop_style() ) {
		$loop_style = 'news';
	}

	// Blog not on front page.
	$static_field = site()->getField( 'uriBlog' );
	$static_key   = str_replace( '/', '', $static_field );

	// Default loop description.
	$description = sprintf(
		'%s %s',
		lang()->get( 'loop-data-description' ),
		site()->title()
	);
	if ( theme() && ! empty( theme()->loop_description() ) ) {
		$description = theme()->loop_description();
	}

	// Default data array.
	$data = [
		'post_count'  => $pages->count(),
		'show_posts'  => site()->getField( 'itemsPerPage' ),
		'location'    => 'home',
		'key'         => false,
		'url'         => loop_url(),
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

		// Get data from the static loop page.
		$loop_page = buildPage( $static_key );

		// Description from the static loop page.
		if (
			! empty( $loop_page->description() ) ||
			! ctype_space( $loop_page->description() )
		) {
			$description = $loop_page->description();
		}

		$data['location']    = 'page';
		$data['key']         = $loop_page->key();
		$data['slug']        = $loop_page->slug();
		$data['template']    = $loop_page->template();
		$data['title']       = $loop_page->title();
		$data['description'] = $description;
		$data['cover']       = $loop_page->coverImage();
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

	// Main navigation to right of site branding (default).
	$position = 'right';

	// Do not use main navigation.
	if ( theme() && 'hide' == theme()->main_nav_pos() ) {
		$position = 'hidden';

	// Main navigation above site branding.
	} elseif ( theme() && 'above' == theme()->main_nav_pos()	) {
		$position = 'above';

	// Main navigation below site branding.
	} elseif ( theme() && 'below' == theme()->main_nav_pos()	) {
		$position = 'below';

	// Main navigation to left of site branding.
	} elseif ( theme() && 'left' == theme()->main_nav_pos() ) {
		$position = 'left';
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
	$default = '';

	// Default cover from theme plugin.
	if ( theme() && theme()->cover_src() ) {
		$default = theme()->cover_src();
	}

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
 * Loop is static
 *
 * Checks if the the blog URI option
 * is set and if a static page slug
 * matches the URI setting.
 *
 * @since  1.0.0
 * @return boolean
 */
function loop_is_static() {

	$loop = loop_data();

	if ( $loop['key'] && ! empty( $loop['key'] ) ) {
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
	$default = '';
	if ( theme() && theme()->cover_src() ) {
		$default = theme()->cover_src();
	}

	// If in loop pages.
	if ( 'blog' == url()->whereAmI() ) {
		if ( loop_is_static() ) {
			$loop = loop_data();

			if ( ! empty( $loop['cover'] ) ) {
				$src  = $loop['cover'];
			} else {
				$src = $default;
			}
		} elseif ( $default ) {
			$src = $default;
		} elseif ( page()->coverImage() ) {
			$src = page()->coverImage();
		}

	// If on a singular page.
	} elseif ( 'page' == url()->whereAmI() ) {
		if ( page()->coverImage() ) {
			$src = page()->coverImage();
		} elseif ( $default ) {
			$src = $default;
		}

	// Default.
	} elseif ( $default ) {
		$src = $default;
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

	// Get loop data.
	$loop = loop_data();

	// No full cover if URL has the page parameter.

	if ( 'blog' == url()->whereAmI() && loop_is_static() ) {
		$build = buildPage( $loop['key'] );

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

	$include = true;
	if ( 'blog' == url()->whereAmI() ) {
		if ( theme() && 'none' == theme()->sidebar_in_loop() ) {
			$include = false;
		}
	} elseif ( site()->pageNotFound() && url()->notFound() ) {
		if ( theme() && 'content' != theme()->error_widgets() ) {
			$include = false;
		}
	} elseif ( 'page' == url()->whereAmI() ) {
		if ( str_contains( page()->template(), 'no-sidebar' ) ) {
			$include = false;
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
	if ( defined( 'DEBUG_MODE' ) && DEBUG_MODE ) {
		return '';
	}
	return '.min';
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
 * Get related posts
 *
 * @since  1.0.0
 * @param  mixed $max
 * @param  mixed $similar
 * @global object $page The Page class.
 * @global object $url The Url class.
 * @return mixed Returns an array of related posts or false.
 */
function get_related( $max = 3, $similar = true ) {

	global $page, $url;

	if ( 'page' == $url->whereAmI() ) {

		if ( $page->isStatic() || ! $page->category() ) {
			return false;
		}

		if ( theme() && theme()->max_related() ) {
			$max = theme()->max_related();
		}
		$currentCategory = getCategory( $page->categoryKey() );
		$allCatPages     = $currentCategory->pages();
		$currentKey      = $page->key();

		// Remove current page.
		$allCatPages = array_diff( $allCatPages, [ $currentKey ] );

		// Sort rest pages by similarity O(N** max).
		if ( $similar ) {
			usort( $allCatPages, function ( $a, $b ) use ( $currentKey ) {
				similar_text( $currentKey, $a, $percentA );
				similar_text( $currentKey, $b, $percentB );
				return $percentA === $percentB ? 0 : ( $percentA > $percentB ? -1 : 1 );
			} );
		}
		// Or randomize.
		else {
			shuffle( $allCatPages );
		}

		$related = [];
		try {
			for ( $i = 0; $i < $max; $i++ ) {
				$item = new \Page( $allCatPages[$i] );
				if ( $item->published() ) {
					$related[] = $item;
				}
			}
		}
		catch( \Exception $e ) {
			// Do exception?
		}
		return $related;
	}
	return false;
}

/**
 * Convert a 3- or 6-digit hexadecimal color to an associative RGB array.
 *
 * @param  string $color The color in hex format.
 * @param  bool   $opacity Whether to return the RGB color is opaque.
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

// Helper class instance.
$helper = helper();
