<?php
/**
 * Aside/sidebar content template
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Aside
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	plugins_hook
};
use function CFE_Tags\{
	social_nav
};

// Social nav heading.
$social_heading = sprintf(
	'<h2>%s</h2>',
	$L->get( 'Social Links' )
);
if ( plugin() ) {
	if ( plugin()->sb_social_heading() ) {
		$social_heading = sprintf(
			'<h2>%s</h2>',
			plugin()->sb_social_heading()
		);
	}
}

?>
<aside id="page-sidebar" class="page-sidebar" data-page-sidebar>
	<?php if ( plugin() ) { plugins_hook( 'site_sidebar_before' ); } ?>
	<?php plugins_hook( 'siteSidebar' ); ?>

	<?php if ( plugin() ) : if ( plugin()->sidebar_social() ) : ?>
	<div class="plugin plugin-social-nav">
		<?php echo $social_heading; ?>
		<div class="plugin-content">
			<?php echo social_nav(); ?>
		</div>
	</div>
	<?php endif; endif; ?>
	<?php if ( plugin() ) { plugins_hook( 'site_sidebar_after' ); } ?>
</aside>
