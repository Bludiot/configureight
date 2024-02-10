<?php
/**
 * Static front page template
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
	page_header
};

// Gallery heading.
$gallery_heading = $L->get( 'Image Gallery' );
if ( ! empty( $page->custom( 'gallery_heading' ) ) ) {
	$gallery_heading = $page->custom( 'gallery_heading' );
}

?>
<article class="site-article" role="article" data-site-article>

	<?php if ( ! has_cover() ) {
		echo page_header();
	} ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php echo $page->content(); ?>
	</div>

	<?php if ( $page->custom( 'page_gallery' ) ) : ?>
	<div class="page-gallery">
		<h2><?php echo $gallery_heading; ?></h2>
		<?php plugins_hook( 'page_gallery' ); ?>
	</div>
	<?php endif; ?>
</article>
