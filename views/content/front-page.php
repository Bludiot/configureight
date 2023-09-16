<?php
/**
 * Static front page template
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

?>
<article class="site-article" role="article" data-site-article>

	<header class="page-header" data-page-header>
		<h2><?php echo $page->title(); ?></h2>

		<?php if ( $page->description() ) {
			printf(
				'<p class="page-description page-description-single">%s</p>',
				$page->description()
			);
		} ?>
	</header>

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
