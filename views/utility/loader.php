<?php
/**
 * Page loader
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

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
		$style .= '--bsb-loader--bg-color:' . THEME_CONFIG['load_screen']['bg_color'] . ';';
	}

	if ( THEME_CONFIG['load_screen']['text_color'] ) {
		$style .= '--bsb-loader--text-color:' . THEME_CONFIG['load_screen']['text_color'] . ';';
	}
	$style .= '}</style>';
}
echo $style;

?>
<div id="page-loader" class="page-loader">
	<p class="loading-text"><?php echo $text; ?></p>
	<div class="loading-image">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M296 48c0 22.091-17.909 40-40 40s-40-17.909-40-40 17.909-40 40-40 40 17.909 40 40zm-40 376c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40zm248-168c0-22.091-17.909-40-40-40s-40 17.909-40 40 17.909 40 40 40 40-17.909 40-40zm-416 0c0-22.091-17.909-40-40-40S8 233.909 8 256s17.909 40 40 40 40-17.909 40-40zm20.922-187.078c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40c0-22.092-17.909-40-40-40zm294.156 294.156c-22.091 0-40 17.909-40 40s17.909 40 40 40c22.092 0 40-17.909 40-40s-17.908-40-40-40zm-294.156 0c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40z"/></svg>
	</div>
</div>
