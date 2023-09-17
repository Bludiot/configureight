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
	asset_min
};
use function BSB_Tags\{
	favicon_tag
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

?>
<head data-site-head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	<link rel="preconnect" href="//fonts.adobe.com" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<?php
	// Change `<html>` class to `js` if JavaScript is enabled.
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n"; ?>
	<?php echo Theme :: jquery(); ?>

	<?php echo favicon_tag(); ?>
	<?php echo Theme :: metaTagTitle(); ?>
	<?php echo Theme :: metaTagDescription(); ?>
	<?php echo Theme :: keywords( $keywords ); ?>

	<?php echo Theme :: cssBootstrapIcons(); ?>
	<?php echo Theme :: css( "assets/css/style{$suffix}.css" ); ?>
	<?php if ( is_rtl() ) {
		echo Theme :: css( "assets/css/style-rtl{$suffix}.css" );
	} ?>

	<?php Theme :: plugins( 'siteHead' ); ?>
</head>
