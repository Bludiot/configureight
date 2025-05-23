<?php
/**
 * Main navigation template
 *
 * This file is used if the theme's companion
 * plugin is installed.
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
	url,
	lang,
	user_logged_in,
	user_role,
	is_home,
	is_front_page,
	static_loop_page,
	is_main_loop,
	loop_url
};
use function CFE_Tags\{
	icon,
	menu_toggle,
	nav_loop_label,
	social_nav
};

?>
<?php echo menu_toggle(); ?>

<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="https://schema.org/SiteNavigationElement" data-site-navigation>
	<ul class="nav-list main-nav-list">
		<?php

		// Link to admin menu tab if no pages selected.
		if (
			user_logged_in() &&
			'admin' == user_role() &&
			'none'  == plugin()->main_nav_loop() &&
			! plugin()->main_nav_pages()
		) {
			$nav_tab = DOMAIN_ADMIN . 'configure-plugin/configureight#nav';
			printf(
				'<li class="no-children"><a href="%s">%s</a></li>',
				$nav_tab,
				lang()->get( 'Select Menu Pages' )
			);
		}

		$home_uri = site()->getField( 'homepage' );
		$loop_uri = site()->getField( 'uriBlog' );

		// Add loop before pages link if home is static content.
		// If `before` in theme plugin.
		if ( 'before' == plugin()->main_nav_loop() && static_loop_page() ) {

			// Item class, look if current.
			$item_class = 'no-children';
			if ( is_main_loop() ) {
				$item_class = 'no-children current-menu-item';
			}

			printf(
				'<li class="%s"><a href="%s">%s</a></li>',
				$item_class,
				loop_url(),
				nav_loop_label()
			);
		}

		// Pages selected in the plugin options.
		$nav_pages = plugin()->main_nav_pages();
		foreach ( $nav_pages as $nav_page ) :

			if ( 'home' === $nav_page ) {
				continue;
			}
			if ( 'foobar' == $nav_page ) {
				continue;
			}

			$nav_item  = buildPage( $nav_page );
			$nav_entry = '';

			// Stop if page not in pages database.
			if ( ! $nav_item ) {
				continue;
			}

			if ( $nav_item->custom( 'menu_label' ) ) {
				$label =  ucwords( $nav_item->custom( 'menu_label' ) );
			} elseif ( 'title' == plugin()->main_nav_labels() ) {
				$label = $nav_item->title();
			} else {
				$label = ucwords(
					str_replace( [ '-', '_' ], ' ', $nav_item->slug() )
				);
			}

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
			} elseif ( $nav_item->hasChildren() && 'secondary' == plugin()->main_nav_children() ) {

				$children = $nav_item->children();

				// Sort by position.
				usort( $children, function( $a, $b ) {
					return $a->position() <=> $b->position();
				} );

				$sub_menu = '<ul class="nav-list main-nav-sub-list">';

				foreach ( $children as $child ) {

					if ( $child->custom( 'menu_label' ) ) {
						$child_label =  ucwords( $child->custom( 'menu_label' ) );
					} elseif ( 'title' == plugin()->main_nav_labels() ) {
						$child_label = $child->title();
					} else {
						$child_label = ucwords(
							str_replace( [ '-', '_' ], ' ', $child->slug() )
						);
					}

					// Item class, look if current.
					$item_class = 'submenu-item';
					if ( str_contains( url()->slug(), $child->key() ) ) {
						$item_class = 'submenu-item current-menu-item';
					}
					$sub_menu .= sprintf(
						'<li class="%s"><a href="%s">%s</a></li>',
						$item_class,
						$child->permalink(),
						$child_label
					);
				}
				$sub_menu .= '</ul>';

				// Item class, look if current.
				$item_class = 'has-children';
				if ( url()->slug() == $nav_item->key() ) {
					$item_class = 'has-children current-menu-item';
				}

				$nav_entry = sprintf(
					'<li class="%s"><a href="%s">%s %s</a>%s</li>',
					$item_class,
					$nav_item->permalink(),
					$label,
					icon( 'angle-down', true ),
					$sub_menu
				);

			} elseif ( $nav_item->isChild() && 'primary' == plugin()->main_nav_children() ) {

				// Item class, look if current.
				$item_class = 'no-children';
				if ( url()->slug() == $nav_item->key() ) {
					$item_class = 'no-children current-menu-item';
				}

				$nav_entry = sprintf(
					'<li class="%s"><a href="%s">%s</a></li>',
					$item_class,
					$nav_item->permalink(),
					$label
				);

			// Page without children.
			} elseif ( ! $nav_item->parent() ) {

				// Item class, look if current.
				$item_class = 'no-children';
				if ( url()->slug() == $nav_item->key() ) {
					$item_class = 'no-children current-menu-item';
				}

				$nav_entry = sprintf(
					'<li class="%s"><a href="%s">%s</a></li>',
					$item_class,
					$nav_item->permalink(),
					$label
				);
			}
			echo $nav_entry;
		endforeach;

		// Add loop after pages link if home is static content.
		// If `after` in theme plugin.
		if ( 'after' == plugin()->main_nav_loop() && static_loop_page() ) {

			// Item class, look if current.
			$item_class = 'no-children';
			if ( is_main_loop() ) {
				$item_class = 'no-children current-menu-item';
			}

			printf(
				'<li class="%s"><a href="%s">%s</a></li>',
				$item_class,
				loop_url(),
				nav_loop_label()
			);
		}

		// Add a home link if true in theme plugin.
		if ( in_array( 'home', plugin()->main_nav_pages() ) ) {

			// Item class, look if current.
			$item_class = 'no-children';
			if ( is_home() || is_front_page() ) {
				$item_class = 'no-children current-menu-item';
			}

			printf(
				'<li class="%s"><a href="%s">%s</a></li>',
				$item_class,
				site()->url(),
				$L->get( 'home-link-label' )
			);
		}

		// Add a search toggle button.
		if ( plugin()->header_search() && getPlugin( 'Search_Forms' ) ) {
			printf(
				'<li class="no-children hide-if-no-js"><a href="#search-bar" class="top-search-open" data-search-toggle-open aria-controls="search-bar" aria-expanded="false" title="%s" data-tooltip>%s<span class="screen-reader-text">%s</span></a></li>',
				$L->get( 'Search This Site' ),
				icon( 'search', true, 'top-search-icon' ),
				$L->get( 'Search' )
			);
		}

		// Add social links.
		if ( plugin()->header_social() ) {
			social_nav( false, true );
		} ?>
	</ul>
</nav>
