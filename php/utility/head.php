<?php
/**
 * Page head section
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

	<link rel="preconnect" href="//fonts.adobe.com" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<?php
	if ( file_exists( THEME_DIR_IMG . 'favicon.png' ) ) {
		echo Theme :: favicon( 'img/favicon.png' );
	} ?>
	<?php echo Theme :: metaTagTitle(); ?>
	<?php echo Theme :: metaTagDescription(); ?>

	<?php echo Theme :: cssBootstrapIcons(); ?>
	<?php echo Theme :: css( 'css/style.min.css' ); ?>

	<?php Theme :: plugins( 'siteHead' ); ?>
</head>
