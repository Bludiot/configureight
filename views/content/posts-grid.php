<?php
/**
 * Posts page grid template
 *
 * Used for posts loop, whether on the
 * home page or loop page when a static
 * home page is used.
 *
 * Theme plugin Loop > Loop Style
 * must be set to 'grid' to use this.
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
	loop_data,
	get_word_count
};
use function CFE_Tags\{
	posts_loop_header,
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
<div class="loop-wrap loop-wrap-<?php echo loop_style(); ?>">
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

	$tags  = $post->tags( true );
	$links = [];
	$sep   = ' ';

	if ( $post->tags( true ) ) {
		$html  = '<ul class="post-info-tags inline-list tags-list">';
		$html .= sprintf(
			'<li>%s</li>',
			$tags_icon
		);
		foreach ( $tags as $tagKey => $tagName ) {

			$links[] = sprintf(
				'<li>%s</li>',
				$tagName
			);
		}
		$html .= implode( $sep, $links );
		$html .= '</ul>';

		return $html;
	}
	return '';
};

// Cover image class.
$cover_class = 'post-cover cover-overlay';
if ( plugin() ) {
	if (
		'blend' == plugin()->cover_style() &&
		is_array( plugin()->cover_blend_use() ) &&
		in_array( 'loop', plugin()->cover_blend_use() )
	) {
		$cover_class = 'post-cover cover-blend';
	}
}

?>
<article class="site-article" role="article" itemscope="itemscope" itemtype="<?php echo 'https://schema.org/' . article_type(); ?>" data-site-article>

	<div class="post-loop-content post-<?php echo loop_style(); ?>-content post-<?php echo loop_type(); ?>-content">

		<a href="<?php echo $post->permalink(); ?>">
			<header class="page-header post-header post-in-loop-header" data-page-header>
				<h2 class="page-title posts-loop-title"><?php echo $sticky . $post->title(); ?></h2>
			</header>

			<?php if ( $thumb_src ) : ?>
			<figure class="<?php echo $cover_class; ?>">
				<img src="<?php echo $thumb_src; ?>" loading="lazy" />
				<figcaption class="screen-reader-text"><?php echo $post->title(); ?></figcaption>
			</figure>
			<?php endif; ?>

			<footer class="post-info post-<?php echo loop_type(); ?>-info">

				<?php if ( $post->category() ) : ?>
				<h3 class="post-info-category">
					<?php echo $post->category(); ?>
				</h3>
				<?php endif; ?>

				<?php if ( plugin() ) : if ( plugin()->loop_date() ) : ?>
				<p class="post-info-date">
					<?php echo $post->date(); ?>
				</p>
				<?php endif; endif; ?>

				<?php if ( $post->tags( true ) ) {
					echo $tags_list();
				} ?>
			</footer>
		</a>
	</div>
</article>
<?php endforeach; ?>
</div><!-- .loop-wrap -->
<?php
echo get_loop_pagination();
