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

?>
<div id="page-sidebar-wrap" class="page-sidebar-wrap">
	<aside id="page-sidebar" class="page-sidebar" data-page-sidebar>
		<?php Theme :: plugins( 'siteSidebar' ); ?>
	</aside>
</div>
