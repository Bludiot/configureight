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
	tags_list
};

$tags_args = [
	'wrap'    => true,
	'title'   => $L->get( 'Post Tags' ),
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
		<?php echo tags_list( $tags_args ); ?>
	</div>
</article>
