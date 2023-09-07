<?php
/**
 * Home page template
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Tags\{
	page_description,
	get_author
};

?>
<article class="site-article" role="article">

	<?php if ( empty( $content) ) : ?>
		<?php include( THEME_DIR_PHP . 'content/no-posts.php' ); ?>
	<?php endif; ?>

	<?php foreach ( $content as $page ) : ?>

		<?php Theme :: plugins( 'pageBegin' ); ?>

		<div class="blog-wrap">
			<?php if ( $page->coverImage() ) : ?>
			<figure class="page-cover page-cover-home">
				<a href="<?php echo $page->permalink(); ?>">
					<img src="<?php echo $page->coverImage(); ?>" />
				</a>
				<figcaption class="screen-reader-text"><?php echo $page->title(); ?></figcaption>
			</figure>
			<?php endif ?>

			<div class="page-summary">

				<header class="page-header">
					<h2><a href="<?php echo $page->permalink(); ?>"><?php echo $page->title(); ?></a></h2>
				</header>

				<?php echo page_description(); ?>

				<footer class="page-info">
					<p>
						<?php if ( BSB_CONFIG['byline'] ) : ?>
						<span class="page-info-entry">
							<span class="bi bi-pencil" role="img"></span>
							<?php echo get_author(); ?>
						</span>
						<br />
						<?php endif ?>

						<?php if ( BSB_CONFIG['post_date'] ) : ?>
						<span class="page-info-entry">
							<span class="bi bi-calendar" role="img"></span>
							<?php echo $page->date(); ?>
						</span>
						<br />
						<?php endif ?>

						<?php if ( BSB_CONFIG['read_time'] ) : ?>
						<span class="page-info-entry">
							<span class="bi bi-clock-history" role="img"></span>
							<?php echo $L->get( 'Reading time' ) . ': ' . $page->readingTime(); ?>
						</span>
						<?php endif ?>
					</p>
				</footer>
			</div>
		</div>

		<?php Theme :: plugins( 'pageEnd' ); ?>
	<?php endforeach; ?>
</article>

<?php
// Get page navigation.
include( THEME_DIR_PHP . 'navigation/pagination.php' );
