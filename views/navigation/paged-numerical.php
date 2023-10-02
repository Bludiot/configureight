<?php
/**
 * Numerical page navigation template
 *
 * Allows users to navigate paginated content.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	blog_url
};
use function CFE_Tags\{
	icon
};

// Get paged page.
if ( ! isset( $_GET['page'] ) ) {
	$getPage = 1;
} else {
	$getPage = $_GET['page'];
}

// Get slug for URLs.
if ( 'blog' == $url->whereAmI() ) {
	$slug = blog_url();
} elseif ( 'category' == $url->whereAmI() ) {
	$slug = DOMAIN_CATEGORIES . $url->slug();
} elseif ( 'tag' == $url->whereAmI() ) {
	$slug = DOMAIN_TAGS . $url->slug();
} else {
	$slug = DOMAIN_BASE . $url->slug();
}

if ( Paginator :: numberOfPages() > 1 ) :

?>
<nav class="page-navigation" data-page-navigation>
	<ul class="nav-list pagination-list pagination-numerical">
		<?php if ( $getPage != 1 ) { ?>
		<li>
			<a class="page-end page-first" href="<?php echo $slug; ?>?page=1"><span class="page-icon"><?php echo icon( 'angles-left' ); ?></span> <?php $L->p( 'First' ); ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: showPrev() ) { ?>
		<li>
			<a class="page-nav page-prev" href="<?php echo Paginator :: previousPageUrl(); ?>" rel="prev"><span class="page-icon"><?php echo icon( 'angle-left' ); ?></span><span class="screen-reader-text"><?php $L->p( 'Previous' ); ?></span></a>
		</li>
		<?php } ?>
		<?php if ( $getPage >= 3 ) { ?>
		<li>
			<a href="<?php echo $slug; ?>?page=<?php echo $getPage - 2; ?>"><?php echo $getPage - 2; ?></a>
		</li>
		<?php } ?>
		<?php if ( $getPage >= 2 ) { ?>
		<li>
			<a href="<?php echo $slug; ?>?page=<?php echo $getPage - 1; ?>"><?php echo $getPage - 1; ?></a>
		</li>
		<?php } ?>
		<?php if ( $getPage ) { ?>
		<li>
			<a class="page-active" href="<?php echo $slug . '?page=' . $getPage; ?>"><?php echo $getPage; ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: numberOfPages() - 1 >= $getPage ) { ?>
		<li>
			<a href="<?php echo $slug . '?page=' . $getPage + 1; ?>"><?php echo $getPage + 1; ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: numberOfPages() - 2 >= $getPage ) { ?>
		<li>
			<a href="<?php echo $slug . '?page=' . $getPage + 2; ?>"><?php echo $getPage + 2; ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: showNext() ) { ?>
		<li>
			<a class="page-nav page-next" href="<?php echo Paginator :: nextPageUrl(); ?>" rel="next"><span class="page-icon"><?php echo icon( 'angle-right' ); ?></span><span class="screen-reader-text"><?php $L->p( 'Next' ); ?></span></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: numberOfPages() != $getPage ) { ?>
		<li>
			<a class="page-end page-last" href="<?php echo $slug . '?page=' . Paginator :: numberOfPages(); ?>"><?php $L->p( 'Last' ); ?> <span class="page-icon"><?php echo icon( 'angles-right' ); ?></span></a>
		</li>
		<?php } ?>

	</ul>
</nav>
<?php endif;
