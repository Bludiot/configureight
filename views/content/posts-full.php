<?php
/**
 * Posts page full content template
 *
 * Used for posts loop, whether on the
 * home page or loop page when a static
 * home page is used.
 *
 * Theme plugin Loop > Content Style
 * must be set to 'full' to use this.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	lang,
	theme,
	loop_data,
	get_word_count
};
use function CFE_Tags\{
	posts_loop_header,
	loop_content_style,
	loop_style,
	icon,
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

// Get loop data.
$loop_data = loop_data();

// Category icon.
$cat_icon = '';
if ( theme() && theme()->loop_icons() ) {
	$cat_icon = sprintf(
		'<span class="theme-icon category-icon loop-category-icon loop-full-category-icon" role="icon">%s</span>',
		icon( 'folder' )
	);
}

// Tags icon.
$tags_icon = '';
if ( theme() && theme()->loop_icons() ) {
	$tags_icon = sprintf(
		'<span class="theme-icon tags-icon loop-tags-icon loop-full-tags-icon" role="icon">%s</span>',
		icon( 'tag' )
	);
}

// Schema article itemtype.
$article_type = 'BlogPosting';
if ( theme() && 'news' == theme()->loop_style() ) {
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
if ( $post->coverImage() ) {
	$thumb_src = $post->coverImage();
} elseif ( theme() ) {
	if ( theme()->cover_src() ) {
		$thumb_src = theme()->cover_src();
	}
} else {
	$thumb_src = DOMAIN_THEME . 'assets/images/transparent.png';
}

// Tags list.
$tags_list = function() use ( $post, $tags_icon ) {

	$tags  = $post->tags( true );
	$links = [];
	$sep   = ' ';

	if ( $post->tags( true ) ) {
		$html = sprintf(
			'%s<ul class="post-info-tags tags-list tags-list-horizontal tags-list-buttons inline-list">',
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

// Post footer classes.
$footer = [
	loop_content_style() . '-view-post-footer',
	'post-info',
	'post-' . loop_style() . '-info'
];
$footer = implode( ' ', $footer );

?>
<article id="<?php echo $post->uuid(); ?>" class="site-article" role="article" itemscope="itemscope" itemtype="<?php echo 'https://schema.org/' . $article_type; ?>" data-site-article>

	<div class="post-loop-content post-<?php echo loop_content_style(); ?>-content post-<?php echo loop_style(); ?>-content">

		<header class="page-header post-header post-in-loop-header" data-page-header>
			<h2 class="page-title posts-loop-title">
				<a href="<?php echo $post->permalink(); ?>"><?php echo $sticky . $post->title(); ?></a>
			</h2>
			<p><?php echo page_description( $post->key() ); ?></p>
		</header>

		<?php if ( $thumb_src ) : ?>
		<figure class="post-cover">
			<a href="<?php echo $post->permalink(); ?>">
				<img src="<?php echo $thumb_src; ?>" loading="lazy" />
			</a>
			<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
		</figure>
		<?php endif; ?>

		<div class="post-in-loop-content" itemprop="articleBody" data-post-content>
			<?php echo $post->content(); ?>
		</div>

			<footer class="<?php echo $footer; ?>">

				<?php if ( $post->category() ) : ?>
				<h3 class="post-info-category">
					<?php echo $cat_icon; ?><a href="<?php echo $post->categoryPermalink(); ?>"><?php echo $post->category(); ?></a>
				</h3>
				<?php endif; ?>

				<?php if ( theme() && theme()->loop_byline() ) : ?>
				<p><span class="post-info-author">
					<?php echo get_author(); ?>
				</span></p>
				<?php endif; ?>

				<?php if ( theme() && theme()->loop_date() ) : ?>
				<p class="post-info-date">
					<?php echo $post->date(); ?>
				</p>
				<?php endif; ?>

				<?php
				if ( theme() ) :
				if (
					theme()->loop_word_count() ||
					theme()->loop_read_time()
				) :
				?>
				<p class="post-info-details">

					<?php if ( theme()->loop_word_count() ) : ?>
					<span class="post-info-word-count">
						<?php lang()->p( 'post-word-count' ); echo get_word_count( $post->key() ); ?>
					</span>
					<?php endif; ?>

					<?php if ( theme()->loop_word_count() && theme()->loop_read_time() ) : ?>
					<span class="post-info-separator"></span>
					<?php endif; ?>

					<?php if ( theme()->loop_read_time() ) : ?>
					<span class="post-info-read-time">
						<?php lang()->p( 'post-read-time' ); echo $post->readingTime(); ?>
					</span>
					<?php endif; ?>
				</p>
				<?php endif; endif; ?>

				<?php if ( $post->tags( true ) ) {
					echo $tags_list();
				} ?>
			</footer>
	</div>

</article>
<?php endforeach; ?>

<?php
echo get_loop_pagination();
