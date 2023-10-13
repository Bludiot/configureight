<?php
/**
 * Posts page template
 *
 * Used for posts loop, whether on the
 * home page or blog page when a static
 * home page is used.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	theme
};

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
	<?php endif; ?>

	<div class="page-summary" data-page-content>

		<header class="page-header" data-page-header>
			<h2><a href="<?php echo $post->permalink(); ?>"><?php echo $post->title(); ?></a></h2>
		</header>

		<?php echo $description; ?>

		<footer class="page-info">
			<p>
				<?php if ( theme() && theme()->loop_date() ) : ?>
				<span class="page-info-entry">
					<span class="bi bi-calendar" role="img"></span>
					<?php echo $post->date(); ?>
				</span>
				<br />
				<?php endif; ?>
			</p>
		</footer>
	</div>
</article>
<?php endforeach; ?>

<?php
echo get_loop_pagination();
