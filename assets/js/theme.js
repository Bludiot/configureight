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

		// Add cover blend class to table galleries.
		$( '.cover-blend-active table.page-gallery tr' ).addClass( 'cover-blend' );

		// Lightbox.
		if ( $.isFunction( $.fn.fancybox ) ) {
			$( '.page-content [href$=".jpg"], .page-content [href$=".jpeg"], .page-content [href$=".png"], .page-content [href$=".gif"]' ).attr( 'data-fancybox', 'page-content-gallery' );
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

		// Add "scrolled" class to header wrap.
		$(window).scroll( function () {

			var scroll = $(window).scrollTop();
			var size   = $(window).width();

			if ( size > 960 ) {
				if ( scroll >= 110 ) {
					$( '.site-header-wrap' ).addClass( 'header-scrolled' );
				} else {
					$( '.site-header-wrap' ).removeClass( 'header-scrolled' );
				}
			}
		});

		// Page loader.
		$( '#page-loader' ).delay( 500 ).fadeOut( 500 );
	});
})(jQuery);
