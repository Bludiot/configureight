<?php
/**
 * Standard page template
 *
 * @package    Configure 8
 * @subpackage Templates
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	is_blog_page,
	loop_data,
	has_cover,
	get_cover_src,
	full_cover,
	include_sidebar
};
use function CFE_Tags\{
	body_classes,
	page_schema,
	page_loader,
	user_toolbar,
	page_id,
	cover_header,
	content_template,
	icon,
	footer_scripts
};

// Get blog data.
$loop_data = loop_data();

// Layout class for the `<main>` element.
$main_view = 'page-view';
if ( is_blog_page() ) {
	$main_view = 'blog-view list-view';
	if ( 'grid' == THEME_CONFIG['loop']['content'] ) {
		$main_view = 'blog-view grid-view';
	}
}

// Get UUID
$uuid = '';
if ( 'page' == $url->whereAmI() ) {
	$uuid = $page->uuid();
}

// Data attributes.
$body_data_attr = sprintf(
	'data-uuid="%s" data-body',
	$uuid
);
$main_data_attr = 'data-page-main';
if ( is_blog_page() ) {
	$body_data_attr = sprintf(
		'data-uuid="%s" data-post-count="%s"',
		$uuid,
		$loop_data['post_count']
	);
	$main_data_attr = sprintf(
		'data-page-main data-show-posts="%s"',
		$loop_data['show_posts']
	);
}

?>
<!DOCTYPE html>
<html dir="auto" class="no-js" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/" data-web-page>
<?php include( THEME_DIR . 'views/utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>" itemid="<?php echo $uuid; ?>" <?php echo $body_data_attr; ?>>

	<?php echo page_loader(); ?>

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<?php include( THEME_DIR . 'views/header/header.php' ); ?>

	<div id="<?php echo page_id(); ?>" class="page-wrap" data-page-wrap itemscope="itemscope" itemtype="<?php page_schema(); ?>">

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<?php if ( 'search' != $url->whereAmI() && ! full_cover() && has_cover() ) : ?>
		<figure class="page-cover page-cover-singular">
			<img src="<?php echo get_cover_src(); ?>" />
			<figcaption>
				<div class="cover-overlay"></div>
				<?php echo cover_header(); ?>
			</figcaption>
		</figure>
		<?php endif; ?>

		<div id="content" class="wrapper-general content-wrapper" data-content-wrapper>

			<main class="page-main <?php echo $main_view; ?>" <?php echo $main_data_attr; ?> itemscope itemprop="mainContentOfPage">
				<?php
				if ( 'search' == $url->whereAmI() ) {
					include( THEME_DIR . 'views/content/search.php' );
				} else {
					include( THEME_DIR . content_template() );
				} ?>
			</main>

			<?php
			if ( include_sidebar() ) {
				include( THEME_DIR . 'views/aside/aside.php' );
			}
			?>
		</div>

		<?php Theme :: plugins( 'pageEnd' ); ?>

	</div>

	<?php include( THEME_DIR . 'views/footer/footer.php' ); ?>

	<?php if ( THEME_CONFIG['to_top'] ) : ?>
	<a href="#" id="to-top" class="hide-if-no-js">
		<?php echo icon( 'angle-up' ); ?>
	</a>
	<?php endif; ?>
	<?php
	echo user_toolbar();

	Theme :: plugins( 'siteBodyEnd' );

	footer_scripts();
	?>
</body>
</html>
