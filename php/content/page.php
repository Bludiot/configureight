<?php
/**
 * Page template
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

?>
<article class="site-article" role="article">

	<?php Theme :: plugins( 'pageBegin' ); ?>

	<header class="page-header">
		<h1><?php echo $page->title(); ?></h1>

		<?php if ( $page->description() ) {
			printf(
				'<p class="page-description page-description-single">%s</p>',
				$page->description()
			);
		} ?>
	</header>

	<?php if ( $page->coverImage() ) : ?>
	<figure class="page-cover page-cover-single">
		<a href="<?php echo $page->permalink(); ?>">
			<img src="<?php echo $page->coverImage(); ?>" />
		</a>
		<figcaption class="screen-reader-text"><?php echo $page->title(); ?></figcaption>
	</figure>
	<?php endif ?>

	<div class="page-content" itemprop="articleBody">
		<?php echo $page->contentBreak(); ?>
	</div>

	<?php if ( $page->readMore() ) : ?>
		<p><a class="button" href="<?php echo $page->permalink(); ?>" role="button"><?php echo $L->get( 'Read More' ); ?></a></p>
	<?php endif; ?>

	<?php Theme :: plugins( 'pageEnd' ); ?>

</article>
