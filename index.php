<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Tags\{
	body_classes,
	site_schema,
	user_toolbar,
	footer_scripts
};

?>
<!DOCTYPE html>
<html dir="auto" class="no-js" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/">
<?php include( THEME_DIR_PHP . 'utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>">

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<div id="page" class="site" itemscope="itemscope" itemtype="<?php site_schema(); ?>">

		<?php include( THEME_DIR_PHP . 'header/header.php' ); ?>

		<main class="wrapper-general site-main" itemscope itemprop="mainContentOfPage">
			<?php
			if ( 'page' == $WHERE_AM_I ) {
				if ( $page->template() ) {
					include( THEME_DIR_PHP . 'content/' . $page->template() . '.php' );
				} else {
					include( THEME_DIR_PHP . 'content/page.php' );
				}
			} else {
				include( THEME_DIR_PHP . 'content/home.php' );
			} ?>
		</main>

		<?php include( THEME_DIR_PHP . 'footer/footer.php' ); ?>
	</div>
	<?php

	user_toolbar();
	Theme :: plugins( 'siteBodyEnd' );
	footer_scripts();

	?>
</body>
</html>
