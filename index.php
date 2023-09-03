<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

	<!-- Dynamic title tag -->
	<?php echo Theme :: metaTags( 'title' ); ?>

	<!-- Dynamic description tag -->
	<?php echo Theme :: metaTags( 'description' ); ?>

	<!-- Include CSS Styles from this theme -->
	<?php echo Theme :: css( 'css/style.min.css' ); ?>

	<!-- Load plugins with the hook siteHead -->
	<?php Theme :: plugins( 'siteHead' ); ?>
</head>
<body>
	<!-- Load plugins with the hook siteBodyBegin -->
	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<?php if ( ! empty( $site->logo() ) ) : ?>
	<img src="<?php echo $site->logo(); ?>" alt="<?php echo $site->title(); ?>" width="128">
	<?php endif ?>

	<h1><?php echo $site->title(); ?></h1>
	<h2><?php echo $site->slogan(); ?></h2>

	<?php if ( $WHERE_AM_I == 'page' ) : ?>
		<h3><?php echo $page->title(); ?></h3>

	<?php elseif ( $WHERE_AM_I == 'home' ) : ?>
		<?php foreach ( $content as $page ) : ?>
		<h3><?php echo $page->title(); ?></h3>
		<?php endforeach ?>
	<?php endif ?>

	<!-- Load plugins with the hook siteBodyBegin -->
	<?php Theme :: plugins( 'siteBodyEnd' ); ?>
</body>
</html>
