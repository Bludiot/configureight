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

		$( 'button[data-search-toggle-open]' ).click( function() {
			$( '#search-bar' ).attr( 'aria-expanded', 'true' ).addClass( 'active' );
			$( this ).attr( 'aria-expanded', 'true' ).hide();
		} );

		$( 'button[data-search-toggle-close]' ).click( function() {
			$( '#search-bar' ).attr( 'aria-expanded', 'false' ).removeClass( 'active' );
			$( 'button[data-search-toggle-open]' ).attr( 'aria-expanded', 'false' ).show();
		} );

		// Scroll to content.
		$( '.intro-scroll' ).click( function(e) {
			e.preventDefault();
			$( 'html, body' ).animate( { scrollTop : $( '#content' ).offset().top }, 350 );
		} );
	});
})(jQuery);
