<?php
/**
 * Posts page full content template
 *
 * Used for posts loop, whether on the
 * home page or loop page when a static
 * home page is used.
 *
 * Theme plugin Loop > Loop Style
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
	plugin,
	plugins_hook,
	loop_data,
	is_main_loop,
	is_cat,
	is_tag,
	get_word_count
};
use function CFE_Tags\{
	loop_page_header,
	loop_style,
	loop_type,
	icon,
	sticky_icon,
	article_type,
	page_description,
	has_tags,
	get_author,
	get_loop_pagination
};

// If no posts.
if ( empty( $content ) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

// Get loop data.
$loop_data = loop_data();

// Category icon.
$cat_icon = '';
if ( plugin() ) {
	if ( plugin()->loop_icons() ) {
		$cat_icon = sprintf(
			'<span class="theme-icon loop-icon category-icon loop-category-icon loop-full-category-icon" role="icon">%s</span>',
			icon( 'folder' )
		);
	}
}

// Tags icon.
$tags_icon = '';
if ( plugin() ) {
	if ( plugin()->loop_icons() ) {
		$tags_icon = sprintf(
			'<span class="theme-icon loop-icon tags-icon loop-tags-icon loop-full-tags-icon" role="icon">%s</span>',
			icon( 'tag' )
		);
	}
}

// Tags icon.
$tags_icon = '';
if ( plugin() && plugin()->loop_icons() ) {
	$tags_icon = sprintf(
		'<span class="theme-icon tags-icon loop-tags-icon loop-full-tags-icon" role="icon">%s</span>',
		icon( 'tag' )
	);
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

echo loop_page_header();

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
} elseif ( plugin() ) {
	if ( plugin()->cover_src() ) {
		$thumb_src = plugin()->cover_src();
	}
} else {
	$thumb_src = DOMAIN_THEME . 'assets/images/transparent.png';
}

// Tags list.
$tags_list = function() use ( $post, $tags_icon ) {

	global $L;
	$tags  = $post->tags( true );
	$links = [];
	$sep   = ', ';

	if ( $post->tags( true ) ) {
		$html = sprintf(
			'<ul class="post-info-tags tags-list tags-list-horizontal inline-list"><li>%s',
			$tags_icon
		);
		foreach ( $tags as $tagKey => $tagName ) {

			$links[] = sprintf(
				'<li><a href="%s" class="tag-list-entry tooltip" rel="tag" title="%s %s" data-tooltip>%s</a>',
				DOMAIN_TAGS . $tagKey,
				$L->get( 'Tagged:' ),
				$tagName,
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
	loop_style() . '-view-post-footer',
	'post-info',
	'post-' . loop_type() . '-info'
];
$footer = implode( ' ', $footer );

// Gallery heading.
$gallery_heading = $L->get( 'Image Gallery' );
if ( ! empty( $page->custom( 'gallery_heading' ) ) ) {
	$gallery_heading = $page->custom( 'gallery_heading' );
}

?>
<article id="<?php echo $post->uuid(); ?>" class="site-article loop-article" role="article" itemscope="itemscope" itemtype="<?php echo 'https://schema.org/' . article_type(); ?>" data-site-article>

	<div class="post-loop-content post-<?php echo loop_style(); ?>-content post-<?php echo loop_type(); ?>-content">

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

				<?php if ( $post->category() ) :
					$cat_title_tag = lang()->get( "Browse the {$post->category()} category" );
				?>
				<h3 class="post-info-category">
					<a href="<?php echo $post->categoryPermalink(); ?>" title="<?php echo $cat_title_tag; ?>" data-tooltip><?php echo $cat_icon; ?><?php echo $post->category(); ?></a>
				</h3>
				<?php endif; ?>

				<?php if ( plugin() ) : if ( plugin()->loop_byline() ) : ?>
				<p><span class="post-info-author">
					<?php echo get_author(); ?>
				</span></p>
				<?php endif; endif; ?>

				<?php if ( plugin() ) : if ( plugin()->loop_date() ) : ?>
				<p class="post-info-date">
					<?php echo $post->date(); ?>
				</p>
				<?php endif; endif; ?>

				<?php
				if ( plugin() ) :
				if (
					plugin()->loop_word_count() ||
					plugin()->loop_read_time()
				) :
				?>
				<p class="post-info-details">

					<?php if ( plugin()->loop_word_count() ) : ?>
					<span class="post-info-word-count">
						<?php lang()->p( 'post-word-count' ); echo get_word_count( $post->key() ); ?>
					</span>
					<?php endif; ?>

					<?php if ( plugin()->loop_word_count() && plugin()->loop_read_time() ) : ?>
					<span class="post-info-separator"></span>
					<?php endif; ?>

					<?php if ( plugin()->loop_read_time() ) : ?>
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

			<?php if ( $page->custom( 'page_gallery' ) ) : ?>
			<div class="page-gallery">
				<h2><?php echo $gallery_heading; ?></h2>
				<?php plugins_hook( 'page_gallery' ); ?>
			</div>
			<?php endif; ?>
	</div>

</article>
<?php endforeach; ?>

<?php
echo get_loop_pagination();
