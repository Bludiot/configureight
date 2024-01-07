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

?>
<?php plugins_hook( 'beforeAll' ); ?>
<head data-site-head>
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
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	echo $helper :: jquery();
	?>

	<?php echo favicon_tag(); ?>

	<?php

	// Meta tags.
	if ( plugin() ) {
		plugins_hook( 'meta_tags' );
	} else {
		echo $helper :: metaTagTitle();
	}
	echo $helper :: metaTagDescription();
	?>

	<?php

	// Core frontend stylesheets.
	echo $helper :: css(
		[
			"assets/css/vendor/lightbox{$suffix}.css",
			"assets/css/vendor/slider{$suffix}.css",
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
	} ?>

	<?php plugins_hook( 'siteHead' ); ?>
</head>
