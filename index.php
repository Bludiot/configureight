<?php
/**
 * Site index file
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @since      1.0.0
 */

use function BS_Init\user_logged_in;

// Classes for the html element.
$html_class = 'no-js';
if ( user_logged_in() ) {
	$html_class = 'no-js user-logged-in';
}

?>
<!DOCTYPE html>
<html class="<?php echo $html_class; ?>" lang="<?php echo Theme :: lang() ?>" xmlns:og="http://opengraphprotocol.org/schema/">
<?php include( THEME_DIR_PHP . 'utility/head.php' ); ?>
<body>

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
</body>
</html>
