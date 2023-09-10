<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Init\{
	is_blog_page
};

use function BSB_Tags\{
	body_classes,
	site_schema,
	user_toolbar,
	content_template,
	footer_scripts
};

// Layout class for the `<main>` element.
$main_view = 'page-view';
if ( is_blog_page() ) {
	$main_view = 'list-view';
	if ( 'grid' == BSB_CONFIG['posts_loop'] ) {
		$main_view = 'grid-view';
	}
}

?>
<!DOCTYPE html>
<html dir="auto" class="no-js" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/">
<?php include( THEME_DIR . 'templates/utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>">

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<div id="page" class="site" itemscope="itemscope" itemtype="<?php site_schema(); ?>">

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<?php include( THEME_DIR . 'templates/header/header.php' ); ?>

		<main class="wrapper-general site-main <?php echo $main_view; ?>" itemscope itemprop="mainContentOfPage">
			<?php include( THEME_DIR . content_template() ); ?>
		</main>

		<?php include( THEME_DIR . 'templates/footer/footer.php' ); ?>
		<?php Theme :: plugins( 'pageEnd' ); ?>
	</div>
	<?php

	user_toolbar();
	Theme :: plugins( 'siteBodyEnd' );
	footer_scripts();

	?>
</body>
</html>
