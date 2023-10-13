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
	theme
};

// Sticky sidebar class.
$sticky = '';
if ( theme() && theme()->sidebar_sticky() ) {
	$sticky = ' sidebar-is-sticky';
}
?>
<div>
	<aside id="page-sidebar" class="page-sidebar<?php echo $sticky; ?>" data-page-sidebar>
		<?php Theme :: plugins( 'siteSidebar' ); ?>
	</aside>
</div>
