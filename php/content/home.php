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
		<?php include( THEME_DIR_PHP . 'content/no-posts.php' ); ?>
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
			<footer class="page-info">
				<p>
				<span class="page-info-entry"><span class="bi bi-calendar"></span> <?php echo $page->date(); ?></span>
        		<span class="page-info-entry"><span class="bi bi-clock-history"></span> <?php echo $L->get( 'Reading time' ) . ': ' . $page->readingTime(); ?></span>
				</p>
			</footer>
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
// Get page navigation.
include( THEME_DIR_PHP . 'navigation/pagination.php' );
