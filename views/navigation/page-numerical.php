<?php
/**
 * Numerical page navigation template
 *
 * Allows users to navigate paginated content.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

// Import namespaced functions.
use function BSB_Func\{
	blog_url
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

$first_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M77.25 256l137.4-137.4c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0l-160 160c-12.5 12.5-12.5 32.75 0 45.25l160 160C175.6 444.9 183.8 448 192 448s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L77.25 256zM269.3 256l137.4-137.4c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0l-160 160c-12.5 12.5-12.5 32.75 0 45.25l160 160C367.6 444.9 375.8 448 384 448s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L269.3 256z"/></svg>';

$prev_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M192 448c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L77.25 256l137.4 137.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448z"/></svg>';

$next_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"/></svg>';

$last_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M246.6 233.4l-160-160c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L178.8 256l-137.4 137.4c-12.5 12.5-12.5 32.75 0 45.25C47.63 444.9 55.81 448 64 448s16.38-3.125 22.62-9.375l160-160C259.1 266.1 259.1 245.9 246.6 233.4zM438.6 233.4l-160-160c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L370.8 256l-137.4 137.4c-12.5 12.5-12.5 32.75 0 45.25C239.6 444.9 247.8 448 256 448s16.38-3.125 22.62-9.375l160-160C451.1 266.1 451.1 245.9 438.6 233.4z"/></svg>';

if ( Paginator :: numberOfPages() > 1 ) :

?>
<nav class="page-navigation" data-page-navigation>
	<ul class="nav-list pagination-list pagination-numerical">
		<?php if ( $getPage != 1 ) { ?>
		<li>
			<a class="page-end page-first" href="<?php echo $slug; ?>?page=1"><span class="page-icon"><?php echo $first_icon; ?></span> <?php $L->p( 'First' ); ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: showPrev() ) { ?>
		<li>
			<a class="page-nav page-prev" href="<?php echo Paginator :: previousPageUrl(); ?>"><span class="page-icon"><?php echo $prev_icon; ?></span><span class="screen-reader-text"><?php $L->p( 'Previous' ); ?></span></a>
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
			<a class="page-active" href="<?php echo $slug.'?page='.$getPage; ?>"><?php echo $getPage; ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: numberOfPages() - 1 >= $getPage ) { ?>
		<li>
			<a href="<?php echo $slug.'?page='.$getPage + 1; ?>"><?php echo $getPage + 1; ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: numberOfPages() - 2 >= $getPage ) { ?>
		<li>
			<a href="<?php echo $slug.'?page='.$getPage + 2; ?>"><?php echo $getPage + 2; ?></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: showNext() ) { ?>
		<li>
			<a class="page-nav page-next" href="<?php echo Paginator :: nextPageUrl(); ?>"><span class="page-icon"><?php echo $next_icon; ?></span><span class="screen-reader-text"><?php $L->p( 'Next' ); ?></span></a>
		</li>
		<?php } ?>
		<?php if ( Paginator :: numberOfPages() != $getPage ) { ?>
		<li>
			<a class="page-end page-last" href="<?php echo $slug.'?page=' . Paginator :: numberOfPages(); ?>"><?php $L->p( 'Last' ); ?> <span class="page-icon"><?php echo $last_icon; ?></span></a>
		</li>
		<?php } ?>

	</ul>
</nav>
<?php endif;
