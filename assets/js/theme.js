/**
 * Theme scripts
 */

// Add body class when document is ready.
( function ($) {
	$(document).ready( function() {

		// Add body class when document is ready.
		$( 'body' ).addClass( 'page-loaded' );

		// Apply FitVids to main and aside.
		$( '.page-content, .page-sidebar' ).fitVids();

		$( 'button[data-menu-toggle-open]' ).click( function(e) {
			e.preventDefault();
			$( 'html' ).css( 'height', '100vh' ).css( 'overflow-y', 'hidden' );
			$( '#mobile-nav' ).attr( 'aria-expanded', 'true' ).css( 'display', 'flex' );
			$( this ).attr( 'aria-expanded', 'true' );
		} );

		$( 'button[data-menu-toggle-close]' ).click( function(e) {
			e.preventDefault();
			$( 'html, body' ).scrollTop( 0 );
			$( 'html' ).css( 'height', 'initial' ).css( 'overflow-y', 'initial' );
			$( '#mobile-nav' ).attr( 'aria-expanded', 'false' ).css( 'display', 'none' );
			$( 'button[data-menu-toggle-open]' ).attr( 'aria-expanded', 'true' );
		} );

		$( 'a[data-search-toggle-open]' ).click( function(e) {
			e.preventDefault();
			$( '#search-bar' ).attr( 'aria-expanded', 'true' ).addClass( 'active' );
			$( this ).attr( 'aria-expanded', 'true' ).hide();
		} );

		$( 'button[data-search-toggle-close]' ).click( function() {
			$( '#search-bar' ).attr( 'aria-expanded', 'false' ).removeClass( 'active' );
			$( 'a[data-search-toggle-open]' ).attr( 'aria-expanded', 'false' ).show();
		} );

		// Scroll to content.
		$( '.intro-scroll' ).click( function(e) {
			e.preventDefault();
			$( 'html, body' ).animate( { scrollTop : $( '#content' ).offset().top }, 350 );
		} );

		// Sticky sidebar.
		if ( $.isFunction( $.fn.stick_in_parent ) ) {
			if ( $( '.sidebar-is-sticky' ).length ) {
				$( '.sidebar-is-sticky' ).stick_in_parent( {
					offset_top      : 0,
					inner_scrolling : false
				} );
			}
			if ( $( '.sidebar-header-are-sticky' ).length ) {
				$( '.sidebar-header-are-sticky' ).stick_in_parent( {
					offset_top      : 100,
					inner_scrolling : false
				} );
			}
		}

		// Lightbox.
		if ( $.isFunction( $.fn.fancybox ) ) {
			$( '.page-content img' ).wrap( function() {
				return '<a href=' + this.src + ' data-fancybox></a>';
			} );
		}

		// Scroll to top button/link.
		$( '#to-top' ).click( function(e) {
			e.preventDefault();
			$( 'html, body' ).animate( { scrollTop : 0 }, 450 );
		});

		$(window).scroll( function() {
			var scroll = $(window).scrollTop();

			if ( scroll >= 450 ) {
				$( '#to-top' ).addClass( 'scrolled' );
			} else {
				$( '#to-top' ).removeClass( 'scrolled' );
			}
		});

		// Page loader.
		$( '#page-loader' ).delay( 500 ).fadeOut( 500 );
	});
})(jQuery);

/**
 * Add "scrolled" class to header wrap.
 */
jQuery(window).scroll( function () {

    var scroll = jQuery(window).scrollTop();

    // If scrolled to 150px.
    if ( scroll >= 110 ) {
        jQuery( '.site-header-wrap' ).addClass( 'header-scrolled' );

		jQuery( '#search-bar' ).attr( 'aria-expanded', 'false' ).removeClass( 'active' );
		jQuery( 'a[data-search-toggle-open]' ).attr( 'aria-expanded', 'false' ).show();
    } else {
        jQuery( '.site-header-wrap' ).removeClass( 'header-scrolled' );
    }
});
