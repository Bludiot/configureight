<?php
/**
 * Page header
 *
 * Site identity and navigation.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Partials
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	site,
	site_domain,
	plugin,
	lang,
	is_front_page,
	get_cover_src,
	full_cover
};
use function CFE_Tags\{
	search_form,
	site_logo,
	page_header,
	cover_header
};

// Site title classes.
$site_title_class = 'site-title';
if ( plugin() && ! plugin()->site_title() ) {
	$site_title_class = 'site-title screen-reader-text';
}

// Site description classes.
$site_desc_class = 'site-description';
if ( plugin() && ! plugin()->site_slogan() ) {
	$site_desc_class = 'site-description screen-reader-text';
}

// Site title element.
if ( 'home' == $WHERE_AM_I || is_front_page() ) {
	$site_title = sprintf(
		'<h1 class="%s"><a href="%s">%s</a></h1>',
		$site_title_class,
		site_domain(),
		site()->title()
	);
} else {
	$site_title = sprintf(
		'<p class="%s"><a href="%s">%s</a></p>',
		$site_title_class,
		site_domain(),
		site()->title()
	);
}

$site_description = '';
if ( ! empty( site()->slogan() ) && ! ctype_space( site()->slogan() ) ) {
	$site_description = sprintf(
		'<p class="%s">%s</p>',
		$site_desc_class,
		site()->slogan()
	);
} elseif ( ! empty( site()->description() ) && ! ctype_space( site()->description() ) ) {
	$site_description = sprintf(
		'<p class="%s">%s</p>',
		$site_desc_class,
		site()->description()
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

	<?php if ( full_cover() ) : ?>
	<div class="cover-overlay"></div>
	<?php endif; ?>

	<?php if (
		plugin() &&
		plugin()->header_search() &&
		getPlugin( 'Search_Forms' )
	) :

	$form_args = [
		'label'       => false
	];
	?>
	<div id="search-bar" class="hide-if-no-js" aria-expanded="false">

		<?php echo SearchForms\form( $form_args ); ?>

		<button data-search-toggle-close><span class="screen-reader-text"><?php lang()->p( 'search-bar-close' ); ?></span></button>
	</div>
	<?php endif; ?>

	<div class="wrapper-general site-header-wrap">

		<div class="site-branding" data-site-branding>
			<?php site_logo(); ?>
			<div class="site-title-description">
				<?php echo $site_title; ?>
				<?php echo $site_description; ?>
			</div>
		</div>

		<?php
		// Get the main navigation menu.
		include( THEME_DIR . 'views/navigation/main-nav.php' ); ?>
	</div>
	<?php if ( full_cover() ) {
		echo cover_header();
	} ?>
</header>
