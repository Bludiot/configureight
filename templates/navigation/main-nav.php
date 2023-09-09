<?php
/**
 * Main navigation template
 *
 * This is a starter theme, a boilerplate,
 * so no mobile menu toggle is provided as
 * these can be styled and structured in
 * many ways. This is simply navigation
 * bones upon which to build.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

?>
<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="http://schema.org/SiteNavigationElement">
	<ul class="nav-list main-nav-list">
		<?php foreach ( $staticContent as $nav_item ) :

		// Front page label.
		if (
			$nav_item->slug() == $site->getField( 'homepage' ) ||
			$nav_item->slug() == str_replace( '/', '', $site->getField( 'uriBlog' ) )
		) {
			$nav_item_title = '';
		} else {
			$nav_item_title = $nav_item->title();
			$nav_item_title = sprintf(
				'<li><a href="%s">%s</a></li>',
				$nav_item->permalink(),
				$nav_item->title()
			);
		}

			echo $nav_item_title;

		endforeach;

		// Add blog link if home is static content.
		$blog_uri = $site->getField( 'uriBlog' );
		if ( ! empty( $site->getField( 'homepage' ) ) && ! empty( $blog_uri ) ) {
			printf(
				'<li><a href="%s">%s</a></li>',
				$site->url() . str_replace( '/', '', $blog_uri ) . '/',
				ucwords( str_replace( [ '/', '-', '_' ], ' ', $blog_uri ) )
			);
		}

		// Add a home link.
		printf(
			'<li><a href="%s">%s</a></li>',
			$site->url(),
			$L->get( 'home-link-label' )
		);
		?>
	</ul>
</nav>
