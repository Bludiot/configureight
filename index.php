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
	plugin,
	is_rtl,
	plugins_hook,
	current_lang,
	is_loop_page,
	is_main_loop,
	is_cat,
	is_tag,
	is_search,
	loop_data,
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

// Site direction.
$dir = 'ltr';
if ( is_rtl() ) {
	$dir = 'rtl';
}

// Get loop data.
$loop_data = loop_data();

// Layout class for the `<main>` element.
$main_view = 'page-view';
if ( is_main_loop() ) {
	$main_view = 'loop-view list-view';
	if ( plugin() ) {
		if ( 'grid' == plugin()->loop_style() ) {
			$main_view = 'loop-view grid-view';
		}
	}
} elseif ( is_cat() ) {
	$main_view = 'loop-view list-view';
	if ( plugin() ) {
		if ( 'grid' == plugin()->cat_style() ) {
			$main_view = 'loop-view grid-view';
		}
	}
} elseif ( is_tag() ) {
	$main_view = 'clist-view';
	if ( plugin() ) {
		if ( 'grid' == plugin()->tag_style() ) {
			$main_view = 'loop-view grid-view';
		}
	}
} elseif ( is_search() ) {
	$main_view = 'loop-view search-view list-view';
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
if ( is_loop_page() ) {
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
<html dir="<?php echo $dir; ?>" class="no-js" lang="<?php echo current_lang(); ?>" xmlns:og="http://opengraphprotocol.org/schema/" data-web-page>
<?php include( THEME_DIR . 'views/utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>" itemid="<?php echo $uuid; ?>" <?php echo $body_data_attr; ?>>

	<?php echo page_loader(); ?>

	<?php plugins_hook( 'siteBodyBegin' ); ?>

	<?php include( THEME_DIR . 'views/header/header.php' ); ?>

	<div id="<?php echo page_id(); ?>" class="page-wrap" data-page-wrap itemscope="itemscope" itemtype="<?php page_schema(); ?>">

		<?php plugins_hook( 'pageBegin' ); ?>

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

		<?php plugins_hook( 'pageEnd' ); ?>

	</div>

	<?php include( THEME_DIR . 'views/footer/footer.php' ); ?>

	<?php if ( plugin() ) : if ( plugin()->to_top_button() ) : ?>
	<a href="#" id="to-top" class="hide-if-no-js">
		<?php echo icon( 'angle-up' ); ?>
	</a>
	<?php endif; endif; ?>
	<?php
	echo user_toolbar();

	footer_scripts();

	plugins_hook( 'siteBodyEnd' );
	?>
</body>
</html>
