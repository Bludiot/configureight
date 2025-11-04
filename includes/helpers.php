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

	if ( $hook ) {
		echo $hook;
	} else {
		return false;
	}
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
function plugin() {

	$theme = false;
	if ( getPlugin( site()->theme() ) ) {
		$theme = getPlugin( site()->theme() );
	}
	return $theme;
}

/**
 * User logged in
 *
 * @since  1.0.0
 * @global object $login The Login class.
 * @return boolean Returns true if the current user is logged in.
 */
function user_logged_in() {

	// Access global variables.
	global $login;

	if ( $login->isLogged() ) {
		return true;
	}
	return false;
}

/**
 * User role
 *
 * @since  1.0.0
 * @return mixed Returns the logged-in user role or false.
 */
function user_role() {

	if ( ! user_logged_in() ) {
		return false;
	}
	$user = new \User( \Session :: get( 'username' ) );
	return $user->role();
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
 * Meta URL
 *
 * @since  1.0.0
 * @return string
 */
function meta_url() {

	// Default to site domain.
	$url = site_domain();

	if ( is_front_page() ) {
		$url = site_domain();

	} elseif ( is_main_loop() && is_static_loop() ) {
		$url = DOMAIN_BASE . static_loop_page()->slug() . '/';

	} elseif ( is_main_loop() && is_loop_not_home() ) {
		$url = DOMAIN_BASE . str_replace( '/', '', site()->getField( 'uriBlog' ) ) . '/';

	} elseif ( is_page() ) {
		$url = site_domain() . url()->slug();

	} elseif ( is_cat() ) {
		$url = DOMAIN_CATEGORIES . url()->slug();

	} elseif ( is_tag() ) {
		$url = DOMAIN_TAGS . url()->slug();

	} elseif ( is_search() ) {
		$url = site_domain() . 'search/';
	}
	return $url;
}

/**
 * Is home
 *
 * If the main loop is on the front page.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_home() {

	if ( 'home' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is category
 *
 * Whether the current page is displaying
 * the category loop.
 *
 * @since  1.0.0
 * @return boolean Returns true if in the main loop.
 */
function is_cat() {

	if ( 'category' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is tag
 *
 * Whether the current page is displaying
 * the tag loop.
 *
 * @since  1.0.0
 * @return boolean Returns true if in the main loop.
 */
function is_tag() {

	if ( 'tag' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is main loop
 *
 * Whether the current page is displaying
 * the main posts loop.
 *
 * Excludes category and tag loops.
 *
 * @since  1.0.0
 * @return boolean Returns true if in the main loop.
 */
function is_main_loop() {

	if ( 'blog' == url()->whereAmI() && ! is_cat() && ! is_tag() ) {
		return true;
	}
	return false;
}

/**
 * Is loop page
 *
 * Whether the current page is displaying
 * a posts loop.
 *
 * Excludes search pages.
 *
 * @since  1.0.0
 * @return boolean Returns true if in a loop.
 */
function is_loop_page() {

	$loop_page = false;
	if ( is_main_loop() ) {
		$loop_page = true;
	}
	if ( is_cat() ) {
		$loop_page = true;
	}
	if ( is_tag() ) {
		$loop_page = true;
	}
	return $loop_page;
}

/**
 * Loop not home
 *
 * If the main loop is not the website home.
 *
 * @since  1.0.0
 * @return boolean Returns true if loop slug is set.
 */
function is_loop_not_home() {

	if ( site()->getField( 'uriBlog' ) ) {
		return true;
	}
	return false;
}

/**
 * Is static loop
 *
 * If a static page has the same slug as the loop slug.
 *
 * @since  1.0.0
 * @return boolean Return whether that page exists.
 */
function is_static_loop() {

	if ( static_loop_page() ) {
		return true;
	}
	return false;
}

/**
 * Static loop page
 *
 * @since  1.0.0
 * @return mixed Returns the static loop page object or
 *               returns false if the page doesn't exist.
 */
function static_loop_page() {

	// Get the blog slug setting.
	$loop_field = site()->getField( 'uriBlog' );

	// Remove slashes from field value, if set.
	$loop_key   = str_replace( '/', '', $loop_field );

	// Build a page using blog slug setting.
	$loop_page  = buildPage( $loop_key );

	// Return whether that page exists (could be built).
	if ( $loop_page ) {
		return $loop_page;
	}
	return false;
}

/**
 * Is page
 *
 * If on a page, static or not.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_page() {

	if ( 'page' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is search
 *
 * If on a search page.
 *
 * No need to check for a search plugin because
 * it is the plugin that defines the location,
 * so the `whereAmI()` function will return false.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_search() {

	if ( 'search' == url()->whereAmI() ) {
		return true;
	}
	return false;
}

/**
 * Is 404
 *
 * @since  1.0.0
 * @return boolean
 */
function is_404() {

	if ( url()->notFound() ) {
		return true;
	}
	return false;
}

/**
 * Page type
 *
 * Whether the page object is static or not.
 *
 * @since  1.0.0
 * @return mixed Returns the page type or null.
 */
function page_type() {

	if ( ! is_page() ) {
		return null;
	}

	if ( page()->isStatic() ) {
		return 'page';
	}
	return 'post';
}

/**
 * Is front page
 *
 * If the front page is not the loop.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_front_page() {

	if ( ! is_page() ) {
		return false;
	}

	if ( site()->homepage() && page()->key() == site()->homepage() ) {
		return true;
	}
	return false;
}

/**
 * User profiles
 *
 * Get the User Profiles plugin object.
 *
 * @since  1.0.0
 * @return object User_Profiles class.
 */
function profiles() {

	if ( getPlugin( 'User_Profiles' ) ) {
		return new \User_Profiles();
	}
	return false;
}

/**
 * Is user
 *
 * If on a user page.
 *
 * @since  1.0.0
 * @return boolean
 */
function is_user() {

	if ( profiles() ) {
		if ( profiles()->users_slug() == url()->whereAmI() ) {
			return true;
		}
	}
	return false;
}

/**
 * User slug
 *
 * Get the user slug from the URL.
 *
 * @since  1.0.0
 * @return string
 */
function user_slug() {

	if ( ! profiles() ) {
		return false;
	}
	$slug = url()->explodeSlug();
	if ( isset( $slug[1] ) ) {
		return $slug[1];
	} else {
		return $slug;
	}
}

/**
 * Text replace
 *
 * Replaces the `%replace%` variable in
 * a language file string.
 *
 * @since  1.0.0
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
 * Loop cover
 *
 * Whether to display a cover image on
 * main loop pages.
 *
 * @since  1.0.0
 * @return boolean Returns true if a cover should display.
 */
function loop_cover() {

	// Always display if theme plugin not installed.
	if ( ! plugin() ) {
		return true;
	}

	if ( is_main_loop() && ! is_static_loop() ) {

		if ( 'none' == plugin()->loop_cover() ) {
			return false;
		}
		if ( 'first' == plugin()->loop_cover() ) {
			if ( isset( $_GET['page'] ) ) {
				return false;
			}
		}
		if ( 'full_first_none' == plugin()->loop_cover() ) {
			if ( isset( $_GET['page'] ) ) {
				return false;
			}
		}
	}
	return true;
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
	if ( is_search() ) {
		return null;
	}

	// Posts loop type.
	$loop_type = 'blog';
	if ( plugin() ) {
		$loop_type = plugin()->loop_type();
	}

	// Default loop description.
	$description = text_replace( 'posts-loop-desc-blog', site()->title() );
	if ( plugin() ) {
		if ( ! empty( plugin()->loop_description() ) ) {
			$description = plugin()->loop_description();
		}
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
		'type'       => $loop_type,
		'title'       => $loop_type,
		'description' => $description,
		'cover'       => false,
	];

	if ( ! is_static_loop() ) {
		return $data;
	} else {

		// Get data from the static loop page.
		$loop_page = static_loop_page();

		// Description from the static loop page.
		if ( ! empty( $loop_page->description() ) ) {
			$description = $loop_page->description();
		}

		$static_data = [
			'location'    => 'page',
			'key'         => $loop_page->key(),
			'slug'        => $loop_page->slug(),
			'template'    => $loop_page->template(),
			'title'       => $loop_page->title(),
			'description' => $description,
			'cover'       => $loop_page->coverImage()
		];
		$data = array_merge( $data, $static_data );
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
	if ( plugin() ) {
		if ( 'hide' == plugin()->main_nav_pos() ) {
			$position = 'hidden';

		// Main navigation above site branding.
		} elseif ( 'above' == plugin()->main_nav_pos()	) {
			$position = 'above';

		// Main navigation below site branding.
		} elseif ( 'below' == plugin()->main_nav_pos()	) {
			$position = 'below';

		// Main navigation to left of site branding.
		} elseif ( 'left' == plugin()->main_nav_pos() ) {
			$position = 'left';
		}
	}
	return $position;
}

/**
 * Has logo
 *
 * @since  1.0.0
 * @param  string $type The logo type, standard or cover.
 * @return boolean
 */
function has_logo( $type = 'standard' ) {

	// Start false, conditionally true.
	$logo = false;

	// If plugin options.
	if ( plugin() ) {

		$standard = plugin()->standard_logo_src();
		$cover    = plugin()->cover_logo_src();
		$std_svg  = plugin()->logo_standard_svg();
		$cov_svg  = plugin()->logo_cover_svg();

		if ( 'standard' == $type ) {
			if ( $standard || $std_svg ) {
				$logo = true;
			}
		} elseif ( 'cover' == $type ) {
			if ( $cover || $cov_svg ) {
				$logo = true;
			}
		}
	} elseif ( ! empty( site()->logo() ) ) {
		if ( 'standard' == $type ) {
			$logo = true;
		} elseif ( 'cover' == $type ) {
			$logo = false;
		}
	}
	return $logo;
}

/**
 * Has cover image
 *
 * @since  1.0.0
 * @return boolean
 */
function has_cover( $default = '' ) {

	// Start false, conditionally true.
	$cover = false;

	// Default cover from theme plugin.
	if ( plugin() ) {
		if ( plugin()->cover_src() ) {
			$default = plugin()->cover_src();
		}
	} else {
		$default = get_cover_src();
	}

	// If on a singular post or page.
	if ( is_main_loop() && ! loop_cover() ) {
		$cover = false;
	} elseif ( is_user() ) {
		if ( plugin() ) {
			if (
				'header' == plugin()->cover_in_profile() ||
				'both'   == plugin()->cover_in_profile()
			) {
				$cover = true;
			} else {
				$cover = false;
			}
		} else {
			$cover = true;
		}
	} elseif ( is_page() ) {

		// If the page has a cover image set.
		if ( ! plugin() && page()->coverImage() ) {
			$cover = true;
		}

		// False if `no-cover` template.
		if ( str_contains( page()->template(), 'no-cover' ) ) {
			$cover = false;
		}

		if ( plugin() ) {
			if ( 'page' == page_type() ) {
				if ( 'none' == plugin()->cover_in_page() ) {
					if ( str_contains( page()->template(), 'full-cover' ) && plugin()->cover_src() ) {
						$cover = true;
					} elseif ( str_contains( page()->template(), 'default-cover' ) && plugin()->cover_src() ) {
						$cover = true;
					} elseif ( page()->custom( 'random_cover' ) && plugin()->cover_src() ) {
						$cover = true;
					} else {
						$cover = false;
					}
				} elseif ( plugin()->cover_src() ) {
					$cover = true;
				}
			} else {
				if ( 'none' == plugin()->cover_in_post() ) {
					if ( str_contains( page()->template(), 'full-cover' ) && plugin()->cover_src() ) {
						$cover = true;
					} elseif ( str_contains( page()->template(), 'default-cover' ) && plugin()->cover_src() ) {
						$cover = true;
					} elseif ( page()->custom( 'random_cover' ) && plugin()->cover_src() ) {
						$cover = true;
					} else {
						$cover = false;
					}
				} elseif ( plugin()->cover_src() ) {
					$cover = true;
				}
			}

		// If the theme plugin has a default cover image set.
		} elseif ( ! empty( $default ) ) {

			if ( filter_var( $default, FILTER_VALIDATE_URL ) ) {
				$cover = true;
			} elseif ( file_exists( THEME_DIR . $default ) ) {
				$cover = true;
			}

			// False if `no-cover` template.
			if ( str_contains( page()->template(), 'no-cover' ) ) {
				$cover = false;
			}
		}

	// If on a loop page.
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
 * Get cover image source
 *
 * @since  1.0.0
 * @return string
 */
function get_cover_src() {

	$src     = '';
	$default = '';
	if ( plugin() ) {
		if ( plugin()->cover_src() ) {
			$default = plugin()->cover_src();
		}
	}

	// If in loop pages.
	if ( is_main_loop() ) {
		if ( is_static_loop() ) {
			$loop  = loop_data();
			$build = buildPage( $loop['slug'] );
			$uuid   = $build->uuid();
			$dir    = PATH_UPLOADS_PAGES . $uuid . DS;
			$files  = \Filesystem :: listFiles( $dir, '*', '*', true, 0 );
			$images = [];

			// Get the URL for each full-size image.
			foreach ( $files as $file ) {
				$images[] = DOMAIN_UPLOADS_PAGES . $uuid . '/' . str_replace( $dir, '', $file );
			}

			if ( $build->custom( 'random_cover' ) ) {
				$rand = array_rand( $images );
				$src  = $images[$rand];
			} elseif ( ! empty( $loop['cover'] ) ) {
				$src = $loop['cover'];
			} else {
				$src = $default;
			}
		} elseif ( $default ) {
			$src = $default;
		} elseif ( page()->coverImage() ) {
			$src = page()->coverImage();
		}

	// If on a singular page.
	} elseif ( is_page() ) {
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
	if ( plugin() ) {
		if (
			is_main_loop() && ! is_static_loop() &&
			(
				'full_first' == plugin()->loop_cover() ||
				'full_first_none' == plugin()->loop_cover()
			 )
		) {
			if ( isset( $_GET['page'] ) ) {
				return false;
			}
			return true;
		}

		if ( is_front_page() && plugin()->posts_slider() ) {
			return true;
		}
	}

	if ( is_main_loop() && is_static_loop() ) {
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

	if ( is_page() && has_cover() ) {

		if ( plugin() ) {
			if ( 'page' == page_type() && 'full' == plugin()->cover_in_page() ) {
				if ( str_contains( page()->template(), 'no-cover' ) ) {
					return false;
				} elseif ( str_contains( page()->template(), 'default-cover' ) ) {
					return false;
				} else {
					return true;
				}
			} elseif ( 'full' == plugin()->cover_in_post() ) {
				if ( str_contains( page()->template(), 'no-cover' ) ) {
					return false;
				} elseif ( str_contains( page()->template(), 'default-cover' ) ) {
					return false;
				} else {
					return true;
				}
			}
		}

		if ( str_contains( page()->template(), 'full-cover' ) ) {
			return true;
		}
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
	if ( is_home() || is_loop_page() ) {

		if ( plugin() ) {
			if ( 'none' == plugin()->sidebar_in_loop() ) {
				$include = false;
			}
		}

		if ( plugin() ) {
			if (
				'side_no_first'   == plugin()->sidebar_in_loop() ||
				'bottom_no_first' == plugin()->sidebar_in_loop()
			) {
				if ( ! isset( $_GET['page'] ) ) {
					$include = false;
				}
			}
		}
	} elseif ( site()->pageNotFound() && url()->notFound() ) {
		if ( plugin() ) {
			if ( 'content' != plugin()->error_widgets() ) {
				$include = false;
			}
		}
	} elseif ( is_page() ) {

		if ( str_contains( page()->template(), 'no-sidebar' ) ) {
			$include = false;
		} elseif ( plugin() ) {
			if (
				'none' == plugin()->sidebar_in_page() &&
				! str_contains( page()->template(), 'sidebar-side' ) &&
				! str_contains( page()->template(), 'sidebar-bottom' )
			) {
				$include = false;
			}
		}

		if ( plugin() && is_front_page() ) {
			if (
				'side_no_front'   == plugin()->sidebar_in_page() ||
				'bottom_no_front' == plugin()->sidebar_in_page()
			) {
				if (
					! str_contains( page()->template(), 'sidebar-side' ) &&
					! str_contains( page()->template(), 'sidebar-bottom' )
				) {
					$include = false;
				}
			}
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

		if ( plugin() ) {
			if ( plugin()->max_related() ) {
				$max = plugin()->max_related();
			}
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

				if ( ! array_key_exists( $i, $allCatPages ) ) {
					continue;
				}
				$item = new \Page( $allCatPages[$i] );
				if ( $item->published() ) {
					$related[] = $item;
				}
			}
		} catch( \Exception $e ) {
			// Do exception?
		}
		return $related;
	}
	return false;
}

/**
 * Auto paragraph
 *
 * Replaces double line breaks with paragraph elements.
 *
 * Modified from WordPress' `wpautop` function.
 *
 * A group of regex replaces used to identify text formatted with newlines and
 * replace double line breaks with HTML paragraph tags. The remaining line breaks
 * after conversion become `<br />` tags, unless `$br` is set to '0' or 'false'.
 *
 * @since  1.0.0
 * @param  string $text The text which has to be formatted.
 * @param  bool   $br   Optional. If set, this will convert all remaining line breaks
 *                      after paragraphing.
 * @return string Text which has been converted into correct paragraph tags.
 */
function autop( $text, $br = true ) {

	$pre_tags = [];

	if ( trim( $text ) === '' ) {
		return '';
	}

	// Just to make things a little easier, pad the end.
	$text = $text . "\n";

	/*
	 * Pre tags shouldn't be touched by autop.
	 * Replace pre tags with placeholders and bring them back after autop.
	 */
	if ( str_contains( $text, '<pre' ) ) {
		$text_parts = explode( '</pre>', $text );
		$last_part  = array_pop( $text_parts );
		$text       = '';
		$i          = 0;

		foreach ( $text_parts as $text_part ) {
			$start = strpos( $text_part, '<pre' );

			// Malformed HTML?
			if ( false === $start ) {
				$text .= $text_part;
				continue;
			}

			$name = "<pre pre-tag-$i></pre>";
			$pre_tags[ $name ] = substr( $text_part, $start ) . '</pre>';

			$text .= substr( $text_part, 0, $start ) . $name;
			++$i;
		}
		$text .= $last_part;
	}

	// Change multiple <br>'s into two line breaks, which will turn into paragraphs.
	$text = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $text );

	$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

	// Add a double line break above block-level opening tags.
	$text = preg_replace( '!(<' . $allblocks . '[\s/>])!', "\n\n$1", $text );

	// Add a double line break below block-level closing tags.
	$text = preg_replace( '!(</' . $allblocks . '>)!', "$1\n\n", $text );

	// Add a double line break after hr tags, which are self closing.
	$text = preg_replace( '!(<hr\s*?/?>)!', "$1\n\n", $text );

	// Standardize newline characters to "\n".
	$text = str_replace( [ "\r\n", "\r" ], "\n", $text );

	// Collapse line breaks before and after <option> elements so they don't get autop'd.
	if ( str_contains( $text, '<option' ) ) {
		$text = preg_replace( '|\s*<option|', '<option', $text );
		$text = preg_replace( '|</option>\s*|', '</option>', $text );
	}

	/*
	 * Collapse line breaks inside <object> elements, before <param> and <embed> elements
	 * so they don't get autop'd.
	 */
	if ( str_contains( $text, '</object>' ) ) {
		$text = preg_replace( '|(<object[^>]*>)\s*|', '$1', $text );
		$text = preg_replace( '|\s*</object>|', '</object>', $text );
		$text = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $text );
	}

	/*
	 * Collapse line breaks inside <audio> and <video> elements,
	 * before and after <source> and <track> elements.
	 */
	if ( str_contains( $text, '<source' ) || str_contains( $text, '<track' ) ) {
		$text = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $text );
		$text = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $text );
		$text = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $text );
	}

	// Collapse line breaks before and after <figcaption> elements.
	if ( str_contains( $text, '<figcaption' ) ) {
		$text = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $text );
		$text = preg_replace( '|</figcaption>\s*|', '</figcaption>', $text );
	}

	// Remove more than two contiguous line breaks.
	$text = preg_replace( "/\n\n+/", "\n\n", $text );

	// Split up the contents into an array of strings, separated by double line breaks.
	$paragraphs = preg_split( '/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY );

	// Reset $text prior to rebuilding.
	$text = '';

	// Rebuild the content as a string, wrapping every bit with a <p>.
	foreach ( $paragraphs as $paragraph ) {
		$text .= '<p>' . trim( $paragraph, "\n" ) . "</p>\n";
	}

	// Under certain strange conditions it could create a P of entirely whitespace.
	$text = preg_replace( '|<p>\s*</p>|', '', $text );

	// Add a closing <p> inside <div>, <address>, or <form> tag if missing.
	$text = preg_replace( '!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $text );

	// If an opening or closing block element tag is wrapped in a <p>, unwrap it.
	$text = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $text );

	// In some cases <li> may get wrapped in <p>, fix them.
	$text = preg_replace( '|<p>(<li.+?)</p>|', '$1', $text );

	// If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
	$text = preg_replace( '|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $text );
	$text = str_replace( '</blockquote></p>', '</p></blockquote>', $text );

	// If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
	$text = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $text );

	// If an opening or closing block element tag is followed by a closing <p> tag, remove it.
	$text = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $text );

	// Optionally insert line breaks.
	if ( $br ) {

		// Normalize <br>
		$text = str_replace( [ '<br>', '<br/>' ], '<br />', $text );

		// Replace any new line characters that aren't preceded by a <br /> with a <br />.
		$text = preg_replace( '|(?<!<br />)\s*\n|', "<br />\n", $text );
	}

	// If a <br /> tag is after an opening or closing block tag, remove it.
	$text = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $text );

	// If a <br /> tag is before a subset of opening or closing block tags, remove it.
	$text = preg_replace( '!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $text );
	$text = preg_replace( "|\n</p>$|", '</p>', $text );

	// Replace placeholder <pre> tags with their original content.
	if ( ! empty( $pre_tags ) ) {
		$text = str_replace( array_keys( $pre_tags ), array_values( $pre_tags ), $text );
	}

	return $text;
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
