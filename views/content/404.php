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
	full_cover,
	has_cover
};
use function CFE_Tags\{
	page_header,
	search_form,
	static_list,
	tags_list
};

// Override static pages list.
$static_args = [
	'wrap'      => true,
	'direction' => 'horz',
	'title'     => ucwords( $L->get( 'Pages' ) ),
	'heading'   => 'h2'
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
		<?php echo $page->content(); ?>
		<?php echo search_form( [ 'label' => false ] ); ?>
		<?php echo static_list( $static_args ); ?>
		<?php echo tags_list( $tags_args ); ?>
	</div>
</article>
