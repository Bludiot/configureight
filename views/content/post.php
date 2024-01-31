<?php
/**
 * Default page (post) template
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	plugins_hook,
	full_cover,
	has_cover,
	get_related
};
use function CFE_Tags\{
	page_header
};

?>
<article class="site-article" role="article" data-site-article>

	<?php if ( ! has_cover() ) {
		echo page_header();
	} ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php echo $page->content(); ?>
	</div>

	<?php if ( $page->custom( 'page_gallery' ) ) : ?>
	<div class="page-gallery">
		<h2><?php $L->p( 'Image Gallery' ); ?></h2>
		<?php plugins_hook( 'page_gallery' ); ?>
	</div>
	<?php endif; ?>
</article>

<?php

// Related posts.
if ( plugin() ) {
	if ( plugin()->related_posts() && get_related() ) {
		if ( 'grid' == plugin()->related_style() ) {
			include( THEME_DIR . 'views/content/partials/related-posts-grid.php' );
		} else {
			include( THEME_DIR . 'views/content/partials/related-posts-list.php' );
		}
	}
} else {
	include( THEME_DIR . 'views/content/partials/related-posts-list.php' );
} ?>

<?php

// Posts navigation.
if ( plugin() ) {
	if ( plugin()->posts_nav() ) {
		if ( 'titles' == plugin()->posts_nav_type() ) {
			include( THEME_DIR . 'views/navigation/posts-titles.php' );
		} else {
			include( THEME_DIR . 'views/navigation/posts-prev-next.php' );
		}
	}
} else {
	include( THEME_DIR . 'views/navigation/posts-prev-next.php' );
} ?>