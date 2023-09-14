<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
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
	$main_view = 'blog-view list-view';
	if ( 'grid' == BSB_CONFIG['posts_loop'] ) {
		$main_view = 'blog-view grid-view';
	}
}

?>
<!DOCTYPE html>
<html dir="auto" class="no-js" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/">
<?php include( THEME_DIR . 'templates/utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>">

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<div class="page-wrap" itemscope="itemscope" itemtype="<?php site_schema(); ?>">

		<?php include( THEME_DIR . 'templates/header/header.php' ); ?>

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<div id="content" class="wrapper-general content-wrapper">
			<main class="page-main <?php echo $main_view; ?>" itemscope itemprop="mainContentOfPage">
				<?php
				if ( 'search' == $url->whereAmI() ) {
					include( THEME_DIR . 'templates/content/search.php' );
				} else {
					include( THEME_DIR . content_template() );
				} ?>
			</main>

			<?php
			if ( 'search' == $url->whereAmI() ) {
				include( THEME_DIR . 'templates/aside/aside.php' );
			} elseif ( ! str_contains( $page->template(), 'no-sidebar' ) ) {
				include( THEME_DIR . 'templates/aside/aside.php' );
			}
			?>
		</div>

		<?php Theme :: plugins( 'pageEnd' ); ?>

		<?php include( THEME_DIR . 'templates/footer/footer.php' ); ?>

	</div>
	<?php
	echo user_toolbar();

	Theme :: plugins( 'siteBodyEnd' );

	footer_scripts();
	?>
</body>
</html>
