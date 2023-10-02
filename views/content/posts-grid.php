<?php
/**
 * Posts page grid template
 *
 * Used for posts loop, whether on the
 * home page or blog page when a static
 * home page is used.
 *
 * Theme config 'loop' > 'content'
 * must be set to 'grid' to use this.
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
	loop_template,
	loop_style,
	sticky_icon,
	page_description,
	has_tags,
	get_author,
	get_loop_pagination
};

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

// Get blog data.
$loop_data = loop_data();

// Category icon.
$cat_icon = '';
if ( THEME_CONFIG['loop']['show_icons'] ) {
	$cat_icon = sprintf(
		'<span class="theme-icon category-icon loop-category-icon loop-full-category-icon" role="icon">%s</span>',
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M464 128H272l-54.63-54.63c-6-6-14.14-9.37-22.63-9.37H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48zm0 272H48V112h140.12l54.63 54.63c6 6 14.14 9.37 22.63 9.37H464v224z"/></svg>'
	);
}

// Tags icon.
$tags_icon = '';
if ( THEME_CONFIG['loop']['show_icons'] ) {
	$tags_icon = sprintf(
		'<span class="theme-icon tags-icon loop-tags-icon loop-full-tags-icon" role="icon">%s</span>',
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M497.941 225.941L286.059 14.059A48 48 0 0 0 252.118 0H48C21.49 0 0 21.49 0 48v204.118a47.998 47.998 0 0 0 14.059 33.941l211.882 211.882c18.745 18.745 49.137 18.746 67.882 0l204.118-204.118c18.745-18.745 18.745-49.137 0-67.882zM259.886 463.996L48 252.118V48h204.118L464 259.882 259.886 463.996zM192 144c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48z"/></svg>'
	);
}

// Schema article itemtype.
$article_type = 'BlogPosting';
if ( 'news' === THEME_CONFIG['loop']['style'] ) {
	$article_type = 'NewsArticle';
}

// User avatar.
$user = new User( Session :: get( 'username' ) );
if ( $user->profilePicture() ) {
	$avatar  = $user->profilePicture();
	$profile = sprintf(
		'%sedit-user/%s',
		DOMAIN_ADMIN,
		Session :: get( 'username' )
	);
} else {
	$avatar  = DOMAIN_THEME . 'assets/images/avatar-default.png';
	$profile = sprintf(
		'%sedit-user/%s#picture',
		DOMAIN_ADMIN,
		Session :: get( 'username' )
	);
}

echo posts_loop_header();

?>
<div class="loop-wrap loop-wrap-<?php echo loop_template(); ?>">
<?php
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

// Tags list.
$tags_list = function() use ( $post, $tags_icon ) {

	$tags  = $post->tags( true );
	$links = [];
	$sep   = ' ';

	if ( $post->tags( true ) ) {
		$html = sprintf(
			'%s<ul class="post-info-tags inline-list tags-list">',
			$tags_icon
		);
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
<article class="site-article" role="article" data-site-article>

	<div class="post-loop-content post-<?php echo loop_template(); ?>-content post-<?php echo loop_style(); ?>-content">

		<header class="page-header post-header post-in-loop-header" data-page-header>
			<h2 class="page-title posts-loop-title">
				<a href="<?php echo $post->permalink(); ?>"><?php echo $sticky . $post->title(); ?></a>
			</h2>
		</header>

		<?php if ( $post->coverImage() ) : ?>
		<figure class="post-cover">
			<a href="<?php echo $post->permalink(); ?>">
				<img src="<?php echo $post->coverImage(); ?>" loading="lazy" />
			</a>
			<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
		</figure>
		<?php endif; ?>

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

			<?php if ( $post->tags( true ) ) {
				echo $tags_list();
			} ?>
		</footer>
	</div>
</article>
<?php endforeach; ?>
</div><!-- .loop-wrap -->
<?php
echo get_loop_pagination();
