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
	theme,
	get_related
};

$get_related = get_related();

// Heading text.
$heading = $L->get( 'Related Posts' );
if ( ! empty( theme()->related_heading() ) ) {
	$heading = theme()->related_heading();
}

?>
<div class="related-posts">

	<?php printf(
		'<%s>%s</%s>',
		theme()->related_heading_el(),
		ucwords( $heading ),
		theme()->related_heading_el()
	); ?>

	<div class="related-loop related-style-<?php echo theme()->related_style(); ?>">
		<?php foreach ( $get_related as $related ) : ?>
		<article class="post item">
			<?php if ( $related->coverImage() ) : ?>
			<figure>
				<img src="<?php echo $related->thumbCoverImage(); ?>" alt="">
			</figure>
			<?php endif; ?>
			<div class="post-inner-content">
				<a href="<?php echo $related->permalink(); ?>" class="post-title">
					<?php echo $related->title(); ?>
				</a>
			</div>
			<div class="post-meta">
				<time datetime="<?php echo $related->dateRaw( 'c' ); ?>">
					<?php echo $related->date(); ?>
				</time>
				<span class="tags">
					<a href="<?php echo DOMAIN_CATEGORIES . $related->categoryKey(); ?>" rel="tag">
						<?php echo $related->category(); ?>
					</a>
				</span>
			</div>
		</article>
		<?php endforeach; ?>
	</div>
</div>
