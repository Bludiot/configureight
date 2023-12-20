<?php
/**
 * Related posts display
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	get_related
};
use function CFE_Tags\{
	page_description,
	icon
};

$get_related = get_related();

// Heading text.
$heading    = $L->get( 'Related Posts' );
$heading_el = 'h2';
if ( plugin() ) {
	if ( ! empty( plugin()->related_heading() ) ) {
		$heading = plugin()->related_heading();
	}
	$heading_el = plugin()->related_heading_el();
}

// Related style.
$related_style = 'list';
if ( plugin() ) {
	$related_style = plugin()->related_style() . ' cover-overlay';

	if (
		'blend' == plugin()->cover_style() &&
		is_array( plugin()->cover_blend_use() ) &&
		in_array( 'related', plugin()->cover_blend_use() )
	) {
		$related_style = plugin()->related_style() . ' cover-blend';
	}
}

// Maximum posts class.
$max = 'max-related-3';
if ( plugin() ) {
	if ( 3 != plugin()->max_related() ) {
		$max = sprintf(
			'max-related-%s',
			plugin()->max_related()
		);
	}
}

?>
<div class="related-posts">

	<?php printf(
		'<%s>%s</%s>',
		$heading_el,
		ucwords( $heading ),
		$heading_el
	); ?>

	<div class="related-loop related-style-<?php echo $related_style; ?> <?php echo $max; ?>">
		<?php foreach ( $get_related as $related ) : ?>
		<article class="related-post">
			<a href="<?php echo $related->permalink(); ?>">
				<?php if ( $related->coverImage() ) : ?>
				<figure class="related-cover">
					<img src="<?php echo $related->thumbCoverImage(); ?>" alt="">
				</figure>
				<?php endif; ?>
				<div class="related-content">
					<p class="related-title">
						<?php echo $related->title(); ?>
					</p>
					<p class="related-description"><?php echo page_description( $related->key() ); ?></p>
				</div>
			</a>
		</article>
		<?php endforeach; ?>
	</div>
</div>
