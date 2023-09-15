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

use function BSB_Tags\{
	site_logo
};

// Site title element.
if ( 'home' == $WHERE_AM_I ) {
	$site_title = sprintf(
		'<h1 class="site-title">%s</h1>',
		$site->title()
	);
} else {
	$site_title = sprintf(
		'<p class="site-title"><a href="%s">%s</a></p>',
		$site->url(),
		$site->title()
	);
}

?>
<header id="masthead" class="site-header" role="banner" itemscope="itemscope" itemtype="https://schema.org/Organization">
	<div class="wrapper-general site-header-wrap">
		<div class="site-branding">
			<?php site_logo(); ?>
			<div class="site-title-description">
				<?php echo $site_title; ?>
				<p class="site-description"><?php echo $site->slogan(); ?></p>
			</div>
		</div>
		<?php
		// Get the main navigation menu.
		include( THEME_DIR . 'views/navigation/main-nav.php' ); ?>
	</div>
</header>
