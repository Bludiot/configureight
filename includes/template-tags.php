<?php
/**
 * Template tags
 *
 * @package    Configure 8
 * @subpackage Includes
 * @category   Templates
 * @since      1.0.0
 */

namespace CFE_Tags;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

// Import namespaced functions.
use function CFE_Func\{
	helper,
	plugin,
	site,
	url,
	site_domain,
	lang,
	page,
	is_rtl,
	user_logged_in,
	is_home,
	is_loop_page,
	is_main_loop,
	is_static_loop,
	static_loop_page,
	is_cat,
	is_tag,
	is_search,
	is_page,
	is_404,
	is_front_page,
	profiles,
	user_slug,
	page_type,
	sticky,
	favicon_exists,
	has_logo,
	loop_data,
	loop_is_static,
	get_nav_position,
	get_config_styles,
	has_cover,
	full_cover,
	asset_min,
	text_replace,
	numbers_to_text
};

/**
 * Loading screen
 *
 * Shows a loading screen until the document
 * (web page) is fully loaded.
 *
 * @since  1.0.0
 * @return mixed Returns the screen markup or null.
 */
function page_loader() {

	// Return null if in debug mode.
	if ( defined( 'DEBUG_MODE' ) && DEBUG_MODE ) {
		return null;
	}

	if ( plugin() ) {
		if ( ! plugin()->page_loader() ) {
			return null;
		} else {
			ob_start();
			include( THEME_DIR . 'views/utility/loader.php' );
			return ob_get_clean();
		}
	}
	return null;
}

/**
 * Favicon tag
 *
 * Returns the site icon meta tag.
 *
 * @since  1.0.0
 * @return mixed Returns the icon tag or null.
 */
function favicon_tag() {

	// If plugin has icon URL.
	if ( plugin() ) {
		if ( plugin()->favicon_src() ) {

			// Get icon src.
			$favicon = plugin()->favicon_src();
		}

	// Use favicon.png in root content/uploads if found & set in options array.
	} elseif ( file_exists( PATH_UPLOADS . 'favicon.png' ) ) {
		$favicon = DOMAIN_UPLOADS . 'favicon.png';

	// Use favicon.png file in theme assets/images if found.
	} elseif ( file_exists( PATH_THEMES . site()->theme() . '/assets/images/favicon.png' ) ) {
		$favicon = DOMAIN_THEME . 'assets/images/favicon.png';
	}

	// Get the image file extension.
	$info = pathinfo( $favicon );
	$type = $info['extension'];

	$tag = null;
	if ( $favicon ) {
		$tag = sprintf(
			'<link rel="icon" href="%s" type="image/%s">',
			$favicon,
			$type
		);
	}
	return $tag;
}

/**
 * Load font files
 *
 * @since  1.0.0
 * @return mixed Returns link tags for the `<head>` or null.
 */
function load_font_files() {
	if ( ! plugin() ) {
		return null;
	}
	return plugin()->load_font_files();
}

/**
 * Config styles
 *
 * Returns a CSS block of override styles
 *
 * @since  1.0.0
 * @return string
 */
function config_styles() {

	if ( ! plugin() ) {
		return;
	}

	$nav_pos = get_nav_position();
	$styles  = '<style>:root {';

	// Loader image overlay.
	if ( ! empty( plugin()->loader_bg_color() ) ) {
		$styles .= sprintf(
			'--cfe-loader--bg-color: %s;',
			plugin()->loader_bg_color()
		);
	}

	// Loader image text.
	if ( ! empty( plugin()->loader_text_color() ) ) {
		$styles .= sprintf(
			'--cfe-loader--text-color: %s;',
			plugin()->loader_text_color()
		);
		$styles .= sprintf(
			'--cfe-loader--image--fill: %s;',
			plugin()->loader_text_color()
		);
	}

	// General spacing.
	$styles .= sprintf(
		'--cfe-wrapper--page--max-width: %spx;',
		plugin()->content_width()
	);
	$styles .= sprintf(
		'--cfe-spacing--horz: %srem;',
		plugin()->horz_spacing()
	);
	$styles .= sprintf(
		'--cfe-spacing--vert: %srem;',
		plugin()->vert_spacing()
	);

	// Maybe use font style options.
	$use_font_styles = true;
	if ( is_page() ) {
		if ( page()->template() && str_contains( page()->template(), 'font-scheme' ) ) {
			$use_font_styles = false;
		}
	}

	if ( $use_font_styles ) {

		// Font weights.
		$styles .= sprintf(
			'--cfe-body--font-weight: %s;',
			plugin()->wght_text()
		);
		$styles .= sprintf(
			'--cfe-display--font-weight: %s;',
			plugin()->wght_display()
		);
		$styles .= sprintf(
			'--cfe-heading-primary--font-weight: %s;',
			plugin()->wght_primary()
		);
		$styles .= sprintf(
			'--cfe-heading-secondary--font-weight: %s;',
			plugin()->wght_secondary()
		);

		// Letter spacing.
		$styles .= sprintf(
			'--cfe-body--letter-spacing: %sem;',
			plugin()->space_text()
		);
		$styles .= sprintf(
			'--cfe-display--letter-spacing: %sem;',
			plugin()->space_display()
		);
		$styles .= sprintf(
			'--cfe-heading-primary--letter-spacing: %sem;',
			plugin()->space_primary()
		);
		$styles .= sprintf(
			'--cfe-heading-secondary--letter-spacing: %sem;',
			plugin()->space_secondary()
		);
	}

	// Header logo width.
	$styles .= sprintf(
		'--cfe-site-logo--max-width: %spx;',
		plugin()->logo_width_std()
	);
	$styles .= sprintf(
		'--cfe-site-logo--max-width--mobile: %spx;',
		plugin()->logo_width_mob()
	);

	// Cover image desaturation.
	$styles .= sprintf(
		'--cfe-cover-img--grayscale: %s;',
		plugin()->cover_desaturate() . '%'
	);

	// Cover image overlay.
	if ( ! empty( plugin()->cover_overlay() ) ) {
		$styles .= sprintf(
			'--cfe-cover-overlay--bg-color: %s;',
			plugin()->cover_overlay()
		);
	}

	// Cover image blend.
	if ( ! empty( plugin()->cover_blend() ) ) {
		$styles .= sprintf(
			'--cfe-cover-blend--bg-color: %s;',
			plugin()->cover_blend()
		);
	}

	// Cover image text.
	if ( ! empty( plugin()->cover_text_color() ) ) {
		$styles .= sprintf(
			'--cfe-cover--text-color: %s;',
			plugin()->cover_text_color()
		);
	}

	// Cover image text shadow.
	if ( ! plugin()->cover_text_shadow() ) {
		$styles .= '--cfe-cover--text-shadow: none;';
	}

	// Modal content (lightbox).
	$styles .= sprintf(
		'--cfe-modal-overlay--bg-color: %s;',
		plugin()->modal_bg_color()
	);

	/**
	 * Main navigation position
	 *
	 * CSS Flexbox reverses in RTL languages. The nav position
	 * settings use left and right options so in RTL the flex
	 * direction needs to adjust accordingly.
	 */
	if ( 'left' === $nav_pos ) {
		if ( ! is_rtl() ) {
			$styles .= '--cfe-site-header-wrap--flex-direction: row-reverse;';
		}
		$styles .= '--cfe-site-header-wrap--flex-direction-tablet: column;';

	} elseif ( 'above' === $nav_pos ) {
		$styles .= '--cfe-site-header-wrap--flex-direction: column-reverse;';
		$styles .= '--cfe-site-header-wrap--align-items: flex-start;';
		$styles .= '--cfe-site-header-wrap--flex-direction-tablet: column-reverse;';
		$styles .= '--cfe-site-header-wrap--justify-content--tablet: center;';

	} elseif ( 'below' === $nav_pos ) {
		$styles .= '--cfe-site-header-wrap--flex-direction: column;';
		$styles .= '--cfe-site-header-wrap--align-items: flex-start;';
		$styles .= '--cfe-site-header-wrap--flex-direction-tablet: column;';
		$styles .= '--cfe-site-header-wrap--justify-content--tablet: center;';

	// Default is right.
	} else {
		if ( is_rtl() ) {
			$styles .= '--cfe-site-header-wrap--flex-direction: row-reverse;';
		}
	}
	$styles .= '}</style>';

	return $styles;
}

/**
 * Custom CSS
 *
 * CSS from the plugin appearance options.
 *
 * @since  1.0.0
 * @return mixed Returns a CSS style block or null.
 */
function custom_css() {

	if ( ! plugin() ) {
		return null;
	}

	if ( empty( plugin()->custom_css() ) ) {
		return null;
	}
	$style  = '<style>';
	$style .= plugin()->custom_css();
	$style .= '</style>';

	return $style;
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

	// Set up classes.
	$classes = [];

	// Get loop data.
	$loop_data = loop_data();

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
	if ( user_logged_in() && plugin() ) {
		if (
			'enabled'  == plugin()->user_toolbar() ||
			'frontend' == plugin()->user_toolbar()
		) {
			$classes[] = 'toolbar-active';
		}

		if ( ! plugin()->toolbar_mobile() ) {
			$classes[] = 'toolbar-mobile-hidden';
		}
	}

	// Cover image.
	if ( has_cover() ) {
		$classes[] = 'has-cover-image';
	}

	// Sticky header.
	if ( plugin() ) {
		if ( plugin()->header_sticky() ) {
			$classes[] = 'has-sticky-header';
		} elseif ( is_page() ) {
			if (
				page()->template() &&
				str_contains( page()->template(), 'sticky-header' )
			) {
				$classes[] = 'has-sticky-header';
			}
		}
	}

	// Home page.
	if ( is_home() ) {
		$classes[] = 'home';
	}

	// Static front page.
	if ( is_front_page() ) {
		$classes[] = 'home front-page';

		// If front page slider.
		if ( plugin() ) {
			if ( plugin()->posts_slider() ) {
				$classes[] = 'has-slider has-front-page-slider';
			}
			if ( 'static' == plugin()->slider_content() ) {
				$classes[] = 'has-static-slider';
			} elseif ( 'id' == plugin()->slider_content() ) {
				$classes[] = 'has-id-slider';
			} else {
				$slider_number = plugin()->slider_number();
				$classes[] = "has-posts-slider slider-{$slider_number}-posts";
			}
		}
	}

	if ( is_home() || is_loop_page() ) {
		$classes[] = 'loop';
	}

	if ( is_loop_page() ) {
		$classes[] = 'loop-not-home';
	}

	// If loop.
	if ( is_home() || is_loop_page() ) {

		// Posts loop type.
		if ( is_main_loop() ) {
			$loop_type = $loop_data['type'];
			$classes[] = "loop-type-{$loop_type}";

			// Loop first page.
			if (
				! isset( $_GET['page'] ) ||
				isset( $_GET['page'] ) && $_GET['page'] < 2
			) {
				$classes[] = 'loop-first-page';
			}
		}

		// Posts loop template.
		if ( plugin() && is_main_loop() ) {
			if ( 'grid' == plugin()->loop_style() ) {
				$classes[] = 'loop-template-grid';
			} elseif ( 'full' == plugin()->loop_style() ) {
				$classes[] = 'loop-template-full';
			} else {
				$classes[] = 'loop-template-list';
			}
		} elseif ( is_main_loop() ) {
			$classes[] = 'loop-template-list';
		}

		// Category loop template.
		if ( plugin() && is_cat() ) {
			if ( 'grid' == plugin()->cat_style() ) {
				$classes[] = 'loop-template-grid';
			} elseif ( 'full' == plugin()->cat_style() ) {
				$classes[] = 'loop-template-full';
			} else {
				$classes[] = 'loop-template-list';
			}
		} elseif ( is_cat() )  {
			$classes[] = 'loop-template-list';
		}

		// Tag loop template.
		if ( plugin() && is_tag() ) {
			if ( 'grid' == plugin()->cat_style() ) {
				$classes[] = 'loop-template-grid';
			} elseif ( 'full' == plugin()->cat_style() ) {
				$classes[] = 'loop-template-full';
			} else {
				$classes[] = 'loop-template-list';
			}
		} elseif ( is_tag() )  {
			$classes[] = 'loop-template-list';
		}

		// Loop sidebar.
		if ( plugin() ) {
			if ( 'bottom' == plugin()->sidebar_in_loop() ) {
				$classes[] = 'template-sidebar-bottom';
			} elseif ( 'bottom_no_first' == plugin()->sidebar_in_loop() ) {
				if ( isset( $_GET['page'] ) ) {
					$classes[] = 'template-sidebar-bottom';
				} else {
					$classes[] = 'template-no-sidebar';
				}
			} elseif ( 'side_no_first' == plugin()->sidebar_in_loop() ) {
				if ( isset( $_GET['page'] ) ) {
					$classes[] = 'template-sidebar';
				} else {
					$classes[] = 'template-no-sidebar';
				}
			} elseif ( plugin() && 'none' === plugin()->sidebar_in_loop() ) {
				$classes[] = 'template-no-sidebar';
			} else {
				$classes[] = 'template-sidebar';
			}
		} else {
			$classes[] = 'template-sidebar';
		}

		// Full cover first loop page if set in plugin.
		if ( plugin() ) {
			if (
				is_main_loop() && ! is_static_loop() &&
				(
					'full_first' == plugin()->loop_cover() ||
					'full_first_none' == plugin()->loop_cover()
				)
			) {
				if ( isset( $_GET['page'] ) ) {
					$classes[] = '';
				} else {
					$classes[] = 'template-full-cover';
				}
			}
		}

		// Templates for the static loop page.
		if ( is_main_loop() && $loop_data['template'] ) {
			$templates = explode( ' ', $loop_data['template'] );

			foreach ( $templates as $template ) {

				// Exclude `full-cover` template if no cover image or paged.
				if ( str_contains( $template, 'full-cover' ) ) {
					if ( ! has_cover() ) {
						$classes[] = '';
					} elseif ( isset( $_GET['page'] ) ) {
						$classes[] = '';
					} else {
						$classes[] = "template-{$template}";
					}
				} else {
					$classes[] = "template-{$template}";
				}
			}
		}

		// Sidebar position.
		if ( plugin() ) {
			if ( 'left' == plugin()->sidebar_position() ) {
				if ( 'side' == plugin()->sidebar_in_loop() ) {
					$classes[] = 'sidebar-left';
				} elseif ( 'side_no_first' == plugin()->sidebar_in_loop() ) {
					if ( isset( $_GET['page'] ) ) {
						$classes[] = 'sidebar-left';
					}
				}
			} else {
				if ( 'side' == plugin()->sidebar_in_loop() ) {
					$classes[] = 'sidebar-right';
				} elseif ( 'side_no_first' == plugin()->sidebar_in_loop() ) {
					if ( isset( $_GET['page'] ) ) {
						$classes[] = 'sidebar-right';
					}
				}
			}
		} else {
			$classes[] = 'sidebar-right';
		}
	}

	// User profiles.
	if ( profiles() ) {
		$users_slug = profiles()->users_slug();
		if ( $users_slug == url()->whereAmI() ) {
			$classes[] = 'page profile-page';
		}
	}

	// Cover color blend is available.
	if ( plugin() ) {
		if (
			'blend' == plugin()->cover_style() &&
			in_array( 'galleries', plugin()->cover_blend_use() )
		) {
			$classes[] = 'cover-blend-active';
		}
	}

	/**
	 * Color scheme
	 *
	 * Excludes pages with a manually applied color scheme.
	 */
	if ( plugin() ) {
		$scheme = plugin()->color_scheme();
		if ( is_page() ) {
			$template = page()->template();
			if ( $template && ! str_contains( $template, 'color-scheme' ) ) {
				$classes[] = "template-color-scheme-{$scheme}";
			} elseif ( empty( $template ) ) {
				$classes[] = "template-color-scheme-{$scheme}";
			}
		} else {
			$classes[] = "template-color-scheme-{$scheme}";
		}
	}

	/**
	 * Font scheme
	 *
	 * Excludes pages with a manually applied font scheme.
	 */
	if ( plugin() ) {
		$scheme = plugin()->font_scheme();
		if ( is_page() ) {
			$template = page()->template();
			if ( $template && ! str_contains( $template, 'font-scheme' ) ) {
				$classes[] = "template-font-scheme-{$scheme}";
			} elseif ( empty( $template ) ) {
				$classes[] = "template-font-scheme-{$scheme}";
			}
		} else {
			$classes[] = "template-font-scheme-{$scheme}";
		}
	}

	// If singular content.
	if ( is_page() ) {

		if ( page()->key() ) {
			$classes[] = page_type();
			$classes[] = sprintf(
				'%s--%s',
				page_type(),
				page()->key()
			);
		}

		if ( full_cover() ) {
			$classes[] = 'template-full-cover';
		}

		// Page templates.
		if ( page()->template() ) {
			$templates = explode( ' ', page()->template() );

			foreach ( $templates as $template ) {

				// Exclude `full-cover` template if no cover image or paged.
				if ( str_contains( page()->template(), 'full-cover' ) ) {
					if ( ! has_cover() ) {
						$classes[] = '';
					} elseif ( full_cover() ) {
						$classes[] = '';
					} elseif ( isset( $_GET['page'] ) ) {
						$classes[] = '';
					} else {
						$classes[] = "template-{$template}";
					}
				} else {
					$classes[] = "template-{$template}";
				}
			}
		}

		// Page sidebar.
		if ( plugin() ) {
			if ( 'bottom' == plugin()->sidebar_in_page() ) {
				if (
					! str_contains( page()->template(), 'sidebar-side' ) &&
					! str_contains( page()->template(), 'sidebar-bottom' ) &&
					! str_contains( page()->template(), 'no-sidebar' )
				) {
					$classes[] = 'template-sidebar-bottom';
				}

			} elseif ( 'bottom_no_front' == plugin()->sidebar_in_page() ) {
				if ( is_front_page() ) {
					$classes[] = 'template-no-sidebar';
				} else {
					if (
						! str_contains( page()->template(), 'sidebar-side' ) &&
						! str_contains( page()->template(), 'sidebar-bottom' ) &&
						! str_contains( page()->template(), 'no-sidebar' )
					) {
						$classes[] = 'template-sidebar-bottom';
					}
				}
			} elseif ( 'side_no_front' == plugin()->sidebar_in_page() ) {
				if ( is_front_page() ) {
					$classes[] = 'template-no-sidebar';
				} else {
					if (
						! str_contains( page()->template(), 'sidebar-side' ) &&
						! str_contains( page()->template(), 'sidebar-bottom' ) &&
						! str_contains( page()->template(), 'no-sidebar' )
					) {
						$classes[] = 'template-sidebar';
					}
				}
			} elseif ( 'none' === plugin()->sidebar_in_page() ) {
				if (
					! str_contains( page()->template(), 'sidebar-side' ) &&
					! str_contains( page()->template(), 'sidebar-bottom' ) &&
					! str_contains( page()->template(), 'no-sidebar' )
				) {
					$classes[] = 'template-no-sidebar';
				}
			} else {
				$classes[] = 'template-sidebar';
			}
		} else {
			$classes[] = 'template-sidebar';
		}

		// Sidebar position.
		if ( plugin() ) {
			if ( 'left' == plugin()->sidebar_position() ) {
				if ( 'side' == plugin()->sidebar_in_page() ) {
					$classes[] = 'sidebar-left';
				} elseif ( 'side_no_front' == plugin()->sidebar_in_page() ) {
					if ( ! is_front_page() ) {
						$classes[] = 'sidebar-left';
					}
				}
			} else {
				if ( 'side' == plugin()->sidebar_in_page() ) {
					$classes[] = 'sidebar-right';
				} elseif ( 'side_no_front' == plugin()->sidebar_in_page() ) {
					if ( ! is_front_page() ) {
						$classes[] = 'sidebar-right';
					}
				}
			}
		} else {
			$classes[] = 'sidebar-right';
		}
	}

	// 404 error page.
	if ( is_404() ) {
		$classes[] = 'url-error-page';
	}

	// Main navigation position.
	$nav_position = get_nav_position();
	$classes[]    = "main-nav-{$nav_position}";

	// Search icon.
	if ( plugin() ) {
		if ( plugin()->search_icon() ) {
			$classes[] = 'search-has-icon';
		}
	}

	// Logo location.
	if ( site()->logo() && plugin() ) {
		if ( 'after' == plugin()->logo_location() ) {
			$classes[] = 'logo-after-text';
		} elseif ( 'above' == plugin()->logo_location() ) {
			$classes[] = 'logo-above-text';
		} elseif ( 'below' == plugin()->logo_location() ) {
			$classes[] = 'logo-below-text';
		} else {
			$classes[] = 'logo-before-text';
		}
	} elseif ( site()->logo() ) {
		$classes[] = 'logo-before-text';
	}

	// Search pages.
	if ( is_search() ) {
		$classes[] = 'search loop loop-type-search loop-template-list';

		// Sidebar position.
		if ( plugin() ) {
			if ( 'left' == plugin()->sidebar_position() ) {
				$classes[] = 'sidebar-left';
			} else {
				$classes[] = 'sidebar-right';
			}
		}
	}

	// Return a string of space-separated classes.
	return implode( ' ', $classes );
}

/**
 * Page Schema
 *
 * Conditional Schema attributes for `<div id="page"`.
 *
 * @since  1.0.0
 * @return string Returns the relevant itemtype.
 */
function page_schema() {

	if ( is_search() ) {
			echo 'https://schema.org/SearchResultsPage';
			return;
	}

	if ( profiles() ) {
		if ( profiles()->users_slug() == url()->whereAmI() ) {
			echo 'https://schema.org/ProfilePage';
			return;
		}
	}

	// Change page slugs and template names as needed.
	if ( str_contains( page()->template(), 'profile' ) ) {
		$itemtype = 'ProfilePage';

	} elseif (
		'about'    == page()->slug() ||
		'about-us' == page()->slug() ||
		str_contains( page()->template(), 'about' )
	) {
		$itemtype = 'AboutPage';

	} elseif (
		'contact'    == page()->slug() ||
		'contact-us' == page()->slug() ||
		str_contains( page()->template(), 'contact' )
	) {
		$itemtype = 'ContactPage';

	} elseif (
		'faq'  == page()->slug() ||
		'faqs' == page()->slug() ||
		str_contains( page()->template(), 'faq' )
	) {
		$itemtype = 'QAPage';

	} elseif (
		'cart' == page()->slug() ||
		'shopping-cart' == page()->slug() ||
		str_contains( page()->template(), 'cart' ) ||
		str_contains( page()->template(), 'checkout' )
	) {
		$itemtype = 'CheckoutPage';

	} elseif (
		is_main_loop() ||
		( is_home() && ! site()->homepage() )
	) {
		if ( plugin() ) {
			if ( 'news' == plugin()->loop_type() ) {
				$itemtype = 'WebPage';
			}
		} else {
			$itemtype = 'Blog';
		}

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
 * @param  array $args Arguments to be passed.
 * @param  array $defaults Default arguments.
 * @return string Returns the header markup.
 */
function page_header( $args = null, $defaults = [] ) {

	// Default arguments.
	$defaults = [
		'wrapper'     => 'header',
		'heading'     => 'h1',
		'description' => page()->description(),
		'sticky_icon' => ''
	];

	// Maybe override defaults.
	if ( is_array( $args ) && $args ) {
		$args = array_merge( $defaults, $args );
	} else {
		$args = $defaults;
	}

	/**
	 * Do not use `<header>` element for the
	 * `full-cover` page template because
	 * this will be used inside the site
	 * header; a `<header>` element must not
	 * contain another `<header>` element.
	 */
	if ( full_cover() ) {
		$args['wrapper'] = 'div';
	}

	// Site title is `h1` on front page; only one per page.
	if ( is_front_page() ) {
		$args['heading'] = 'h2';
	}

	// If the page is sticky.
	if ( page()->sticky() ) {
		$args['sticky_icon'] = sticky_icon( 'false', 'sticky-icon-heading' ) . ' ';
	}

	$html = sprintf(
		'<%s class="page-header" data-page-header>',
		$args['wrapper']
	);
	$html .= sprintf(
		'<%s class="page-title">%s%s</%s>',
		$args['heading'],
		$args['sticky_icon'],
		page()->title(),
		$args['heading']
	);

	if ( ! empty( $args['description'] ) && ! ctype_space( $args['description'] ) ) {
		if ( ! str_contains( page()->template(), 'no-page-description' ) ) {
			$html .= sprintf(
				'<p class="page-description page-description-single">%s</p>',
				$args['description']
			);
		}
	}
	$html .= "</{$args['wrapper']}>";

	return $html;
}

/**
 * Cover header
 *
 * Returns the page title and description
 *
 * @since  1.0.0
 * @param  array $args Arguments to be passed.
 * @param  array $defaults Default arguments.
 * @return string Returns the header markup.
 */
function cover_header( $args = null, $defaults = [] ) {

	$loop_data = loop_data();

	// Default arguments.
	$defaults = [
		'loop_data'   => $loop_data,
		'heading_el'  => 'h1',
		'page_title'  => '',
		'description' => ''
	];

	// Maybe override defaults.
	if ( is_array( $args ) && $args ) {
		$args = array_merge( $defaults, $args );
	} else {
		$args = $defaults;
	}

	if ( is_page() ) {
		$args['page_title']  = page()->title();
		$args['description'] = page()->description();
	}

	// Site title is `h1` on front page; only one per page.
	if ( is_home() || is_front_page() ) {
		$args['heading_el'] = 'h2';
	}

	// Conditional heading & description.
	if (
		is_main_loop() &&
		'page' == $loop_data['location']
	) {
		$class = 'loop-page-description';
		$args['page_title']  = loop_title();
		$args['description'] = loop_description();

	} elseif (
		is_home() ||
		is_main_loop()
	) {
		$class = 'loop-page-description';
		$args['page_title']  = loop_label();
		$args['description'] = loop_description();

	} elseif ( is_cat() ) {
		$get_cat = new \Category( url()->slug() );
		$class = 'category-page-description';
		$args['page_title']  = $get_cat->name();
		$args['description'] = text_replace( 'posts-loop-desc-cat', $get_cat->name() );

	} elseif ( is_tag() ) {
		$get_tag = new \Tag( url()->slug() );
		$class = 'tag-page-description';
		$args['page_title']  = $get_tag->name();
		$args['description'] = text_replace( 'posts-loop-desc-tag', $get_tag->name() );
	} elseif ( is_search() ) {

		$slug  = url()->slug();
		$terms = '';
		if ( str_contains( $slug, 'search/' ) ) {
			$terms = str_replace( 'search/', '', $slug );
			$terms = str_replace( '+', ' ', $terms );
		}

		$args['page_title']  = lang()->get( 'Search Results' );
		$args['description'] = sprintf(
			'%s "%s"',
			lang()->get( 'Searching' ),
			 $terms
		);
	} elseif ( profiles() ) {
		if ( profiles()->users_slug() == url()->whereAmI() ) {
			$user    = user_slug();
			$tagline = profiles()->getValue( 'tagline_' . $user );
			$class = 'user-page-description';
			$args['page_title']  = \UPRO_Tags\user_display_name( $user );
			if ( $tagline ) {
				$args['description'] = htmlspecialchars_decode( $tagline );
			}
		}
	}

	$html  = '<div class="cover-header" data-cover-header>';
	$html .= sprintf(
		'<%s class="cover-title">%s</%s>',
		$args['heading_el'],
		$args['page_title'],
		$args['heading_el']
	);

	if ( ! empty( $args['description'] ) && ! ctype_space( $args['description'] ) ) {
		$html .= sprintf(
			'<p class="cover-description">%s</p>',
			$args['description']
		);
	}

	// Full cover down icon.
	$icon = 'angle-down-light';
	if ( plugin() ) {
		if ( plugin()->cover_icon() ) {
			$icon = plugin()->cover_icon();
		} else {
			$icon = '';
		}
	}

	if ( full_cover() ) {
		$html .= sprintf(
			'<a href="#content" class="button intro-scroll %s hide-if-no-js"><span class="screen-reader-text">%s</span>%s</a>',
			$icon,
			lang()->get( 'Scroll to Content' ),
			icon( $icon )
		);
	}
	$html .= '</div>';

	return $html;
}

/**
 * Print site logo
 *
 * @since  1.0.0
 * @return mixed Returns null if theme plugin not installed.
 */
function site_logo() {

	if ( ! has_logo() ) {
		return null;
	}

	// Get logo(s) from plugin uploads or SVG.
	if ( plugin() ) :

	$standard = plugin()->standard_logo_src();
	$cover    = plugin()->cover_logo_src();
	$std_svg  = plugin()->logo_standard_svg();
	$cov_svg  = plugin()->logo_cover_svg();

	?>
	<div class="site-logo" data-site-logo>
		<figure class="standard-logo" style="display: <?php echo ( ( $cover || $cov_svg ) && full_cover() ? 'none' : 'block' ); ?>">
			<a href="<?php echo site_domain(); ?>">
			<?php if ( ! empty( $std_svg ) ) : ?>
				<?php echo htmlspecialchars_decode( $std_svg ); ?>
			<?php else : ?>
				<img src="<?php echo plugin()->standard_logo_src(); ?>" alt="<?php echo site()->title(); ?>">
			<?php endif; ?>
			</a>
			<figcaption class="screen-reader-text"><?php echo site()->title(); ?></figcaption>
		</figure>
		<?php if ( has_logo( 'cover' ) && full_cover() ) : ?>
		<figure class="cover-logo">
			<a href="<?php echo site_domain(); ?>">
			<?php if ( ! empty( $cov_svg ) ) : ?>
				<?php echo htmlspecialchars_decode( $cov_svg ); ?>
			<?php else : ?>
				<img src="<?php echo plugin()->cover_logo_src(); ?>" alt="<?php echo site()->title(); ?>">
			<?php endif; ?>
			</a>
			<figcaption class="screen-reader-text"><?php echo site()->title(); ?></figcaption>
		</figure>
		<?php endif; ?>
	</div>
	<?php

	// Use default logo upload if theme plugin is not installed.
	else : ?>
	<div class="site-logo" data-site-logo>
		<figure class="standard-logo">
			<a href="<?php echo site_domain(); ?>">
				<img src="<?php echo site()->logo(); ?>" alt="<?php echo site()->title(); ?>">
			</a>
			<figcaption class="screen-reader-text"><?php echo site()->title(); ?></figcaption>
		</figure>
	</div>
	<?php endif;
}

/**
 * Menu toggle
 *
 * Returns the mobile menu icon or text.
 *
 * @since  1.0.0
 * @return string
 */
function menu_toggle( $toggle = '' ) {

	if ( plugin() ) {

		// If an icon option is set (plugin default is bars).
		if ( 'none' != plugin()->main_nav_icon() ) {

			// Bars icon.
			$icon  = 'bars';
			$class = 'nav-icon-bars';

			// Dots icon.
			if ( 'dots' == plugin()->main_nav_icon() ) {
				$icon  = 'dots-h';
				$class = 'nav-icon-dots';
			}
			return icon( $icon, true, $class );

		// If no icon option and custom text in the tag..
		} elseif ( 'none' == plugin()->main_nav_icon() && ! empty( $toggle ) ) {
			return $toggle;
		}
	}

	// Default, text.
	return lang()->get( 'Menu' );
}

/**
 * Page ID
 *
 * Returns an ID based on the page type
 * and the page key.
 *
 * @since  1.0.0
 * @return mixed Returns the page ID or null.
 */
function page_id() {

	// Null if in search results (global errors).
	if ( is_search() ) {
		return null;
	}

	if ( profiles() ) {
		if ( profiles()->users_slug() == url()->whereAmI() ) {
			return null;
		}
	}

	// Conditional page ID, static or not.
	$id = '';
	if (
		( is_loop_page() && ! is_home() ) ||
		( is_home() && ! is_page() )
	) {
		$id = 'loop-page';
		if ( ! isset( $_GET['page'] ) ) {
			$id .= '-' . 1;
		} else {
			$id .= '-' . $_GET['page'];
		}

	} elseif ( page()->isStatic() && ! is_loop_page() ) {
		$id = 'page-' . page()->key();
	} else {
		$id = 'post-' . page()->key();
	}

	// String replace not necessary but just in case...
	return strtolower( str_replace( [ '_', ' ' ], '-', $id ) );
}

/**
 * Content template
 *
 * @since  1.0.0
 * @return string Returns the relevant template.
 */
function content_template() {

	// Blog template when a static home page is used.
	if ( is_page() && page()->slug() == str_replace( '/', '', site()->getField( 'uriBlog' ) ) ) {

		if ( is_main_loop() && plugin() ) {
			if ( 'grid' == loop_style() ) {
				$template = 'views/content/posts-grid.php';
			} elseif ( 'full' == loop_style() ) {
				$template = 'views/content/posts-full.php';
			} else {
				$template = 'views/content/posts-list.php';
			}
		} elseif ( is_main_loop() ) {
			$template = 'views/content/posts-list.php';
		}

	// Page templates.
	} elseif ( is_page() ) {

		// Static home page.
		if ( site()->getField( 'homepage' ) && page()->slug() == site()->getField( 'homepage' ) ) {
			$template = 'views/content/front-page.php';

		/**
		 * Static page with template applied, excluding some templates.
		 * Sidebar templates are excluded because sidebar location
		 * is achieved with CSS based on body class.
		 *
		 * @see body_classes()
		 *
		 * The `full-cover` template is excluded because a different
		 * site header is used prior to calling this function.
		 */
		} elseif ( page()->template() && page()->isStatic() ) {
			$template = 'views/content/' . str_replace( [ ' ', 'full-cover', 'no-sidebar', 'sidebar-bottom' ], '', page()->template() ) . '.php';
			if ( file_exists( THEME_DIR . $template ) ) {
				$template = $template;
			} else {
				$template = 'views/content/page.php';
			}

		// Static page.
		} elseif ( page()->isStatic() ) {
			$template = 'views/content/page.php';

		// Sticky page (post).
		} elseif ( page()->sticky() ) {
			$template = 'views/content/sticky.php';

		// Default (post) page.
		} else {
			$template = 'views/content/post.php';
		}
	} elseif ( is_cat() && plugin() ) {
		if ( 'grid' == plugin()->cat_style() ) {
			$template = 'views/content/posts-grid.php';
		} elseif ( 'full' == plugin()->cat_style() ) {
			$template = 'views/content/posts-full.php';
		} else {
			$template = 'views/content/posts-list.php';
		}
	} elseif ( is_cat() ) {
		$template = 'views/content/posts-list.php';

	} elseif ( is_tag() && plugin() ) {
		if ( 'grid' == plugin()->tag_style() ) {
			$template = 'views/content/posts-grid.php';
		} elseif ( 'full' == plugin()->tag_style() ) {
			$template = 'views/content/posts-full.php';
		} else {
			$template = 'views/content/posts-list.php';
		}
	} elseif ( is_tag() ) {
		$template = 'views/content/posts-list.php';

	// Default to posts loop.
	} else {
		if ( is_main_loop() && plugin() ) {
			if ( 'grid' == loop_style() ) {
				$template = 'views/content/posts-grid.php';
			} elseif ( 'full' == loop_style() ) {
				$template = 'views/content/posts-full.php';
			} else {
				$template = 'views/content/posts-list.php';
			}
		} else {
			$template = 'views/content/posts-list.php';
		}
	}
	return $template;
}

/**
 * Loop style
 *
 * Gets loop style from the theme plugin.
 *
 * @since  1.0.0
 * @return string Returns the loop style.
 */
function loop_style() {

	// Conditional template.
	$template = 'list';
	if ( plugin() ) {
		if ( is_main_loop() ) {
			if ( 'grid' === plugin()->loop_style() ) {
				$template = 'grid';
			} elseif ( 'full' === plugin()->loop_style() ) {
				$template = 'full';
			}
		} elseif ( is_cat() ) {
			if ( 'grid' === plugin()->cat_style() ) {
				$template = 'grid';
			} elseif ( 'full' === plugin()->cat_style() ) {
				$template = 'full';
			}
		} elseif ( is_tag() ) {
			if ( 'grid' === plugin()->tag_style() ) {
				$template = 'grid';
			} elseif ( 'full' === plugin()->tag_style() ) {
				$template = 'full';
			}
		}
	}
	return $template;
}

/**
 * Loop post count
 *
 * Gets loop post count from the loop data.
 *
 * @since  1.0.0
 * @return integer Returns the loop post count.
 */
function loop_post_count() {
	$loop_data = loop_data();
	return $loop_data['post_count'];
}

/**
 * Loop show posts
 *
 * Gets loop posts per page from the loop data.
 *
 * @since  1.0.0
 * @return integer Returns the loop posts per page.
 */
function loop_show_posts() {
	$loop_data = loop_data();
	return $loop_data['show_posts'];
}

/**
 * Loop location
 *
 * Gets loop location from the loop data.
 *
 * @since  1.0.0
 * @return string Returns the loop location.
 */
function loop_location() {
	$loop_data = loop_data();
	return $loop_data['location'];
}

/**
 * Loop key
 *
 * Gets loop key from the loop data.
 *
 * @since  1.0.0
 * @return mixed Returns the loop key or false.
 */
function loop_key() {
	$loop_data = loop_data();
	return $loop_data['key'];
}

/**
 * Loop URL
 *
 * Gets loop URL from the loop data.
 *
 * @since  1.0.0
 * @return string Returns the loop URL.
 */
function loop_url() {
	$loop_data = loop_data();
	return $loop_data['url'];
}

/**
 * Loop slug
 *
 * Gets loop slug from the loop data.
 *
 * @since  1.0.0
 * @return string Returns the loop slug.
 */
function loop_slug() {
	$loop_data = loop_data();
	return $loop_data['slug'];
}

/**
 * Loop template
 *
 * Gets loop template from the loop data.
 *
 * @since  1.0.0
 * @return mixed Returns the loop template or false.
 */
function loop_template() {
	$loop_data = loop_data();
	return $loop_data['template'];
}

/**
 * Loop type
 *
 * Gets loop type from the loop data.
 *
 * @since  1.0.0
 * @return string Returns the loop type.
 */
function loop_type() {
	$loop_data = loop_data();
	return $loop_data['type'];
}

/**
 * Loop title
 *
 * Gets loop title from the loop data.
 *
 * @since  1.0.0
 * @return string Returns the loop title.
 */
function loop_title() {
	$loop_data = loop_data();
	return $loop_data['title'];
}

/**
 * Loop description
 *
 * Gets loop description from the loop data.
 *
 * @since  1.0.0
 * @return string Returns the loop description.
 */
function loop_description() {
	$loop_data = loop_data();

	return $loop_data['description'];
}

/**
 * Loop cover image
 *
 * Gets loop cover image from the loop data.
 *
 * @since  1.0.0
 * @return mixed Returns the loop cover image or false.
 */
function loop_cover() {
	$loop_data = loop_data();
	return $loop_data['cover'];
}

/**
 * Loop label
 *
 * @since  1.0.0
 * @return string
 */
function loop_label() {

	$static = static_loop_page();
	$label  = lang()->get( 'Blog' );
	$field  = site()->getField( 'uriBlog' );

	if ( plugin() ) {
		if ( $static ) {
			if ( 'title' == plugin()->main_nav_labels() ) {
				$label = $static->title();
			} else {
				$label = ucwords(
					str_replace( [ '-', '_' ], ' ', $static->slug() )
				);
			}
		} elseif ( plugin()->loop_title() ) {
			$label = plugin()->loop_title();
		} elseif ( $field && '/blog/' != $field ) {
			$label = str_replace( [ '/', '-', '_' ], ' ', $field );
		}
	} elseif ( $static ) {
		$label = str_replace( [ '/', '-', '_' ], ' ', $static->slug() );
	} elseif ( $field ) {
		$label = str_replace( [ '/', '-', '_' ], ' ', $field );
	}
	return ucwords( $label );
}

/**
 * Navigation loop label
 *
 * @since  1.0.0
 * @return string
 */
function nav_loop_label() {

	if ( plugin() ) {
		if ( plugin()->main_nav_loop_label() ) {
			$label = ucwords( plugin()->main_nav_loop_label() );
		} else {
			$label = loop_label();
		}
	} else {
		$label = loop_label();
	}
	return $label;
}

/**
 * Loop page header
 *
 * Prints a header section in a posts loop
 * page: posts, category, tag.
 *
 * @since  1.0.0
 * @return mixed
 */
function loop_page_header() {

	// Null if in search results (global errors).
	if ( is_search() ) {
		return null;
	}

	// Header variables.
	$heading     = '';
	$description = '';
	$class       = '';
	$format_slug =  ucwords( str_replace( [ '-', '_' ], '', url()->slug() ) );
	$loop_data   = loop_data();
	$loop_page   = '';

	// If on a loop page other than the first.
	if ( isset( $_GET['page'] ) && $_GET['page'] > 1 ) {
		$loop_page = sprintf(
			' &rsaquo; %s %s',
			lang()->get( 'page' ),
			$_GET['page']
		);
	}

	// Conditional heading & description.
	if ( is_home() ) {
		$class       = 'posts-page-description';
		$heading     = ucwords( $loop_data['title'] . $loop_page );
		$description = loop_description();
		if ( plugin() ) {
			if ( plugin()->loop_type() ) {
				$heading = sprintf(
					'%s %s%s',
					ucwords( plugin()->loop_type() ),
					lang()->get( 'Posts' ),
					ucwords( $loop_page )
				);
			}
		}

	} elseif ( is_main_loop() ) {
		$class       = 'posts-page-description';
		$heading     = ucwords( $loop_data['title'] . $loop_page );
		$description = loop_description();

	} elseif ( is_cat() ) {
		$get_cat     = new \Category( url()->slug() );
		$class       = 'category-page-description';
		$heading     = $get_cat->name();
		$description = text_replace( 'posts-loop-desc-cat', $get_cat->name() );

	} elseif ( is_tag() ) {
		$get_tag     = new \Tag( url()->slug() );
		$class       = 'tag-page-description';
		$heading     = $get_tag->name();
		$description = text_replace( 'posts-loop-desc-tag', $get_tag->name() );
	}

	// SEt up the header markup.
	$html = '<header class="page-header loop-page-header">';

	if ( ! empty( $heading ) && ! ctype_space( $heading ) ) {
		$html .= sprintf(
			'<h3 class="loop-page-heading">%s</h3>',
			$heading
		);
	}

	if ( ! empty( $description ) && ! ctype_space( $description ) ) {
		$html .= sprintf(
			'<p class="page-description loop-page-description %s">%s</p>',
			$class,
			$description
		);
	}
	$html .= '</header>';

	// Print nothing if site home or singular page.
	if ( is_page() ) {
		return '';
	}
	return $html;
}

/**
 * Get SVG icon
 *
 * @since  1.0.0
 * @param  string $$file Name of the SVG file.
 * @return array
 */
function icon( $filename = '', $wrap = false, $class = '' ) {

	$exists = file_exists( sprintf(
		THEME_DIR . 'assets/images/svg-icons/%s.svg',
		$filename
	) );
	if ( ! empty( $filename ) && $exists ) {

		if ( true == $wrap ) {
			return sprintf(
				'<span class="theme-icon %s">%s</span>',
				$class,
				file_get_contents( THEME_DIR . "assets/images/svg-icons/{$filename}.svg" )
			);
		} else {
			return file_get_contents( THEME_DIR . "assets/images/svg-icons/{$filename}.svg" );
		}
	}
	return '';
}

/**
 * Sticky icon
 *
 * @since  1.0.0
 * @param  boolean $echo Whether to echo or return the icon.
 * @param  string $class Add classes to the icon markup.
 * @param  string $title Text for the title attribute.
 * @return mixed Echoes the icon, or returns the icon or empty.
 */
function sticky_icon( $echo = '', $class = '', $title = '' ) {

	if ( is_search() ) {
		return false;
	}

	$icon = '';
	if ( page()->sticky() ) {
		$icon = sprintf(
			'<span class="theme-icon sticky-icon %s" title="%s" role="img">%s</span><span class="screen-reader-text">%s </span>',
			$class,
			$title,
			icon( 'sticky' ),
			lang()->get( 'Sticky Post:' )
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
 * Article type
 *
 * For use in Schema tags.
 *
 * @since  1.0.0
 * @return mixed Returns the article type or false.
 */
function article_type() {

	$article_type = 'BlogPosting';
	if ( plugin() ) {
		if ( 'news' == plugin()->loop_type() ) {
			$article_type = 'NewsArticle';
		}
	}
	return $article_type;
}

/**
 * Page description
 *
 * Gets the page description or
 * an excerpt of the content.
 *
 * @since  1.0.0
 * @return string Returns the description.
 */
function page_description( $key = '' ) {

	if ( empty( $key ) ) {
		$key = page()->key();
	}

	$page = buildPage( $key );

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
 * @return boolean Returns true if tags are attached.
 */
function has_tags() {

	if ( page()->tags( true ) ) {
		return true;
	}
	return false;
}

/**
 * Get page author
 *
 * @since  1.0.0
 * @return string
 */
function get_author() {

	$user   = page()->username();
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
 * Loop pagination
 *
 * @since  1.0.0
 * @return mixed Returns the navigation markup or false.
 */
function get_loop_pagination() {

	if ( plugin() ) {
		if ( 'numerical' == plugin()->loop_paged() ) {
			ob_start();
			include( THEME_DIR . 'views/navigation/paged-numerical.php' );
			return ob_get_clean();
		}
	} else {
		ob_start();
		include( THEME_DIR . 'views/navigation/paged-prev-next.php' );
		return ob_get_clean();
	}
}

/**
 * Previous key
 *
 * @since  1.0.0
 * @global object $pages Pages class
 * @return mixed
 */
function prev_key() {

	// Access global variables.
	global $pages;

	// Stop if on a static page.
	if ( page()->isStatic() ) {
		return false;
	}

	$current  = page()->key();
	$keys     = $pages->getPublishedDB( true );
	$position = array_search( $current, $keys ) + 1;

	if ( isset( $keys[$position] ) ) {
		return $keys[$position];
	}
	return false;
}

/**
 * Next key
 *
 * @since  1.0.0
 * @global object $pages Pages class
 * @return mixed
 */
function next_key() {

	// Access global variables.
	global $pages;

	// Stop if on a static page.
	if ( page()->isStatic() ) {
		return false;
	}

	$current  = page()->key();
	$keys     = $pages->getPublishedDB( true );
	$position = array_search( $current, $keys ) - 1;

	if ( isset( $keys[$position] ) ) {
		return $keys[$position];
	}
	return false;
}

/**
 * Social navigation
 *
 * Displays a list of links to social sites.
 *
 * @since  1.0.0
 * @param  boolean $wrap Whether to wrap the list in a `<nav>` element.
 * @return string
 */
function social_nav( $wrap = true, $items_only = false ) {

	$links = helper() :: socialNetworks();
	if ( $links ) :

	if ( $wrap ) {
		echo '<nav class="social-navigation" data-page-navigation>';
	}
		if ( ! $items_only ) {
			echo '<ul class="nav-list social-nav-list">';
		}
			foreach ( $links as $link => $label ) :

			// Get icon SVG file.
			$icon = '';
			$file = THEME_DIR . 'assets/images/svg-icons/' . $link . '.svg';
			if ( file_exists( $file ) ) {
				$icon = file_get_contents( $file );
			}
			if ( 'Twitter' === $label ) {
				$label = 'X/Twitter';
			}
			?>
			<li>
				<a href="<?php echo site()->{$link}(); ?>" target="_blank" rel="noreferrer noopener" title="<?php echo $label; ?>" data-tooltip>
					<span class="theme-icon social-icon"><?php echo $icon; ?></span>
					<span class="screen-reader-text social-label"><?php echo $label; ?></span>
				</a>
			</li>
			<?php endforeach;
		if ( ! $items_only ) {
			echo '</ul>';
		}
	if ( $wrap ) {
		echo '</nav>';
	}
	endif;
}

/**
 * Pint footer scripts
 *
 * @since  1.0.0
 * @return void
 */
function footer_scripts() {

	$suffix = asset_min();
	echo helper() :: js(
		[
			"assets/js/fitvids{$suffix}.js",
			"assets/js/slider{$suffix}.js",
			"assets/js/lightbox{$suffix}.js",
			"assets/js/tooltips{$suffix}.js",
			"assets/js/theme{$suffix}.js"
		],
		DOMAIN_THEME
	);

	?>
	<script>
	jQuery(document).ready( function($) {

		// Tooltips.
		$( '[data-tooltip]' ).tooltipster({
			distance : 5,
			delay : 150,
			animationDuration : 150,
			theme : 'cfe-tooltips'
		});
	});
	</script>
	<?php
}
