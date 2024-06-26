<?php
/**
 * Front page slider
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Partials
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	site,
	lang,
	is_rtl,
	page
};
use function CFE_Tags\{
	page_description,
	icon
};

// Option variables.
$recent = ( 'recent' == plugin()->slider_content() ? true : false );
$static = ( 'static' == plugin()->slider_content() ? true : false );

// Get published & sticky posts, full objects.
$slider = $pages->getPublishedDB();
$sticky = $pages->getStickyDB();
if ( isset( $sticky[0] ) ) {
	$slider = array_merge( $sticky, $slider );
}

// Get static pages.
if ( $static ) {
	$slider = $staticContent;
}

// Maximum recent posts.
if ( $recent ) {
	$slider = array_slice( $slider, 0, plugin()->slider_number() );
}

// Cover image classes.
$cover_wrap_class  = 'full-cover-image cover-overlay';
$cover_image_class = '';
if (
	'blend' == plugin()->cover_style() &&
	is_array( plugin()->cover_blend_use() ) &&
	in_array( 'slider', plugin()->cover_blend_use() )
) {
	$cover_wrap_class = 'full-cover-image cover-blend';
}
if ( in_array( 'slider', plugin()->cover_desaturate_use() ) ) {
	$cover_image_class = 'desaturate';
}

// Icons.
$link_arrow = 'arrow-right-light';
$prev_arrow = 'arrow-left-light';
$next_arrow = 'arrow-right-light';
if ( is_rtl() ) {
	$link_arrow = 'arrow-left-light';
	$prev_arrow = 'arrow-right-light';
	$next_arrow = 'arrow-left-light';
}

// Arrow options.
if ( 'angle' == plugin()->slider_arrows() ) {
	$prev_arrow = 'angle-left-light';
	$next_arrow = 'angle-right-light';

	if ( is_rtl() ) {
		$prev_arrow = 'angle-right-light';
		$next_arrow = 'angle-left-light';
	}
} elseif ( 'angles' == plugin()->slider_arrows() ) {
	$prev_arrow = 'angles-left-light';
	$next_arrow = 'angles-right-light';

	if ( is_rtl() ) {
		$prev_arrow = 'angles-right-light';
		$next_arrow = 'angles-left-light';
	}
}

// Autoplay speed.
$duration = plugin()->slider_duration();
if ( str_contains( $duration, '.' ) ) {
	$duration = str_replace( '.', '', plugin()->slider_duration() ) . '00';
} else {
	$duration = $duration . '000';
}

// Loading icon.
$icon_type = 'spinner-dots';
$icon_show = true;
if ( plugin() ) {
	if ( 'spinner-dashes' == plugin()->slider_icon() ) {
		$icon_type = 'spinner-dashes';
	} elseif ( 'spinner-third' == plugin()->slider_icon() ) {
		$icon_type = 'spinner-third';
	}

	if ( 'none' == plugin()->slider_icon() ) {
		$icon_show = false;
	}
}

// Down icon.
$icon_down = 'angle-down-light';
if ( plugin() ) {
	if ( plugin()->cover_icon() ) {
		$icon_down = plugin()->cover_icon();
	} else {
		$icon_down = '';
	}
}

// Slider markup.
if ( isset( $slider[0] ) ) : ?>
<div id="slider-loading">
	<?php if ( $icon_show ) : ?>
	<p class="loading-text"><?php lang()->p( 'Loading Slides' ); ?></p>
	<div class="loading-image <?php echo $icon_type; ?>">
		<?php echo icon( $icon_type ); ?>
	</div>
	<?php endif; ?>
</div>

<div id="front-page-slider" class="slider-wrap front-page-slider hide-if-no-js">
<?php
foreach ( $slider as $slide ) :

	// Get recent post object.
	if ( $recent ) {
		$slide = new \Page( $slide );
	}

	// Static pages already return object.
	if ( $static ) {

		// Skip pages not selected.
		if ( ! in_array( $slide->key(), plugin()->slider_pages() ) ) {
			continue;
		}
	}

	// Skip if no cover image.
	if ( ! $slide->coverImage() ) {
		continue;
	}

	// Link text.
	$link_text = lang()->get( 'Read More' );
	if ( $slide->custom( 'read_more' ) ) {
		$link_text = $slide->custom( 'read_more' );
	} elseif ( ! empty( plugin()->slider_link_text() ) ) {
		$link_text = plugin()->slider_link_text();
	}

	// Link markup.
	if ( $slide->key() == site()->homepage() ) {
		$link = sprintf(
			'<a href="#content" class="button intro-scroll %s hide-if-no-js"><span class="screen-reader-text">%s</span>%s</a>',
			$icon_down,
			lang()->get( 'Scroll to Content' ),
			icon( $icon_down )
		);
	} else {
		$link = sprintf(
			'<a class="slider-more" href="%s"><span>%s</span> %s</a>',
			$slide->permalink() . '/',
			$link_text,
			icon( $link_arrow, true, 'slider-more-icon' )
		);
	}

	?>
	<div class="<?php echo $cover_wrap_class; ?> page-slide">
		<figure>
			<img class="<?php echo $cover_image_class; ?>" src="<?php echo $slide->coverImage(); ?>" role="presentation">
		</figure>
		<div class="cover-header" data-cover-header>
			<h2 class="cover-title"><a href="<?php echo $slide->permalink() . '/'; ?>"><?php echo $slide->title(); ?></a></h2>
			<p class="cover-description"><?php echo page_description( $slide->key() ); ?></p>
			<?php echo $link; ?>
		</div>
	</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<script>
jQuery(document).ready( function($) {

	// Instantiate Slick slider for the front page slider.
	$( '#front-page-slider' ).slick({
		arrows         : <?php echo ( 'none' != plugin()->slider_arrows() ? 'true' : 'false' ) ?>,
		prevArrow      : '<?php echo icon( $prev_arrow, true, 'slick-prev' ); ?>',
        nextArrow      : '<?php echo icon( $next_arrow, true, 'slick-next' ); ?>',
		dots           : <?php echo ( plugin()->slider_dots() ? 'true' : 'false' ) ?>,
		slidesToScroll : 1,
		autoplay       : true,
		autoplaySpeed  : <?php echo $duration; ?>,
		infinite       : true,
		adaptiveHeight : false,
		speed          : 800,
		pauseOnHover   : false,
		pauseOnFocus   : false,
		fade           : <?php echo ( 'slide' == plugin()->slider_animate() ? 'false' : 'true' ) ?>,
		cssEase        : 'ease-in-out',
		rtl            : <?php echo ( is_rtl() ? 'true' : 'false' ); ?>
	});

	// Show slides when page is loaded.
	$( '.page-slide' ).css( 'opacity', '1' );
	$( '#slider-loading' ).hide();
});
</script>
