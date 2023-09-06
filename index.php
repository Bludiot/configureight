<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

use function BS_Init\body_classes;
use function BS_Init\user_logged_in;

?>
<!DOCTYPE html>
<html dir="auto" class="no-js" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/">
<?php include( THEME_DIR_PHP . 'utility/head.php' ); ?>
<body class="<?php echo body_classes(); ?>">

	<?php Theme :: plugins( 'siteBodyBegin' ); ?>

	<?php include( THEME_DIR_PHP . 'header/header.php' ); ?>

	<main class="wrapper-general site-main">
		<?php
		if ( 'page' == $WHERE_AM_I ) {
			include( THEME_DIR_PHP . 'content/page.php' );
		} elseif ( 'home' == $WHERE_AM_I || 'blog' == $WHERE_AM_I ) {
			include( THEME_DIR_PHP . 'content/home.php' );
		} ?>
	</main>

	<?php Theme :: plugins( 'siteBodyEnd' ); ?>
	<?php if ( user_logged_in() ) {
		include( THEME_DIR_PHP . 'utility/toolbar.php' );
	} ?>
	<?php include( THEME_DIR_PHP . 'footer/footer.php' ); ?>
</body>
</html>
