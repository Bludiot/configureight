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

// Loading icon.
$icon_type = 'spinner-dots';
$icon_show = true;
if ( plugin() ) {
	if ( 'spinner-dashes' == plugin()->loader_icon() ) {
		$icon_type = 'spinner-dashes';
	} elseif ( 'spinner-third' == plugin()->loader_icon() ) {
		$icon_type = 'spinner-third';
	}

	if ( 'none' == plugin()->loader_icon() ) {
		$icon_show = false;
	}
}

?>
<div id="page-loader" class="page-loader hide-if-no-js">
	<p class="loading-text"><?php echo $text; ?></p>
	<?php if ( $icon_show ) : ?>
	<div class="loading-image <?php echo $icon_type; ?>">
		<?php echo icon( $icon_type ); ?>
	</div>
	<?php endif; ?>
</div>
