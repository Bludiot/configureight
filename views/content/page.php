<?php
/**
 * Static page template
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
	has_cover
};
use function CFE_Tags\{
	page_header
};

// Gallery heading.
$gallery_heading = $L->get( 'Image Gallery' );
if ( ! empty( $page->custom( 'gallery_heading' ) ) ) {
	$gallery_heading = $page->custom( 'gallery_heading' );
}

// If author box.
$profiles = getPlugin( 'User_Profiles' );
$author   = 'no-author-box';
if ( $profiles ) {
	if (
		'page' == $profiles->author_display() ||
		'both' == $profiles->author_display()
	) {
		if ( 'before' == $profiles->author_location() ) {
			$author = 'has-author-box author-box-before-content';
		} else {
			$author = 'has-author-box author-box-after-content';
		}
	}
}

?>
<article class="site-article <?php echo $author; ?>" role="article" data-site-article>

	<?php if ( ! has_cover() ) {
		echo page_header();
	} ?>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php echo $page->content(); ?>
	</div>

	<?php if ( $page->custom( 'page_gallery' ) ) : ?>
	<div class="page-gallery">
		<h2><?php echo $gallery_heading; ?></h2>
		<?php plugins_hook( 'page_gallery' ); ?>
	</div>
	<?php endif; ?>
</article>

<?php
// Support for comment plugins.
if ( getPlugin( 'Post_Comments' ) && array_key_exists( 'comments_full', $plugins ) ) {
	plugins_hook( 'comments_full' );
} elseif ( getPlugin( 'easyComments' ) ) {
	easyComments();
} ?>
