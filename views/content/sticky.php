<?php
/**
 * Sticky page (post) template
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	full_cover,
	has_cover,
	get_related
};
use function CFE_Tags\{
	page_header,
	sticky_icon
};

?>
<article class="site-article" role="article" data-site-article>

	<?php if ( ! has_cover() ) {
		echo page_header();
	} ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php echo $page->content(); ?>
	</div>
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
