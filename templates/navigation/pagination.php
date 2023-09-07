<?php
/**
 * Page navigation template
 *
 * Allows users to navigate paginated content.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

if ( Paginator :: numberOfPages() > 1 ) :

?>
<nav class="page-navigation">
	<ul class="nav-list pagination-list">
	<?php if ( Paginator :: showPrev() ) : ?>
		<li id="prev-page">
			<a class="button" href="<?php echo Paginator :: previousPageUrl(); ?>" tabindex="-1"><?php echo $L->get( 'Previous' ); ?></a>
		</li>
	<?php endif; ?>
	<?php if ( Paginator :: showNext() ) : ?>
		<li id="next-page">
			<a class="button" href="<?php echo Paginator :: nextPageUrl(); ?>"><?php echo $L->get( 'Next' ); ?></a>
		</li>
	<?php endif; ?>
	</ul>
</nav>
<?php endif;
