<?php
/**
 * Static page template
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	plugins_hook,
	full_cover,
	has_cover
};
use function CFE_Tags\{
	page_header,
	search_form,
	static_list,
	categories_list,
	tags_list
};

// Override static pages list.
$static_args = [
	'wrap'      => true,
	'direction' => 'horz',
	'title'     => ucwords( $L->get( 'Pages' ) ),
	'heading'   => 'h2'
];

// Override categories list defaults.
$cats_args = [
	'wrap'    => true,
	'title'   => ucwords( $L->get( 'Categories' ) ),
	'heading' => 'h2',
	'count'   => true
];

// Override tags list defaults.
$tags_args = [
	'wrap'    => true,
	'title'   => ucwords( $L->get( 'Post Tags' ) ),
	'heading' => 'h2',
	'count'   => true
];

?>
<article class="site-article" role="article" data-site-article>

	<?php if ( ! has_cover() ) {
		echo page_header();
	} ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php

		// Maybe get widgets.
		if ( plugin() ) {
			if (
				'above' == plugin()->error_widgets() ||
				'no_content' == plugin()->error_widgets()
			) {
				echo plugins_hook( 'url_not_found' );
			}
			if ( 'no_content' != plugin()->error_widgets() ) {
				echo $page->content();
			}
			if ( 'below' == plugin()->error_widgets() ) {
				echo plugins_hook( 'url_not_found' );
			}
		} else {
			echo $page->content();
		} ?>
	</div>
</article>
