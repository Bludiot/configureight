<?php
/**
 * Titles post navigation template
 *
 * Allows users to navigate between singular posts.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	lang,
	loop_url
};
use function CFE_Tags\{
	prev_key,
	next_key,
	icon
};

if ( prev_key() || next_key() ) :

?>
<nav class="page-navigation" data-page-navigation>
	<ul class="nav-list pagination-list pagination-titles">
	<?php
	if ( prev_key() ) :
		$prev_page = new \Page( prev_key() );
	?>
		<li id="prev-post">
			<a class="posts-nav-title" href="<?php echo $prev_page->permalink(); ?>" rel="prev"><?php echo icon( 'arrow-left', true ); ?> <?php echo $prev_page->title();; ?></a>
		</li>
	<?php
	endif;

	if ( next_key() ) :
		$next_page = new \Page( next_key() );
	?>
		<li id="next-post">
			<a class="posts-nav-title" href="<?php echo $next_page->permalink(); ?>" rel="next"><?php echo $next_page->title(); ?> <?php echo icon( 'arrow-right', true ); ?></a>
		</li>
	<?php endif; ?>
	</ul>
</nav>
<?php endif;
