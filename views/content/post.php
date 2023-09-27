<?php
/**
 * Default page (post) template
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	full_cover,
	has_cover
};
use function BSB_Tags\{
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
<?php include( THEME_DIR . 'views/navigation/posts-prev-next.php' ); ?>
