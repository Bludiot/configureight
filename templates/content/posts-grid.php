<?php
/**
 * Posts page grid template
 *
 * Used for posts loop, whether on the
 * home page or blog page when a static
 * home page is used.
 *
 * The BSB_CONFIG['posts_loop'] constant
 * must be set to `grid` to use this.
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

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'templates/content/no-posts.php' );
	return;
}

// Print header if on static blog page.
if ( 'home' != $url->whereAmI() && 'blog' == $url->whereAmI() ) :
?>
<header class="page-header">
	<h1><?php echo $blog_data['title']; ?></h1>

	<?php printf(
		'<p class="page-description blog-description">%s</p>',
		$blog_data['description']
	); ?>
</header>
<?php
endif;

// If posts, print for each.
foreach ( $content as $post ) :

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
			<h2><a href="<?php echo $post->permalink(); ?>"><?php echo $post->title(); ?></a></h2>
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
