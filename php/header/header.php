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

// Site title element.
if ( 'home' == $WHERE_AM_I ) {
	$site_title = sprintf(
		'<h1 class="site-title"><a href="%s">%s</a></h1>',
		$site->url(),
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
<header id="masthead" class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
	<div class="wrapper-general site-header-wrap">
		<div class="site-branding">
			<?php if ( ! empty( $site->logo() ) ) : ?>
			<div class="site-logo">
				<figure>
					<a href="<?php echo $site->url(); ?>">
						<img src="<?php echo $site->logo(); ?>" alt="<?php echo $site->title(); ?>" width="80">
					</a>
					<figcaption class="screen-reader-text"><?php echo $site->title(); ?></figcaption>
				</figure>
			</div>
			<?php endif; ?>
			<div class="site-title-description">
				<?php echo $site_title; ?>
				<p class="site-description"><?php echo $site->slogan(); ?></p>
			</div>
		</div>
		<nav class="site-navigation" role="directory" itemscope itemtype="http://schema.org/SiteNavigationElement">
			<ul>
				<li>
					<a href="#">Menu Item</a>
				</li>
				<li>
					<a href="#">Menu Item</a>
				</li>
				<li>
					<a href="#">Menu Item</a>
				</li>
				<li>
					<a href="#">Menu Item</a>
				</li>
				<li>
					<a href="#">Menu Item</a>
				</li>
			</ul>
		</nav>
	</div>
</header>
