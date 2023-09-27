<?php
/**
 * Aside/sidebar content template
 *
 * Sidebar wrapping element is necessary for
 * the jQuery sticky option.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Aside
 * @since      1.0.0
 */

// Sticky sidebar class.
$sticky = '';
if ( THEME_CONFIG['aside']['sticky'] ) {
	$sticky = ' sidebar-is-sticky';
}
?>
<div>
	<aside id="page-sidebar" class="page-sidebar<?php echo $sticky; ?>" data-page-sidebar>
		<?php Theme :: plugins( 'siteSidebar' ); ?>
	</aside>
</div>
