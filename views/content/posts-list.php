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
	loop_data,
	get_word_count
};
use function CFE_Tags\{
	posts_loop_header,
	loop_style,
	sticky_icon,
	page_description,
	has_tags,
	get_author,
	get_loop_pagination
};

// Get blog data.
$loop_data = loop_data();

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

echo posts_loop_header();

// If posts, print for each.
foreach ( $content as $post ) :

// Schema article itemtype.
$article_type = 'BlogPosting';
if ( 'news' === THEME_CONFIG['loop']['style'] ) {
	$article_type = 'NewsArticle';
}

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
} elseif ( THEME_CONFIG['media']['loop_fallback'] ) {
	$thumb_src = DOMAIN_THEME . 'assets/images/' . THEME_CONFIG['media']['loop_fallback'];
}

// Tags list.
$tags_list = function() use ( $post ) {

	$tags  = $post->tags( true );
	$links = [];
	$sep   = ' ';

	if ( $post->tags( true ) ) {
		$html = '<ul class="inline-list tags-list">';
		foreach ( $tags as $tagKey => $tagName ) {

			$links[] = sprintf(
				'<li><a href="%s" class="tag-list-entry" rel="tag">%s</a></li>',
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
<article id="<?php echo $post->uuid(); ?>" class="site-article" role="article" itemscope="itemscope" itemtype="<?php echo 'https://schema.org/' . $article_type; ?>" data-site-article>

	<div class="post-loop-content post-list-content post-<?php echo loop_style(); ?>-content loop-wrap">

		<?php if ( ! empty( $thumb_src ) ) : ?>
		<figure class="post-cover">
			<a href="<?php echo $post->permalink(); ?>">
				<img src="<?php echo $thumb_src; ?>" loading="lazy" />
			</a>
			<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
		</figure>
		<?php endif; ?>

		<div class="page-summary">

			<header class="page-header post-header post-in-loop-header" data-page-header>
				<h2>
					<a href="<?php echo $post->permalink(); ?>"><?php echo $sticky . $post->title(); ?></a>
				</h2>
				<?php echo page_description( $post->key() ); ?>
			</header>

			<footer class="post-info post-<?php echo loop_style(); ?>-info">

				<?php if ( $post->category() ) : ?>
				<h3 class="post-info-category">
					<?php echo $cat_icon; ?><a href="<?php echo $post->categoryPermalink(); ?>"><?php echo $post->category(); ?></a>
				</h3>
				<?php endif; ?>

				<?php if ( THEME_CONFIG['loop']['byline'] ) : ?>
				<p><span class="post-info-author">
					<?php echo get_author(); ?>
				</span></p>
				<?php endif; ?>

				<?php if ( THEME_CONFIG['loop']['post_date'] ) : ?>
				<p class="post-info-date">
					<?php echo $post->date(); ?>
				</p>
				<?php endif; ?>

				<?php
				if (
					THEME_CONFIG['loop']['word_count'] ||
					THEME_CONFIG['loop']['read_time']
				) : ?>
				<p class="post-info-details">

					<?php if ( THEME_CONFIG['loop']['word_count'] ) : ?>
					<span class="post-info-word-count">
						<?php $L->p( 'post-word-count' ); echo get_word_count( $post->key() ); ?>
					</span>
					<?php endif; ?>

					<?php if ( THEME_CONFIG['loop']['read_time'] ) : ?>
					<span class="post-info-separator"></span>
					<span class="post-info-read-time">
						<?php $L->p( 'post-read-time' ); echo $post->readingTime(); ?>
					</span>
					<?php endif; ?>
				</p>
				<?php endif; ?>

				<?php if ( $post->tags( true ) ) : ?>
				<span class="page-info-entry page-info-tags">
					<?php echo $tags_list(); ?>
				</span>
				<?php endif; ?>
			</footer>
		</div>
	</div>
</article>
<?php endforeach; ?>

<?php
echo get_loop_pagination();
