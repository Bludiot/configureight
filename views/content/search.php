<?php
/**
 * Search template
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
	is_search,
	loop_data,
	get_word_count
};
use function CFE_Tags\{
	loop_style,
	loop_type,
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

// Schema article itemtype.
$article_type = 'BlogPosting';
if ( plugin() ) {
	if ( 'news' == plugin()->loop_type() ) {
		$article_type = 'NewsArticle';
	}
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
			'%s<ul class="post-info-tags inline-list tags-list tags-list-horizontal inline-list">',
			$tags_icon
		);
		foreach ( $tags as $tagKey => $tagName ) {

			$links[] = sprintf(
				'<li><a href="%s" class="tag-list-entry tooltip" rel="tag" title="%s %s" data-tooltip>%s</a></li>',
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

// Cover image classes.
$cover_wrap_class  = 'post-cover cover-overlay';
$cover_image_class = '';
if ( plugin() ) {
	if (
		'blend' == plugin()->cover_style() &&
		is_array( plugin()->cover_blend_use() )
	) {
		if ( is_search() && in_array( 'search', plugin()->cover_blend_use() ) ) {
			$cover_wrap_class = 'post-cover cover-blend';
		}
	}
	if ( in_array( 'covers', plugin()->cover_desaturate_use() ) ) {
		$cover_image_class = 'desaturate';
	}
}

?>
<article id="<?php echo $post->uuid(); ?>" class="site-article loop-article" role="article" itemscope="itemscope" itemtype="<?php echo 'https://schema.org/' . $article_type; ?>" data-site-article>

<div class="post-loop-content post-list-content">

	<header class="page-header post-header post-in-loop-header" data-page-header>
		<h2 class="page-title posts-loop-title">
			<a href="<?php echo $post->permalink(); ?>"><?php echo $sticky . $post->title(); ?></a>
		</h2>
		<p class="page-description posts-loop-description"><?php echo page_description( $post->key() ); ?></p>
	</header>

	<?php if ( $thumb_src ) : ?>
	<div class="post-cover-wrap">
		<figure class="<?php echo $cover_wrap_class; ?>">
			<a href="<?php echo $post->permalink(); ?>">
				<img class="<?php echo $cover_image_class; ?>" src="<?php echo $thumb_src; ?>" loading="lazy" />
			</a>
			<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
		</figure>
	</div>
	<?php endif; ?>

	<footer class="post-info post-<?php echo loop_type(); ?>-info">

		<?php if ( $post->category() ) : ?>
		<h3 class="post-info-category">
			<a href="<?php echo $post->categoryPermalink(); ?>"><?php echo $cat_icon; ?><?php echo $post->category(); ?></a>
		</h3>
		<?php endif; ?>

		<?php if ( plugin() ) : if ( plugin()->loop_byline() ) : ?>
		<p><span class="post-info-author">
			<?php // echo get_author(); ?>
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

<?php
echo get_loop_pagination();
