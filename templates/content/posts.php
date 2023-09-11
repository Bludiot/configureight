<?php
/**
 * Posts page template
 *
 * Used for posts loop, whether on the
 * home page or blog page when a static
 * home page is used.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Tags\{
	sticky_icon,
	page_description,
	get_author
};

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'templates/content/no-posts.php' );
	return;
}

// If posts, print for each.
foreach ( $content as $post ) :

// Maybe a sticky icon.
$sticky = '';
if ( $post->sticky() ) {
	$sticky = sprintf(
		'%s ',
		sticky_icon(
			'false',
			'sticky-icon-heading',
			$L->get( 'Post is sticky' )
		)
	);
}

?>
<article class="site-article blog-wrap" role="article">
	<?php if ( $post->coverImage() ) : ?>
	<figure class="page-cover page-cover-home">
		<a href="<?php echo $post->permalink(); ?>">
			<img src="<?php echo $post->coverImage(); ?>" loading="lazy" />
		</a>
		<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
	</figure>
	<?php endif ?>

	<div class="page-summary">

		<header class="page-header">
			<h2><a href="<?php echo $post->permalink(); ?>"><?php echo $sticky . $post->title(); ?></a></h2>
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
					<?php echo $post->date(); ?>
				</span>
				<br />
				<?php endif ?>

				<?php if ( BSB_CONFIG['read_time'] ) : ?>
				<span class="page-info-entry">
					<span class="bi bi-clock-history" role="img"></span>
					<?php echo $L->get( 'Reading time' ) . ': ' . $post->readingTime(); ?>
				</span>
				<?php endif ?>
			</p>
		</footer>
	</div>
</article>
<?php endforeach; ?>

<?php

// Get page navigation.
include( THEME_DIR . 'templates/navigation/pagination.php' );
