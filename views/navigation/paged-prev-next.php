<?php
/**
 * Prev/next page navigation template
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

if ( ! isset( $_GET['page'] ) ) {
	$getPage = 1;
} else {
	$getPage = $_GET['page'];
}

if ( Paginator :: numberOfPages() > 1 ) :

?>
<nav class="page-navigation" data-page-navigation>
	<ul class="nav-list pagination-list pagination-prev-next">
	<?php if ( Paginator :: showPrev() ) : ?>
		<li id="prev-page">
			<a class="button" href="<?php echo Paginator :: previousPageUrl(); ?>" tabindex="-1" rel="prev"><?php echo $L->get( 'Previous' ); ?></a>
		</li>
	<?php endif; ?>
	<?php if ( Paginator :: showNext() ) : ?>
		<li id="next-page">
			<a class="button" href="<?php echo Paginator :: nextPageUrl(); ?>" rel="next"><?php echo $L->get( 'Next' ); ?></a>
		</li>
	<?php endif; ?>
	</ul>
</nav>
<?php endif;
