<?php
/**
 * Template for no posts in loop
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

?>
<div class="no-posts-found" data-no-posts>

	<?php
	if ( 'search' == $WHERE_AM_I ) {
		printf(
			'<h1>%s</h1>',
			$L->get( 'no-search-heading' )
		);
		printf(
			'<p class="page-description page-description-single">%s</p>',
			$L->get( 'no-search-message' )
		);
	} else {
		printf(
			'<h1>%s</h1>',
			$L->get( 'no-posts-heading' )
		);
		printf(
			'<p class="page-description page-description-single">%s</p>',
			$L->get( 'no-posts-message' )
		);
	} ?>
</div>
