<?php
/**
 * About page template
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	full_cover,
	has_cover
};
use function CFE_Tags\{
	page_header
};

// If author box.
$profiles = getPlugin( 'User_Profiles' );
$author   = 'no-author-box';
if ( $profiles ) {
	if (
		'post' == $profiles->author_display() ||
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
</article>
