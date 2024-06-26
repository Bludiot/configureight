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
	has_tags
};

$get_related = get_related();

// Heading text.
$heading    = $L->get( 'Related Posts' );
$heading_el = 'h2';
if ( plugin() ) {
	$heading = '';
	if ( ! empty( plugin()->related_heading() ) ) {
		$heading = plugin()->related_heading();
	}
	$heading_el = plugin()->related_heading_el();
}

// Related style.
$related_style = 'list';
$cover_image_class = '';
if ( plugin() ) {
	$related_style = plugin()->related_style() . ' cover-overlay';

	if (
		'blend' == plugin()->cover_style() &&
		is_array( plugin()->cover_blend_use() ) &&
		in_array( 'related', plugin()->cover_blend_use() )
	) {
		$related_style = plugin()->related_style() . ' cover-blend';
	}
	if ( in_array( 'related', plugin()->cover_desaturate_use() ) ) {
		$cover_image_class = 'desaturate';
	}
}

// Use loop date.
$loop_date = true;
if ( plugin() ) {
	$loop_date = plugin()->loop_date();
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

	<?php if ( ! empty( $heading ) ) {
		printf(
			'<%s class="related-posts-heading">%s</%s>',
			$heading_el,
			ucwords( $heading ),
			$heading_el
		);
	} ?>

	<div class="related-loop related-style-<?php echo $related_style; ?> <?php echo $max; ?>">
		<?php foreach ( $get_related as $related ) : ?>
		<article class="related-post">
			<?php if ( $related->coverImage() ) : ?>
			<a href="<?php echo $related->permalink(); ?>">
				<figure class="related-cover">
					<img class="<?php echo $cover_image_class; ?>" src="<?php echo $related->thumbCoverImage(); ?>" alt="">
				</figure>
			</a>
			<?php endif; ?>
			<div class="related-content">
				<p class="related-title"><a href="<?php echo $related->permalink(); ?>">
					<?php echo $related->title(); ?>
				</a></p>
				<p class="related-description"><?php echo page_description( $related->key() ); ?></p>
				<div class="related-meta">
					<?php if ( $loop_date ) : ?>
					<time datetime="<?php echo $related->dateRaw( 'c' ); ?>">
						<?php echo $related->date(); ?>
					</time>
					<?php endif; ?>
				</div>
			</div>
		</article>
		<?php endforeach; ?>
	</div>
</div>
