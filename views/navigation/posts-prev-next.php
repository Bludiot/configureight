<?php
/**
 * Prev/next post navigation template
 *
 * Allows users to navigate between singular posts.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Tags\{
	prev_key,
	next_key
};

if ( prev_key() || next_key() ) :

?>
<nav class="page-navigation" data-page-navigation>
	<ul class="nav-list pagination-list pagination-prev-next">
	<?php
	if ( prev_key() ) :
		$prev_page = new \Page( prev_key() );
	?>
		<li id="prev-post">
			<a class="button" href="<?php echo $prev_page->permalink(); ?>" title="<?php echo $prev_page->title(); ?>" rel="prev"><?php echo $L->get( 'Previous' ); ?></a>
		</li>
	<?php
	endif;

	if ( next_key() ) :
		$next_page = new \Page( next_key() );
	?>
		<li id="next-post">
			<a class="button" href="<?php echo $next_page->permalink(); ?>" title="<?php echo $next_page->title(); ?>" rel="next"><?php echo $L->get( 'Next' ); ?></a>
		</li>
	<?php endif; ?>
	</ul>
</nav>
<?php endif;
