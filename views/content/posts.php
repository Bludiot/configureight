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
use function BSB_Func\{
	blog_data
};
use function BSB_Tags\{
	posts_loop_header,
	sticky_icon,
	page_description,
	has_tags,
	get_author
};

// Get blog data.
$blog_data = blog_data();

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

echo posts_loop_header();

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

// Thumbnail image.
$thumb_src = '';
if ( $post->thumbCoverImage() ) {
	$thumb_src = $post->thumbCoverImage();
} elseif ( $post->coverImage() ) {
	$thumb_src = $post->coverImage();
}

// Tags list.
$tags_list = function() use ( $post ) {

	$tags  = $post->tags( true );
	$links = [];
	$sep   = ' ';

	if ( has_tags() ) {
		$html = '<ul class="inline-list tags-list">';
		foreach ( $tags as $tagKey => $tagName ) {

			$links[] = sprintf(
				'<a href="%s" class="tag-list-entry" rel="tag">%s</a>',
				DOMAIN_TAGS . $tagKey,
				$tagName
			);
		}
		$html .= implode( $sep, $links );
		$html .= '</ul>';

		return $html;
	}
	return '';
};

?>
<article class="site-article blog-wrap" role="article" data-site-article>
	<?php if ( ! empty( $thumb_src ) ) : ?>
	<figure class="page-cover page-cover-blog">
		<a href="<?php echo $post->permalink(); ?>">
			<img src="<?php echo $thumb_src; ?>" loading="lazy" />
		</a>
		<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
	</figure>
	<?php endif ?>

	<div class="page-summary" data-page-content>

		<header class="page-header" data-page-header>
			<h2><a href="<?php echo $post->permalink(); ?>"><?php echo $sticky . $post->title(); ?></a></h2>
		</header>

		<?php echo page_description(); ?>

		<footer class="page-info">
			<p>
				<?php if ( THEME_CONFIG['posts']['byline'] ) : ?>
				<span class="page-info-entry page-info-author">
					<?php echo get_author(); ?>
				</span>
				<?php endif ?>

				<?php if ( THEME_CONFIG['posts']['post_date'] ) : ?>
				<span class="page-info-entry page-info-date">
					<?php echo $post->date(); ?>
				</span>
				<br />
				<?php endif ?>

				<?php if ( has_tags() ) : ?>
				<span class="page-info-entry page-info-tags">
					<?php echo $tags_list(); ?>
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
