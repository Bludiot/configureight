<?php
/**
 * Page head section
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	is_rtl,
	asset_min,
	has_cover,
	get_cover_src
};
use function BSB_Tags\{
	load_font_files,
	favicon_tag,
	config_styles,
	scheme_stylesheet
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
<?php Theme :: plugins( 'beforeAll' ); ?>
<head data-site-head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	<link rel="preconnect" href="//fonts.adobe.com" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php echo load_font_files(); ?>
	<?php echo $load_cover; ?>

	<?php
	// Change `<html>` class to `js` if JavaScript is enabled.
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n"; ?>
	<?php echo Theme :: jquery(); ?>

	<?php echo favicon_tag(); ?>
	<?php echo Theme :: metaTagTitle(); ?>
	<?php echo Theme :: metaTagDescription(); ?>
	<?php echo Theme :: keywords( $keywords ); ?>

	<?php echo Theme :: cssBootstrapIcons(); ?>
	<?php echo Theme :: css( "assets/css/root{$suffix}.css" ); ?>
	<?php echo Theme :: css( "assets/css/style{$suffix}.css" ); ?>
	<?php if ( is_rtl() ) {
		echo Theme :: css( "assets/css/style-rtl{$suffix}.css" );
	} ?>
	<?php echo scheme_stylesheet( 'colors' ); ?>
	<?php echo scheme_stylesheet( 'fonts' ); ?>
	<?php echo config_styles(); ?>

	<?php Theme :: plugins( 'siteHead' ); ?>
</head>
