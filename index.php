<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

// Site title.
if ( $WHERE_AM_I == 'home' ) {
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
<!DOCTYPE html>
<html lang="<?php echo Theme::lang() ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Include Favicon -->
	<?php echo Theme :: favicon( 'img/favicon.png' ); ?>

	<!-- Dynamic title tag -->
	<?php echo Theme :: metaTagTitle(); ?>

	<!-- Dynamic description tag -->
	<?php echo Theme :: metaTagDescription(); ?>

	<!-- Include CSS Styles from this theme -->
	<?php echo Theme :: css( 'css/style.min.css' ); ?>

	<!-- Load plugins with the hook siteHead -->
	<?php Theme :: plugins( 'siteHead' ); ?>
</head>
<body>
	<!-- Load plugins with the hook siteBodyBegin -->
	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<?php if ( ! empty( $site->logo() ) ) : ?>
	<img src="<?php echo $site->logo(); ?>" alt="<?php echo $site->title(); ?>" width="80">
	<?php endif; ?>

	<?php echo $site_title; ?>
	<p class="site-description"><?php echo $site->slogan(); ?></p>

	<?php if ( $WHERE_AM_I == 'page' ) : ?>
		<h1><?php echo $page->title(); ?></h1>

	<?php elseif ( $WHERE_AM_I == 'home' ) : ?>
		<?php foreach ( $content as $page ) : ?>
		<h2><a href="<?php echo $page->permalink(); ?>"><?php echo $page->title(); ?></a></h2>
		<?php endforeach;?>
	<?php endif; ?>

	<!-- Load plugins with the hook siteBodyBegin -->
	<?php Theme :: plugins( 'siteBodyEnd' ); ?>
</body>
</html>
