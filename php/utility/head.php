<?php
/**
 * Page head section
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

use function BSB_Init\{
	is_rtl,
	asset_min
};

$suffix = asset_min();

?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	<link rel="preconnect" href="//fonts.adobe.com" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<?php
	// Change `<html>` class to `js` if JavaScript is enabled.
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";

	if ( file_exists( THEME_DIR_IMG . 'favicon.png' ) ) {
		echo Theme :: favicon( 'img/favicon.png' );
	} ?>
	<?php echo Theme :: metaTagTitle(); ?>
	<?php echo Theme :: metaTagDescription(); ?>

	<?php echo Theme :: jquery(); ?>

	<?php echo Theme :: cssBootstrapIcons(); ?>
	<?php echo Theme :: css( "css/style{$suffix}.css" ); ?>
	<?php if ( is_rtl() ) {
		echo Theme :: css( "css/style-rtl{$suffix}.css" );
	} ?>

	<?php Theme :: plugins( 'siteHead' ); ?>
</head>
