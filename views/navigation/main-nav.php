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

// Search icon.
$search_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504.1 471l-134-134C399.1 301.5 415.1 256.8 415.1 208c0-114.9-93.13-208-208-208S-.0002 93.13-.0002 208S93.12 416 207.1 416c48.79 0 93.55-16.91 129-45.04l134 134C475.7 509.7 481.9 512 488 512s12.28-2.344 16.97-7.031C514.3 495.6 514.3 480.4 504.1 471zM48 208c0-88.22 71.78-160 160-160s160 71.78 160 160s-71.78 160-160 160S48 296.2 48 208z"/></svg>';

?>
<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="https://schema.org/SiteNavigationElement" data-site-navigation>
	<ul class="nav-list main-nav-list">
		<?php

		$max_items = (integer)THEME_CONFIG['main_nav']['max_items'];
		$nav_items = array_slice( $staticContent, 0, $max_items );

		foreach ( $nav_items as $nav_item ) :

		$nav_entry = '';
		$home_uri  = $site->getField( 'homepage' );
		$blog_uri  = $site->getField( 'uriBlog' );

		/**
		 * Do not list static front page or
		 * blog not on the home page because
		 * they are added at the end of the
		 * top-level entries.
		 */
		if (
			$nav_item->slug() == $home_uri ||
			$nav_item->slug() == str_replace( '/', '', $site->getField( 'uriBlog' ) )
		) {
			$nav_entry = '';

		// Do not list the 404 error page.
		} elseif ( $nav_item->slug() == str_replace( '/', '', $site->getField( 'pageNotFound' ) ) ) {
			$nav_entry = '';

		// Parent item & children submenu.
		} elseif ( $nav_item->hasChildren() ) {

			$children = $nav_item->children();
			$sub_menu = '<ul class="nav-list main-nav-sub-list">';

			foreach ( $children as $child ) {
				$sub_menu .= sprintf(
					'<li><a href="%s">%s</a></li>',
					$child->permalink(),
					$child->title()
				);
			}
			$sub_menu .= '</ul>';

			$nav_entry = sprintf(
				'<li><a href="%s">%s</a>%s</li>',
				$nav_item->permalink(),
				$nav_item->title(),
				$sub_menu
			);

		// Page without children.
		} elseif ( ! $nav_item->parent() ) {
			$nav_entry = sprintf(
				'<li><a href="%s">%s</a></li>',
				$nav_item->permalink(),
				$nav_item->title()
			);
		}
		echo $nav_entry;
		endforeach;

		// Add blog link if home is static content.
		if ( ! empty( $home_uri ) && ! empty( $blog_uri ) ) {

			// If true in config file.
			if ( THEME_CONFIG['main_nav']['blog'] ) {
				printf(
					'<li><a href="%s">%s</a></li>',
					$site->url() . str_replace( '/', '', $blog_uri ) . '/',
					ucwords( str_replace( [ '/', '-', '_' ], ' ', $blog_uri ) )
				);
			}
		}

		// Add a home link if true in config file.
		if ( THEME_CONFIG['main_nav']['home'] ) {
			printf(
				'<li><a href="%s">%s</a></li>',
				$site->url(),
				$L->get( 'home-link-label' )
			);
		}

		// Add a search toggle button.
		if (
			THEME_CONFIG['main_nav']['search'] &&
			getPlugin( 'pluginSearch' )
		) {
			printf(
				'<li class="hide-if-no-js"><button data-search-toggle-open  aria-controls="search-bar" aria-expanded="false">%s<span class="screen-reader-text">%s</span></button></li>',
				$search_icon,
				$L->get( 'search-link-label' )
			);
		}

		?>
	</ul>
</nav>
