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
	lang,
	user_logged_in,
	user_role,
	loop_url
};
use function CFE_Tags\{
	icon,
	menu_toggle,
	nav_loop_label,
	social_nav
};

?>
<div id="mobile-nav" aria-expanded="false">

	<div id="mobile-nav-top">
		<?php
		if ( plugin()->header_search() && getPlugin( 'Search_Forms' ) ) : ?>
		<div id="mobile-search-bar" class="hide-if-no-js" aria-expanded="false">
			<?php echo SearchForms\form( [ 'label' => false ] ); ?>
		</div>
		<?php endif; ?>
		<div id="menu-close">
			<button class="button" data-menu-toggle-close><?php echo icon( 'close', false ); ?><span class="screen-reader-text"><?php lang()->p( 'Close' ); ?></span></button>
		</div>
	</div>

	<nav id="mobile-navigation" class="site-navigation mobile-navigation" role="directory" itemscope itemtype="https://schema.org/SiteNavigationElement" data-site-navigation>
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
			if ( 'before' == plugin()->main_nav_loop() ) {
				printf(
					'<li class="no-children"><a href="%s">%s</a></li>',
					loop_url(),
					nav_loop_label()
				);
			}

			// Pages selected in the plugin options.
			$nav_pages = plugin()->main_nav_pages();
			if ( $nav_pages[0] ) :
				foreach ( $nav_pages as $nav_page ) :

				// The `home` array key is not a page key/object.
				if ( 'home' === $nav_page ) {
					continue;
				}

				$nav_item  = buildPage( $nav_page );
				$nav_entry = '';

				if ( 'title' == plugin()->main_nav_labels() ) {
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
					$sub_menu = '<ul class="nav-list main-nav-sub-list">';

					foreach ( $children as $child ) {

						if ( 'title' == plugin()->main_nav_labels() ) {
							$child_label = $child->title();
						} else {
							$child_label = ucwords(
								str_replace( [ '-', '_' ], ' ', $child->slug() )
							);
						}
						$sub_menu .= sprintf(
							'<li><a href="%s">%s</a></li>',
							$child->permalink(),
							$child_label
						);
					}
					$sub_menu .= '</ul>';

					$nav_entry = sprintf(
						'<li class="has-children"><a href="%s">%s</a>%s</li>',
						$nav_item->permalink(),
						$label,
						$sub_menu
					);

				} elseif ( $nav_item->isChild() && 'primary' == plugin()->main_nav_children() ) {

					$nav_entry = sprintf(
						'<li class="no-children"><a href="%s">%s</a></li>',
						$nav_item->permalink(),
						$label
					);

				// Page without children.
				} elseif ( ! $nav_item->parent() ) {
					$nav_entry = sprintf(
						'<li class="no-children"><a href="%s">%s</a></li>',
						$nav_item->permalink(),
						$label
					);
				}
				echo $nav_entry;
				endforeach;
			endif;

			// Add loop after pages link if home is static content.
			// If `after` in theme plugin.
			if ( 'after' == plugin()->main_nav_loop() ) {
				printf(
					'<li class="no-children"><a href="%s">%s</a></li>',
					loop_url(),
					nav_loop_label()
				);
			}

			// Add a home link if true in theme plugin.
			if ( in_array( 'home', plugin()->main_nav_pages() ) ) {
				printf(
					'<li class="no-children"><a href="%s">%s</a></li>',
					site()->url(),
					$L->get( 'home-link-label' )
				);
			}

			// Add social links.
			if ( plugin()->header_social() ) :

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
			endif; endif;

			?>
		</ul>
	</nav>
</div>
