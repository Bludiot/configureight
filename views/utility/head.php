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
	scheme_stylesheet,
	custom_css
};

$suffix = asset_min();

// Get keywords from config file.
$keywords = '';
if (
	is_array( THEME_CONFIG['head'] ) &&
	array_key_exists( 'keywords', THEME_CONFIG['head'] ) &&
	is_array( THEME_CONFIG['head']['keywords'] )
) {
	$keywords = implode( ' ', THEME_CONFIG['head']['keywords'] );
}

// Preload over image.
$load_cover = '';
if ( has_cover() ) {

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
	echo $helper :: metaTagTitle();
	echo $helper :: metaTagDescription();
	echo $helper :: keywords( $keywords );
	?>

	<?php

	// Core frontend stylesheets.
	echo $helper :: css(
		[
			"assets/css/vendor/lightbox{$suffix}.css",
			"assets/css/root{$suffix}.css",
			"assets/css/style{$suffix}.css"
		],
		DOMAIN_THEME
	);

	if ( is_rtl() ) {
		echo $helper :: css( "assets/css/style-rtl{$suffix}.css" );
	}

	// Configuration stylesheets.
	echo scheme_stylesheet( 'colors' );
	echo scheme_stylesheet( 'fonts' );
	echo config_styles();
	echo custom_css();
	?>

	<?php plugins_hook( 'siteHead' ); ?>
</head>
