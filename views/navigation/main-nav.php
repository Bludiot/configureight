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
<nav id="site-navigation" class="site-navigation" role="directory" itemscope itemtype="https://schema.org/SiteNavigationElement" data-site-navigation>
	<ul class="nav-list main-nav-list">
		<?php foreach ( $staticContent as $nav_item ) :

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
		?>
	</ul>
</nav>
