<?php
/**
 * Page footer
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Partials
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	site,
	plugins_hook
};
use function CFE_Tags\{
	social_nav,
	footer_scripts
};

$copyright = '';
if ( plugin() ) {
	if ( plugin()->copyright() ) {

		$year = '';
		if ( plugin()->copy_date() ) {
			$year = sprintf(
				' <span itemprop="copyrightYear">%s</span>',
				date( 'Y' )
			);
		}

		$get_text = plugin()->copy_text();
		if ( ! empty( $get_text ) ) {

			$text = $get_text;
			if ( strstr( $get_text, '{{copy}}' ) ) {
				$text = str_replace( '{{copy}}', '&copy;', $text );
			}
			if ( strstr( $get_text, '{{year}}' ) ) {
				$text = str_replace( '{{year}}', $year, $text );
			}

			$copyright = sprintf(
				'<p class="copyright" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">%s</p>',
				htmlspecialchars_decode( $text )
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
}

?>
<footer id="site-footer" class="site-footer" data-site-footer>
	<div class="wrapper-general site-footer-wrap">
		<?php if ( ! empty( site()->footer() ) && plugin() && plugin()->footer_text() ) : ?>
		<div class="site-footer-text">
			<?php printf(
				'<p>%s</p>',
				site()->footer()
			); ?>
		</div>
		<?php endif; ?>

		<?php
		if ( plugin() ) {
			if ( plugin()->footer_search() && getPlugin( 'Search_Forms' ) ) {
				echo SearchForms\form( [
					'label'       => false,
					'placeholder' => $L->get( 'Search' )
				] );
			}
		} ?>

		<?php
		if ( ! plugin() ) {
			echo social_nav();
		} elseif ( plugin() ) {
			if ( plugin()->footer_social() ) {
				echo social_nav();
			}
		} ?>
		<?php echo $copyright; ?>
	</div>
</footer>
<?php

footer_scripts();

if ( plugin() ) {
	plugins_hook( 'footer_code' );
}
