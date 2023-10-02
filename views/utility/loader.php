<?php
/**
 * Page loader
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Tags\{
	icon
};

// Loading text.
$text = '';
if ( THEME_CONFIG['load_screen']['loading_text'] ) {
	$text = THEME_CONFIG['load_screen']['loading_text'];
} elseif( $L->get( 'loading-text' ) ) {
	$text = $L->get( 'loading-text' );
}

// Loader styles.
$style = '';
if (
	THEME_CONFIG['load_screen']['bg_color'] ||
	THEME_CONFIG['load_screen']['text_color']
) {
	$style = '<style>:root{';

	if ( THEME_CONFIG['load_screen']['bg_color'] ) {
		$style .= '--cfe-loader--bg-color:' . THEME_CONFIG['load_screen']['bg_color'] . ';';
	}

	if ( THEME_CONFIG['load_screen']['text_color'] ) {
		$style .= '--cfe-loader--text-color:' . THEME_CONFIG['load_screen']['text_color'] . ';';
	}
	$style .= '}</style>';
}
echo $style;

?>
<div id="page-loader" class="page-loader hide-if-no-js">
	<p class="loading-text"><?php echo $text; ?></p>
	<div class="loading-image">
		<?php echo icon( 'spinner' ); ?>
	</div>
</div>
