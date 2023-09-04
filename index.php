<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

?>
<!DOCTYPE html>
<html lang="<?php echo Theme :: lang() ?>">
<?php include( THEME_DIR_PHP . 'utility/head.php' ); ?>
<body>

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<?php include( THEME_DIR_PHP . 'header/header.php' ); ?>

	<main class="wrapper-general site-main">
		<?php
		if ( 'page' == $WHERE_AM_I ) :
			include( THEME_DIR_PHP . 'content/page.php' );
		elseif ( 'home' == $WHERE_AM_I ) :
			include( THEME_DIR_PHP . 'content/home.php' );
		endif; ?>
	</main>

	<?php Theme :: plugins( 'siteBodyEnd' ); ?>
</body>
</html>
