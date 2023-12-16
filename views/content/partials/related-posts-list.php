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
	if ( ! empty( plugin()->related_heading() ) ) {
		$heading = plugin()->related_heading();
	}
	$heading_el = plugin()->related_heading_el();
}

// Related style.
$related_style = 'list';
if ( plugin() ) {
	$related_style = plugin()->related_style();
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

	<?php printf(
		'<%s class="related-posts-heading">%s</%s>',
		$heading_el,
		ucwords( $heading ),
		$heading_el
	); ?>

	<div class="related-loop related-style-<?php echo $related_style; ?> <?php echo $max; ?>">
		<?php foreach ( $get_related as $related ) : ?>
		<article class="related-post">
			<?php if ( $related->coverImage() ) : ?>
			<figure class="related-cover">
				<a href="<?php echo $related->permalink(); ?>">
					<img src="<?php echo $related->thumbCoverImage(); ?>" alt="">
				</a>
			</figure>
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
