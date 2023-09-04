<?php
/**
 * Home page template
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

?>
<article class="site-article" role="article">

	<?php if ( empty( $content) ) : ?>
		<?php $language->p( 'No pages found' ); ?>
	<?php endif; ?>

	<?php foreach ( $content as $page ) : ?>

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<header class="page-header">
			<h2><a href="<?php echo $page->permalink(); ?>"><?php echo $page->title(); ?></a></h2>

			<?php if ( $page->description() ) {
				printf(
					'<p class="page-description">%s</p>',
					$page->description()
				);
			} ?>
		</header>

		<div class="page-content" itemprop="articleBody">
			<?php echo $page->contentBreak(); ?>
		</div>

		<?php if ( $page->readMore() ) : ?>
			<p><a class="button" href="<?php echo $page->permalink(); ?>" role="button"><?php echo $L->get( 'Read More' ); ?></a></p>
		<?php endif; ?>

		<?php Theme :: plugins( 'pageEnd' ); ?>
	<?php endforeach; ?>
</article>

<?php
/**
 * Page navigation
 *
 * Allows users to navigate paginated content.
 */
if ( Paginator :: numberOfPages() > 1 ) : ?>
	<nav class="paginator">
		<ul class="pagination">
		<?php if ( Paginator :: showPrev() ) : ?>
			<li class="page-item">
				<a class="page-link" href="<?php echo Paginator :: previousPageUrl(); ?>" tabindex="-1"><?php echo $L->get( 'Previous' ); ?></a>
			</li>
		<?php endif; ?>
		<?php if ( Paginator :: showNext() ) : ?>
			<li class="page-item">
				<a class="page-link" href="<?php echo Paginator :: nextPageUrl(); ?>"><?php echo $L->get( 'Next' ); ?></a>
			</li>
		<?php endif; ?>
		</ul>
	</nav>
<?php endif;
