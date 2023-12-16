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
use function CFE_Func\{
	plugin,
	lang
};
use function CFE_Tags\{
	icon
};

// loader text.
$text = '';
if ( plugin() ) {
	if ( ! empty( plugin()->loader_text() ) ) {
		$text = plugin()->loader_text();
	}
} elseif( lang()->get( 'loader-text' ) ) {
	$text = lang()->get( 'loader-text' );
}

// Loader styles.
$style = '';
if ( plugin() ) {
	if (
		! empty( plugin()->loader_bg_color() ) ||
		! empty( plugin()->loader_text_color() )
	) {
		$style = '<style>:root{';

		if ( ! empty( plugin()->loader_bg_color() ) ) {
			$style .= '--cfe-loader--bg-color:' . plugin()->loader_bg_color() . ';';
		}

		if ( ! empty( plugin()->loader_text_color() ) ) {
			$style .= '--cfe-loader--text-color:' . plugin()->loader_text_color() . ';';
		}
		$style .= '}</style>';
	}
}
echo $style;

?>
<div id="page-loader" class="page-loader hide-if-no-js">
	<p class="loading-text"><?php echo $text; ?></p>
	<div class="loading-image">
		<?php echo icon( 'spinner' ); ?>
	</div>
</div>
