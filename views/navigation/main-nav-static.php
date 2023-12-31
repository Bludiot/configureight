<?php
/**
 * Main navigation template
 *
 * This file is used if the theme's companion
 * plugin is not installed.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	site,
	plugin,
	lang,
	loop_url
};
use function CFE_Tags\{
	icon,
	menu_toggle,
	nav_loop_label,
	social_nav
};

?>
<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="https://schema.org/SiteNavigationElement" data-site-navigation>
	<ul class="nav-list main-nav-list">
		<?php

		$home_uri  = site()->getField( 'homepage' );
		$loop_uri  = site()->getField( 'uriBlog' );
		$nav_items = $staticContent;
		$max_items = 12;

		if ( $max_items > 0 ) {
			$nav_items = array_slice( $staticContent, 1, $max_items );
		}

		foreach ( $nav_items as $nav_item ) :

		$nav_entry = '';

		/**
		 * Do not list static front page or
		 * loop not on the home page because
		 * they are added at the end of the
		 * top-level entries.
		 */
		if (
			$nav_item->slug() == $home_uri ||
			$nav_item->slug() == str_replace( '/', '', site()->getField( 'uriBlog' ) )
		) {
			$nav_entry = '';

		// Do not list the 404 error page.
		} elseif ( $nav_item->slug() == str_replace( '/', '', site()->getField( 'pageNotFound' ) ) ) {
			$nav_entry = '';

		// Parent item & children submenu.
		} elseif ( $nav_item->hasChildren() ) {

			$children = $nav_item->children();
			$sub_menu = '<ul class="nav-list main-nav-sub-list">';

			foreach ( $children as $child ) {
				$sub_menu .= sprintf(
					'<li><a href="%s">%s</a></li>',
					$child->permalink(),
					ucwords( str_replace( [ '-', '_' ], ' ', $child->slug() ) )
				);
			}
			$sub_menu .= '</ul>';

			$nav_entry = sprintf(
				'<li class="has-children"><a href="%s">%s %s</a>%s</li>',
				$nav_item->permalink(),
				ucwords( str_replace( [ '-', '_' ], ' ', $nav_item->slug() ) ),
				icon( 'angle-down', true ),
				$sub_menu
			);

		// Page without children.
		} elseif ( ! $nav_item->parent() ) {
			$nav_entry = sprintf(
				'<li class="no-children"><a href="%s">%s</a></li>',
				$nav_item->permalink(),
				ucwords( str_replace( [ '-', '_' ], ' ', $nav_item->slug() ) )
			);
		}
		echo $nav_entry;
		endforeach;

		// Add loop after pages link if home is static content.
		printf(
			'<li class="no-children"><a href="%s">%s</a></li>',
			loop_url(),
			nav_loop_label()
		);

		// Add a home link if true in theme plugin.
		printf(
			'<li class="no-children"><a href="%s">%s</a></li>',
			site()->url(),
			$L->get( 'home-link-label' )
		);

		// Add a search toggle button if the Search Forms plugin is installed.
		if ( getPlugin( 'Search_Forms' ) ) {
			printf(
				'<li class="no-children hide-if-no-js"><a href="#search-bar"><button data-search-toggle-open  aria-controls="search-bar" aria-expanded="false">%s<span class="screen-reader-text">%s</span></button></a></li>',
				icon( 'search' ),
				$L->get( 'search-link-label' )
			);
		} ?>
	</ul>
</nav>
