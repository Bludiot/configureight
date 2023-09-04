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
		'<h1 class="site-title">%s</h1>',
		$site->title()
	);
} else {
	$site_title = sprintf(
		'<p class="site-title">%s</p>',
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
					<img src="<?php echo $site->logo(); ?>" alt="<?php echo $site->title(); ?>" width="80">
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
