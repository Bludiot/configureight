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
	theme,
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
</article>

<?php
// Related posts.
if ( theme() && theme()->related_posts() && get_related() ) {
	if ( 'grid' == theme()->related_style() ) {
		include( THEME_DIR . 'views/content/partials/related-posts-grid.php' );
	} else {
		include( THEME_DIR . 'views/content/partials/related-posts-list.php' );
	}
} ?>

<?php include( THEME_DIR . 'views/navigation/posts-prev-next.php' ); ?>
