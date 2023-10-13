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
	theme
};

$copyright = '';
if ( theme() && theme()->copyright() ) {

	$year = '';
	if ( theme()->copy_date() ) {
		$year = sprintf(
			' <span itemprop="copyrightYear">%s</span>',
			date( 'Y' )
		);
	}

	$get_text = theme()->copy_text();
	if ( ! empty( $get_text ) ) {

		$text = $get_text;
		if ( strstr( $get_text, '%copy%' ) ) {
			$text = str_replace( '%copy%', '&copy;', $text );
		}
		if ( strstr( $get_text, '%year%' ) ) {
			$text = str_replace( '%year%', $year, $text );
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
		<?php
		$search = getPlugin( 'pluginSearch' );
		if ( $search && theme() && 'footer' == theme()->sidebar_search() ) {
			echo $search->siteSidebar();
		} ?>
		<div class="site-footer-text">
			<?php
			if ( ! empty( Theme :: footer() ) ) {
				printf(
					'<p>%s</p>',
					Theme :: footer()
				);
			} ?>
			<?php

			// Personal/social links.
			$links = Theme :: socialNetworks();
			if ( $links ) : ?>
			<nav class="social-navigation" data-page-navigation>
				<ul class="nav-list social-nav-list">
					<?php foreach ( $links as $link => $label ) :

					// Get icon SVG file.
					$icon = '';
					$file = THEME_DIR . 'assets/images/svg-icons/' . $link . '.svg';
					if ( file_exists( $file ) ) {
						$icon = file_get_contents( $file );
					} ?>
					<li>
						<a href="<?php echo $site->{$link}(); ?>" target="_blank" rel="noreferrer noopener" title="<?php echo $label; ?>">
							<span class="social-icon"><?php echo $icon; ?></span>
							<span class="screen-reader-text social-label"><?php echo $label; ?></span>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<?php endif; ?>
		</div>
		<?php echo $copyright; ?>
	</div>
</footer>
