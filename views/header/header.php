<?php
/**
 * Page header
 *
 * Site identity and navigation.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Partials
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	get_cover_src,
	full_cover
};
use function BSB_Tags\{
	search_form,
	site_logo,
	page_header
};

// Site title classes.
$site_title_class = 'site-title';
if ( ! THEME_CONFIG['header']['title'] ) {
	$site_title_class = 'site-title screen-reader-text';
}

// Site description classes.
$site_desc_class = 'site-description';
if ( ! THEME_CONFIG['header']['description'] ) {
	$site_desc_class = 'site-description screen-reader-text';
}

// Site title element.
if ( 'home' == $WHERE_AM_I ) {
	$site_title = sprintf(
		'<h1 class="%s">%s</h1>',
		$site_title_class,
		$site->title()
	);
} else {
	$site_title = sprintf(
		'<p class="%s"><a href="%s">%s</a></p>',
		$site_title_class,
		$site->url(),
		$site->title()
	);
}

// Background image.
$header_image = '';
if ( full_cover() ) {
	$header_image = sprintf(
		'style="background-image: url( %s )"',
		get_cover_src()
	);
}

?>
<header id="masthead" class="site-header" role="banner" itemscope="itemscope" itemtype="https://schema.org/Organization" data-site-header <?php echo $header_image; ?>>

	<div class="site-header-overlay"></div>

	<?php if (
		THEME_CONFIG['main_nav']['search'] &&
		getPlugin( 'pluginSearch' )
	) : ?>
	<div id="search-bar" class="hide-if-no-js" aria-expanded="false">

		<?php echo search_form( [ 'label' => false ] ); ?>

		<button data-search-toggle-close><span class="screen-reader-text"><?php $L->p( 'search-bar-close' ); ?></span></button>
	</div>
	<?php endif; ?>

	<div class="wrapper-general site-header-wrap">

		<div class="site-branding" data-site-branding>
			<?php site_logo(); ?>
			<div class="site-title-description">
				<?php echo $site_title; ?>
				<p class="<?php echo $site_desc_class; ?>"><?php echo $site->slogan(); ?></p>
			</div>
		</div>

		<?php
		// Get the main navigation menu.
		include( THEME_DIR . 'views/navigation/main-nav.php' ); ?>
	</div>
	<?php if ( full_cover() ) {
		echo page_header();
	} ?>
</header>
