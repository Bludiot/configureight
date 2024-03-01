<?php
/**
 * Meta data functions
 *
 * Used for title & meta tags.
 *
 * @package    Configure 8
 * @subpackage Includes
 * @category   Functions
 * @since      1.0.0
 */

namespace CFE_Meta;

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	site,
	url,
	site_domain,
	lang,
	page,
	current_lang,
	is_home,
	is_loop_page,
	is_main_loop,
	is_static_loop,
	is_loop_not_home,
	static_loop_page,
	is_cat,
	is_tag,
	is_search,
	is_page,
	is_front_page,
	page_type,
	loop_is_static,
	has_cover,
	get_cover_src
};
use function CFE_Tags\{
	loop_title,
	loop_description,
	page_description
};

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

/**
 * Get keywords
 *
 * Converts each line of the textarea field
 * to an array value.
 *
 * @since  1.0.0
 * @return mixed Returns a simple array or
 *               an empty string.
 */
function get_keywords() {

	// Return an empty string if the plugin is not installed.
	if ( ! plugin() ) {
		return '';
	}

	// Get the content of the keywords option.
	$option = plugin()->meta_keywords();

	// Return an empty string if the option is empty or only contains spaces.
	if ( 0 == strlen( $option ) || ctype_space( $option ) ) {
		return '';
	}

	/**
	 * Convert each new line of the option to an array value,
	 * removing any carriage return entities.
	 */
	$keywords = explode( "\n", str_replace( "\r", '', $option ) );

	// Return an array of keywords or phrases.
	return $keywords;
}

/**
 * Keywords
 *
 * Converts the array of keywords or phrases
 * to a comma-separated string.
 *
 * @since  1.0.0
 * @return string Returns a comma-separated string of
 *                keywords or phrases, or an empty string.
 */
function keywords() {

	// Get keywords.
	$keywords = get_keywords();

	// Convert keywords array to a comma-separated string.
	if ( is_array( $keywords ) ) {
		$keywords = implode( ', ', $keywords );
	}

	// Return the comma-separated string of keywords or empty.
	return $keywords;
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
 * Meta title
 *
 * @since  1.0.0
 * @return string
 */
function meta_title() {

	// Title separator.
	$separator = '|';
	if ( plugin() ) {
		$separator = plugin()->title_sep();
	}

	// Default to site title.
	$title = site()->title();

	if ( is_page() ) {
		$title = sprintf(
			'%s %s %s',
			$title = page()->title(),
			$separator,
			site()->title()
		);

	} elseif ( is_main_loop() ) {
		$title = sprintf(
			'%s %s %s',
			loop_title(),
			$separator,
			site()->title()
		);

	} elseif ( is_cat() ) {
		$title = sprintf(
			'%s %s %s',
			lang()->get( 'Category Index' ),
			$separator,
			site()->title()
		);

	} elseif ( is_tag() ) {
		$title = sprintf(
			'%s %s %s',
			lang()->get( 'Tag Index' ),
			$separator,
			site()->title()
		);

	} elseif ( is_search() ) {
		$title = sprintf(
			'%s %s %s',
			lang()->get( 'Search Results' ),
			$separator,
			site()->title()
		);
	}
	return $title;
}

/**
 * Meta description
 *
 * @since  1.0.0
 * @return string
 */
function meta_description() {

	// Default description.
	$desc = '';
	if ( site()->description() ) {
		$desc = site()->description();
	} elseif ( site()->slogan() ) {
		$desc = site()->slogan();
	}

	if ( is_page() ) {
		$desc = page_description( page()->key() );

	} elseif ( is_home() || is_main_loop() ) {
		$desc = loop_description();

	} elseif ( is_cat() ) {
		$cat  = new \Category( url()->slug() );
		$desc = lang()->get( "Posts in the {$cat->name()} category" );

	} elseif ( is_tag() ) {
		$tag  = new \Tag( url()->slug() );
		$desc = lang()->get( "Posts tagged '{$tag->name()}'" );

	} elseif ( is_search() ) {

		$slug  = url()->slug();
		$terms = '';
		if ( str_contains( $slug, 'search/' ) ) {
			$terms = str_replace( 'search/', '', $slug );
			$terms = str_replace( '+', ' ', $terms );
		}
		$desc = lang()->get( "Searching '{$terms}'" );
	}
	return $desc;
}

/**
 * Meta author
 *
 * @since  1.0.0
 * @return string
 */
function meta_author() {

	// Empty if not on a page.
	if ( ! is_page() ) {
		return '';
	}

	// Get user name.
	$user   = page()->user();
	$author = ucwords( page()->username() );
	if ( $user->nickname() ) {
		$author = $user->nickname();
	}
	return $author;
}

/**
 * Meta tags: standard
 *
 * @since  1.0.0
 * @return string
 */
function meta_tags_standard() {

	$html = '<!-- Standard meta tags -->' . "\r";

	// Language tag.
	$html .= sprintf(
		'<meta name="language" content="%s" />',
		current_lang()
	) . "\r";

	// Keywords tag.
	if ( ! empty( keywords() ) ) {
		$html .= sprintf(
			'<meta name="keywords" content="%s" />',
			keywords()
		) . "\r";
	}

	// Title tag.
	$html .= sprintf(
		'<meta name="title" content="%s" />',
		meta_title()
	) . "\r";

	// Description tag.
	$html .= sprintf(
		'<meta name="description" content="%s" />',
		meta_description()
	) . "\r";

	// Author tag.
	if ( is_page() ) {
		$html .= sprintf(
			'<meta name="author" content="%s" />',
			meta_author()
		) . "\r";
	}

	// Copyright tag.
	$html .= sprintf(
		'<meta name="copyright" content="%s" />',
		date( 'Y' )
	) . "\r";

	return $html;
}

/**
 * Meta tags: Schema
 *
 * @since  1.0.0
 * @return string
 */
function meta_tags_schema() {

	// Stop if no plugin or plugin option is false.
	if ( ! plugin() ) {
		return false;
	} elseif ( plugin() ) {
		if ( ! plugin()->meta_use_schema() ) {
			return false;
		}
	}

	$html = "\r" . '<!-- Schema meta tags -->' . "\r";

	// URL tag.
	$html .= sprintf(
		'<meta itemprop="url" content="%s" />',
		meta_url()
	) . "\r";

	// Name (title) tag.
	$html .= sprintf(
		'<meta itemprop="name" content="%s" />',
		meta_title()
	) . "\r";

	// Description tag.
	$html .= sprintf(
		'<meta itemprop="description" content="%s" />',
		meta_description()
	) . "\r";

	// Post/page tags.
	if ( is_page() ) {
		$html .= sprintf(
			'<meta itemprop="author" content="%s" />',
			meta_author()
		) . "\r";
		$html .= sprintf(
			'<meta itemprop="datePublished" content="%s" />',
			page()->date()
		) . "\r";
		$html .= sprintf(
			'<meta itemprop="dateModified" content="%s" />',
			page()->dateModified()
		) . "\r";
	}

	// Image tag.
	if ( has_cover() ) {
		$html .= sprintf(
			'<meta itemprop="image" content="%s" />',
			get_cover_src()
		) . "\r";
	}
	return $html;
}

/**
 * Meta tags: Open Graph
 *
 * @since  1.0.0
 * @return string
 */
function meta_tags_open_graph() {

	// Stop if no plugin or plugin option is false.
	if ( ! plugin() ) {
		return false;
	} elseif ( plugin() ) {
		if ( ! plugin()->meta_use_og() ) {
			return false;
		}
	}

	$html = "\r" . '<!-- Open Graph meta tags -->' . "\r";

	// URL tag.
	$html .= sprintf(
		'<meta property="og:url" content="%s" />',
		meta_url()
	) . "\r";

	// Language tag.
	$html .= sprintf(
		'<meta property="og:locale" content="%s" />',
		current_lang()
	) . "\r";

	// Type tag.
	$html .= sprintf(
		'<meta property="og:type" content="%s" />',
		( is_page() ? 'article' : 'website' )
	) . "\r";

	// Site title.
	$html .= sprintf(
		'<meta property="og:site_name" content="%s" />',
		site()->title()
	) . "\r";

	// Content title.
	$html .= sprintf(
		'<meta property="og:title" content="%s" />',
		meta_title()
	) . "\r";

	// Description tag.
	$html .= sprintf(
		'<meta property="og:description" content="%s" />',
		meta_description()
	) . "\r";

	// Image tag.
	if ( has_cover() ) {
		$html .= sprintf(
			'<meta property="og:image" content="%s" />',
			get_cover_src()
		) . "\r";
	}
	return $html;
}

/**
 * Meta tags: X/Twitter
 *
 * @since  1.0.0
 * @return string
 */
function meta_tags_twitter() {

	// Stop if no plugin or plugin option is false.
	if ( ! plugin() ) {
		return false;
	} elseif ( plugin() ) {
		if ( ! plugin()->meta_use_twitter() ) {
			return false;
		}
	}

	$html = "\r" . '<!-- X/Twitter meta tags -->' . "\r";

	// Card tag.
	$html .= '<meta name="twitter:card" content="summary_large_image" />' . "\r";

	// Site URL tag.
	$html .= sprintf(
		'<meta name="twitter:domain" content="%s" />',
		site_domain()
	) . "\r";

	// Site title.
	$html .= sprintf(
		'<meta name="twitter:site" content="%s" />',
		site()->title()
	) . "\r";

	// URL tag.
	$html .= sprintf(
		'<meta name="twitter:url" content="%s" />',
		meta_url()
	) . "\r";

	// Content title.
	$html .= sprintf(
		'<meta name="twitter:title" content="%s" />',
		meta_title()
	) . "\r";

	// Description tag.
	$html .= sprintf(
		'<meta name="twitter:description" content="%s" />',
		meta_description()
	) . "\r";

	// Image tag.
	if ( has_cover() ) {
		$html .= sprintf(
			'<meta name="twitter:image:src" content="%s" />',
			get_cover_src()
		) . "\r";
	}

	// Rights tag.
	$html .= sprintf(
		'<meta name="DC.Rights" content="%s" />',
		date( 'Y' )
	) . "\r";
	return $html;
}

/**
 * Meta tags: Dublin Core
 *
 * @since  1.0.0
 * @return string
 */
function meta_tags_dublin_core() {

	// Stop if no plugin or plugin option is false.
	if ( ! plugin() ) {
		return false;
	} elseif ( plugin() ) {
		if ( ! plugin()->meta_use_dublin() ) {
			return false;
		}
	}

	$html  = "\r" . '<!-- Dublin Core meta tags -->' . "\r";
	$html .= '<meta name="DC.Format" content="text/html" />' . "\r";

	// Language tag.
	$html .= sprintf(
		'<meta name="DC.Language" content="%s" />',
		current_lang()
	) . "\r";

	// Source tag.
	$html .= sprintf(
		'<meta name="DC.Source" content="%s" />',
		site_domain()
	) . "\r";

	// Creator tag.
	$html .= sprintf(
		'<meta name="DC.Creator" content="%s" />',
		site()->title()
	) . "\r";

	// Publisher tag.
	$html .= sprintf(
		'<meta name="DC.Publisher" content="%s" />',
		site()->title()
	) . "\r";

	// Identifier tag.
	$html .= sprintf(
		'<meta name="DC.Identifier" content="%s" />',
		meta_url()
	) . "\r";

	// Content title tag.
	$html .= sprintf(
		'<meta name="DC.Title" content="%s" />',
		meta_title()
	) . "\r";

	if ( ! is_home() && ! is_front_page() ) {
		// Relation tag.
		$html .= sprintf(
			'<meta name="DC.Relation" content="%s" scheme="IsPartOf" />',
			meta_url()
		) . "\r";
	}

	// Post/page tags.
	if ( is_page() ) {

		// Subject tag.
		if ( page()->category() ) {
			$html .= sprintf(
				'<meta name="DC.Subject" content="%s" />',
				page()->category()
			) . "\r";
		}

		// Contributor tag.
		$html .= sprintf(
			'<meta name="DC.Contributor" content="%s" />',
			meta_author()
		) . "\r";

		// Date tag.
		$html .= sprintf(
			'<meta name="DC.Date" content="%s" />',
			page()->date()
		) . "\r";
	}

	// Description tag.
	$html .= sprintf(
		'<meta name="DC.Description" content="%s" />',
		meta_description()
	) . "\r";

	// Image tag.
	if ( has_cover() ) {
		$html .= sprintf(
			'<meta name="twitter:image:src" content="%s" />',
			get_cover_src()
		) . "\r";
	}
	return $html;
}
