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
<body class="<?php echo body_classes(); ?>" itemid="<?php echo $uuid; ?>" data-uuid="<?php echo $body_data_attr; ?>">

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<div id="<?php echo page_id(); ?>" class="page-wrap" data-page-wrap itemscope="itemscope" itemtype="<?php site_schema(); ?>">

		<?php include( THEME_DIR . 'views/header/header.php' ); ?>

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<?php if ( 'search' != $url->whereAmI() && ! full_cover() && has_cover() ) : ?>
		<figure class="page-cover page-cover-single">
			<img src="<?php echo get_cover_src(); ?>" />
			<figcaption class="screen-reader-text"><?php echo $page->title(); ?></figcaption>
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
	<?php
	echo user_toolbar();

	Theme :: plugins( 'siteBodyEnd' );

	footer_scripts();
	?>
</body>
</html>
