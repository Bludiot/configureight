<?php
/**
 * Page footer
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Partials
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	text_replace
};

$copyright = '';
if ( THEME_CONFIG['footer']['copyright_line'] ) {

	$year = '';
	if ( THEME_CONFIG['footer']['copyright_date'] ) {
		$year = sprintf(
			' <span itemprop="copyrightYear">%s</span>',
			date( 'Y' )
		);
	}

	$get_text = THEME_CONFIG['footer']['copyright_text'];
	if ( ! empty( $get_text ) ) {

		$text = $get_text;
		if ( strstr( $get_text, '%year%' ) ) {
			$text = str_replace( '%year%', $year, $get_text );
		}

		$copyright = sprintf(
			'<p class="copyright" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">%s</p>',
			$text
		);
	} else {
		$copyright = sprintf(
			'<p class="copyright" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">&copy;%s <span itemprop="copyrightHolder">%s.</span> %s</p>',
			$year,
			$site->title(),
			$L->get( 'copyright-message' )
		);
	}
}

?>
<footer class="site-footer" data-site-footer>
	<div class="wrapper-general">
		<div class="site-footer-text">
			<?php echo Theme :: footer(); ?>
		</div>
		<?php echo $copyright; ?>
	</div>
</footer>
