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
	is_page,
	has_cover,
	get_cover_src,
	full_cover
};
use function CFE_Tags\{
	search_form,
	site_logo,
	page_header,
	cover_header
};

// Header class.
$header_class = 'site-header';
if ( full_cover() ) {
	$header_class = 'site-header full-cover-header';
}

// Site title classes.
$site_title_class = 'site-title';
if ( plugin() ) {
	if ( ! plugin()->site_title() ) {
		$site_title_class = 'site-title screen-reader-text';
	}
}

// Site description classes.
$site_desc_class = 'site-description';
if ( plugin() ) {
	if ( ! plugin()->site_slogan() ) {
		$site_desc_class = 'site-description screen-reader-text';
	}
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

// Site description element.
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

// Cover image class.
$cover_class = 'page-cover cover-overlay';
if ( full_cover() ) {
	$cover_class = 'full-cover-image cover-overlay';
}
if ( plugin() ) {
	if (
		'blend' == plugin()->cover_style() &&
		is_array( plugin()->cover_blend_use() ) &&
		in_array( 'covers', plugin()->cover_blend_use() )
	) {
		$cover_class = 'page-cover cover-blend';

		if ( full_cover() ) {
			$cover_class = 'full-cover-image cover-blend';
		}
	}
}

?>
<header id="masthead" class="<?php echo $header_class; ?>" role="banner" data-site-header>

	<?php
	if ( plugin() ) :
	if ( plugin()->header_search() && getPlugin( 'Search_Forms' ) ) : ?>
	<div id="search-bar" class="hide-if-no-js" aria-expanded="false">

		<?php echo SearchForms\form( [ 'label' => false ] ); ?>

		<button data-search-toggle-close><span class="screen-reader-text"><?php lang()->p( 'search-bar-close' ); ?></span></button>
	</div>
	<?php endif; endif; ?>

	<div class="wrapper-general site-header-wrap">

		<div class="site-branding" itemscope="itemscope" itemtype="https://schema.org/Organization" data-site-branding>
			<?php site_logo(); ?>
			<div class="site-title-description">
				<?php echo $site_title; ?>
				<?php echo $site_description; ?>
			</div>
		</div>

		<?php
		// Get main navigation if theme plugin.
		if ( plugin() ) {
			include( THEME_DIR . 'views/navigation/main-nav-options.php' );

		// Get main navigation if no theme plugin.
		} else {
			include( THEME_DIR . 'views/navigation/main-nav-static.php' );
		} ?>
	</div>
</header>
<?php if ( has_cover() ) : ?>
<div class="<?php echo $cover_class; ?>">
	<figure>
		<img src="<?php echo get_cover_src(); ?>" role="presentation">
	</figure>
	<?php echo cover_header(); ?>
</div>
<?php endif; ?>
