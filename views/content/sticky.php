<?php
/**
 * Sticky page (post) template
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
	page_header,
	sticky_icon
};

?>
<article class="site-article" role="article" data-site-article>

	<?php if ( ! full_cover() ) {
		echo page_header();
	} ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php echo $page->contentBreak(); ?>
	</div>

	<?php if ( $page->readMore() ) : ?>
		<p><a class="button" href="<?php echo $page->permalink(); ?>" role="button"><?php echo $L->get( 'Read More' ); ?></a></p>
	<?php endif; ?>
</article>
