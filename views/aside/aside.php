<?php
/**
 * Aside/sidebar content template
 *
 * Sidebar wrapping element is necessary for
 * the jQuery sticky option.
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

// Sticky sidebar class.
$sticky = '';
if ( plugin() && plugin()->sidebar_sticky() ) {
	$sticky = 'sidebar-is-sticky';
}

// Social nave heading.
$social_heading = sprintf(
	'<h2>%s</h2>',
	$L->get( 'Social Links' )
);
if ( plugin() ) {
	$social_heading = sprintf(
		'<h2>%s</h2>',
		plugin()->sb_social_heading()
	);
}
?>
<div>
	<aside id="page-sidebar" class="page-sidebar <?php echo $sticky; ?>" data-page-sidebar>
		<?php plugins_hook( 'siteSidebar' ); ?>

		<?php if ( plugin() && plugin()->sidebar_social() ) : ?>
		<div class="plugin plugin-social-nav">
			<?php echo $social_heading; ?>
			<div class="plugin-content">
				<?php echo social_nav(); ?>
			</div>
		</div>
		<?php endif; ?>
	</aside>
</div>
