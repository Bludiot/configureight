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

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

// If posts, print for each.
foreach ( $content as $post ) :

if ( $post->description() ) {
	$description = $post->description();
} else {
	$description = substr( strip_tags( $post->content() ), 0, 85 );
	if ( ! empty( $post->content() ) && ! ctype_space( $post->content() ) ) {
		$description .= '&hellip;';
	}
}

?>
<article class="site-article blog-wrap" role="article" data-site-article>
	<?php if ( $post->coverImage() ) : ?>
	<figure class="page-cover page-cover-blog">
		<a href="<?php echo $post->permalink(); ?>">
			<img src="<?php echo $post->coverImage(); ?>" loading="lazy" />
		</a>
		<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
	</figure>
	<?php endif ?>

	<div class="page-summary" data-page-content>

		<header class="page-header" data-page-header>
			<h2><a href="<?php echo $post->permalink(); ?>"><?php echo $post->title(); ?></a></h2>
		</header>

		<?php echo $description; ?>

		<footer class="page-info">
			<p>
				<?php if ( THEME_CONFIG['posts']['post_date'] ) : ?>
				<span class="page-info-entry">
					<span class="bi bi-calendar" role="img"></span>
					<?php echo $post->date(); ?>
				</span>
				<br />
				<?php endif ?>

				<?php if ( THEME_CONFIG['posts']['read_time'] ) : ?>
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
if ( 'numerical' == THEME_CONFIG['posts']['paged'] ) {
	include( THEME_DIR . 'views/navigation/page-numerical.php' );
} else {
	include( THEME_DIR . 'views/navigation/page-prev-next.php' );
}
