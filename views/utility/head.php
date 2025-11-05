<?php
/**
 * Page head section
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	plugins_hook,
	is_rtl,
	meta_url,
	asset_min,
	has_cover,
	get_cover_src
};
use function CFE_Tags\{
	load_font_files,
	favicon_tag,
	config_styles,
	custom_css
};

$suffix = asset_min();

// Preload cover image.
$load_cover = '';
$type       = '';
if ( has_cover() && ! empty( get_cover_src() ) ) {

	// File type.
	$type = get_headers( get_cover_src(), 1 )['Content-Type'];

	// Preload tag.
	$load_cover = sprintf(
		'<link rel="preload" as="image" href="%s" type="%s">',
		get_cover_src(),
		$type
	);
}

$noindex = 'max-image-preview:large';
if ( plugin() ) {
	if ( plugin()->meta_noindex() ) {
		$noindex = 'noindex, nofollow';
	}
}

?>
<?php plugins_hook( 'beforeAll' ); ?>
<head data-site-head>

	<meta name='robots' content='<?php echo $noindex; ?>' />
	<meta charset="<?php echo CHARSET; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	<?php // Preconnect and preload files. ?>
	<link rel="preconnect" href="//fonts.adobe.com" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php echo load_font_files(); ?>
	<?php echo $load_cover; ?>

	<?php

	// Change `<html>` 'no-js' class to 'js' if JavaScript is enabled.
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\r";
	echo $helper :: jquery();

	echo "\r" . favicon_tag() . "\r\r"; ?>
	<link rel="canonical" href="<?php echo meta_url(); ?>" />
	<?php

	// Metadata tag.
	if ( getPlugin( 'Meta_Data' ) && $plugins['title_tag'] ) {
		plugins_hook( 'title_tag' ) . "\r\r";
	} else {
		echo $helper :: metaTagTitle();
	}
	echo "\r";

	// Core frontend stylesheets.
	echo $helper :: css(
		[
			"assets/css/lightbox{$suffix}.css",
			"assets/css/slider{$suffix}.css",
			"assets/css/tooltips{$suffix}.css",
			"assets/css/style{$suffix}.css"
		],
		DOMAIN_THEME
	);

	if ( is_rtl() ) {
		echo $helper :: css( "assets/css/style-rtl{$suffix}.css" );
	}

	// Configuration styles.
	if ( plugin() ) {
		echo plugin()->scheme_stylesheet( 'colors' );
		echo plugin()->scheme_stylesheet( 'fonts' );
		echo config_styles();
		plugins_hook( 'color_scheme_vars' );
		echo custom_css();
	}

	echo plugins_hook( 'siteHead' ); ?>
</head>
