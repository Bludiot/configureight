<?php
/**
 * Page footer
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Partials
 * @since      1.0.0
 */

$copyright = sprintf(
	'<p class="copyright" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">&copy; <span itemprop="copyrightYear">%s</span> <span itemprop="copyrightHolder">%s.</span> %s</p>',
	date( 'Y' ),
	$site->title(),
	$L->get( 'copyright-message' )
);

?>
<footer class="site-footer" data-site-footer>
	<div class="wrapper-general">
		<div class="site-footer-text">
			<?php echo Theme :: footer(); ?>
		</div>
		<?php echo $copyright; ?>
	</div>
</footer>
