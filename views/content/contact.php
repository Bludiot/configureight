<?php
/**
 * Contact page template
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	full_cover
};
use function BSB_Tags\{
	page_header
};

?>
<article class="site-article" role="article" data-site-article>

	<?php if ( ! full_cover() ) {
		echo page_header();
	} ?>

	<?php if ( $page->coverImage() && ! full_cover() ) : ?>
	<figure class="page-cover page-cover-single">
		<img src="<?php echo $page->coverImage(); ?>" />
		<figcaption class="screen-reader-text"><?php echo $page->title(); ?></figcaption>
	</figure>
	<?php endif ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php echo $page->contentBreak(); ?>
	</div>

	<?php if ( $page->readMore() ) : ?>
		<p><a class="button" href="<?php echo $page->permalink(); ?>" role="button"><?php echo $L->get( 'Read More' ); ?></a></p>
	<?php endif; ?>
</article>
