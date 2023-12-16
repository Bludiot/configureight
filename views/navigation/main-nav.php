<?php
/**
 * Main navigation template
 *
 * This is a starter plugin, a boilerplate,
 * so no mobile menu toggle is provided as
 * these can be styled and structured in
 * many ways. This is simply navigation
 * bones upon which to build.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Navigation
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	site,
	plugin
};
use function CFE_Tags\{
	icon,
	menu_toggle,
	social_nav
};

?>
<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="https://schema.org/SiteNavigationElement" data-site-navigation>
	<ul class="nav-list main-nav-list">
		<?php

		$max_items = 0;
		if ( plugin() ) {
			if ( plugin()->max_nav_items() ) {
				$max_items = plugin()->max_nav_items();
			}
		}

		$nav_items = $staticContent;
		if ( $max_items > 0 ) {
			$nav_items = array_slice( $staticContent, 1, $max_items );
		}

		foreach ( $nav_items as $nav_item ) :

		$nav_entry = '';
		$home_uri  = $site->getField( 'homepage' );
		$loop_uri  = $site->getField( 'uriBlog' );

		/**
		 * Do not list static front page or
		 * loop not on the home page because
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

		// Add loop link if home is static content.
		if ( ! empty( $home_uri ) && ! empty( $loop_uri ) ) {

			// If true in theme plugin.
			if ( plugin() ) {
				if ( plugin()->main_nav_loop() ) {
					printf(
						'<li class="no-children"><a href="%s">%s</a></li>',
						$site->url() . str_replace( '/', '', $loop_uri ) . '/',
						ucwords( str_replace( [ '/', '-', '_' ], ' ', $loop_uri ) )
					);
				}
			} else {
				printf(
					'<li class="no-children"><a href="%s">%s</a></li>',
					$site->url() . str_replace( '/', '', $loop_uri ) . '/',
					ucwords( str_replace( [ '/', '-', '_' ], ' ', $loop_uri ) )
				);
			}
		}

		// Add a home link if true in theme plugin.
		if ( plugin() ) {
			if ( plugin()->main_nav_home() ) {
				printf(
					'<li class="no-children"><a href="%s">%s</a></li>',
					$site->url(),
					$L->get( 'home-link-label' )
				);
			}
		} else {
			printf(
				'<li class="no-children"><a href="%s">%s</a></li>',
				$site->url(),
				$L->get( 'home-link-label' )
			);
		}

		// Add a search toggle button.
		if ( plugin() ) {
			if ( plugin()->header_search() && getPlugin( 'Search_Forms' ) ) {
				printf(
					'<li class="no-children hide-if-no-js"><button data-search-toggle-open  aria-controls="search-bar" aria-expanded="false">%s<span class="screen-reader-text">%s</span></button></li>',
					icon( 'search' ),
					$L->get( 'search-link-label' )
				);
			}
		}

		// Add social links.
		if ( plugin() ) : if ( plugin()->header_social() ) :

			$links = $helper :: socialNetworks();
			if ( $links ) :
			foreach ( $links as $link => $label ) :

				// Get icon SVG file.
				$icon = '';
				$file = THEME_DIR . 'assets/images/svg-icons/' . $link . '.svg';
				if ( file_exists( $file ) ) {
					$icon = file_get_contents( $file );
				} ?>
				<li>
					<a href="<?php echo site()->{$link}(); ?>" target="_blank" rel="noreferrer noopener" title="<?php echo $label; ?>">
						<span class="theme-icon social-icon"><?php echo $icon; ?></span>
						<span class="screen-reader-text social-label"><?php echo $label; ?></span>
					</a>
				</li>
				<?php endforeach;
		endif; endif; endif;

		?>
	</ul>
</nav>
