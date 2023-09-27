<?php
/**
 * Standard page template
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	is_blog_page,
	blog_data,
	has_cover,
	get_cover_src,
	full_cover
};
use function BSB_Tags\{
	body_classes,
	site_schema,
	user_toolbar,
	page_id,
	cover_header,
	content_template,
	footer_scripts
};

// Get blog data.
$blog_data = blog_data();

// Layout class for the `<main>` element.
$main_view = 'page-view';
if ( is_blog_page() ) {
	$main_view = 'blog-view list-view';
	if ( 'grid' == THEME_CONFIG['posts']['loop'] ) {
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
		$blog_data['post_count']
	);
	$main_data_attr = sprintf(
		'data-page-main data-show-posts="%s"',
		$blog_data['show_posts']
	);
}

?>
<!DOCTYPE html>
<html dir="auto" class="no-js" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/" data-web-page>
<?php include( THEME_DIR . 'views/utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>" itemid="<?php echo $uuid; ?>" <?php echo $body_data_attr; ?>>

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<?php include( THEME_DIR . 'views/header/header.php' ); ?>

	<div id="<?php echo page_id(); ?>" class="page-wrap" data-page-wrap itemscope="itemscope" itemtype="<?php site_schema(); ?>">

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<?php if ( 'search' != $url->whereAmI() && ! full_cover() && has_cover() ) : ?>
		<figure class="page-cover page-cover-singular">
			<img src="<?php echo get_cover_src(); ?>" />
			<figcaption>
				<div class="cover-overlay"></div>
				<?php echo cover_header(); ?>
			</figcaption>
		</figure>
		<?php endif ?>

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
			if ( 'page' != $url->whereAmI() ) {
				include( THEME_DIR . 'views/aside/aside.php' );
			} elseif ( ! str_contains( $page->template(), 'no-sidebar' ) ) {
				include( THEME_DIR . 'views/aside/aside.php' );
			}
			?>
		</div>

		<?php Theme :: plugins( 'pageEnd' ); ?>

		<?php include( THEME_DIR . 'views/footer/footer.php' ); ?>

	</div>
	<?php if ( THEME_CONFIG['to_top'] ) : ?>
	<a href="#" id="to-top" class="hide-if-no-js">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M352 352c-8.188 0-16.38-3.125-22.62-9.375L192 205.3l-137.4 137.4c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25C368.4 348.9 360.2 352 352 352z"/></svg>
	</a>
	<?php endif ?>
	<?php
	echo user_toolbar();

	Theme :: plugins( 'siteBodyEnd' );

	footer_scripts();
	?>
</body>
</html>
