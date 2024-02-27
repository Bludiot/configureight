<?php
/**
 * User toolbar
 *
 * Not a complete set of links as this
 * is a starter theme and you can add
 * links as you like, or disable the
 * user toolbar.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	plugin,
	site,
	url,
	lang,
	page,
	is_page,
	page_type
};
use function CFE_Tags\{
	icon
};

// Edit link.
$edit_link = '';
if ( is_page() ) {

	// Page slug.
	$slug = url()->slug();
	if ( site()->pageNotFound() ) {
		$site_not_found = site()->pageNotFound();

		// Error page if current URL is 404.
		if ( url()->notFound() ) {
			$error = buildPage( $site_not_found );
			$slug  = $error->slug();
		}
	}
	$edit_link = sprintf(
		'<li class="top-level-item"><a href="%s">%s %s</a></li>',
		DOMAIN_ADMIN . 'edit-content/' . $slug ,
		icon( 'pencil', true ),
		'page' == page_type() ? lang()->get( 'Edit Page' ) : lang()->get( 'Edit Post' )
	);
}

// Get a username or fallback.
$user = new User( Session :: get( 'username' ) );
$name = lang()->get( 'profile-link-default' );

if ( $user->nickname() ) {
	$name = $user->nickname();
} elseif ( $user->firstName() ) {
	$name = $user->firstName();
}

// User avatar & profile link.
if ( $user->profilePicture() ) {
	$avatar  = $user->profilePicture();
	$profile = sprintf(
		'%sedit-user/%s',
		DOMAIN_ADMIN,
		Session :: get( 'username' )
	);
} else {
	$avatar  = DOMAIN_THEME . 'assets/images/avatar-default.png';
	$profile = sprintf(
		'%sedit-user/%s#picture',
		DOMAIN_ADMIN,
		Session :: get( 'username' )
	);
}

?>
<section class="user-toolbar" data-user-toolbar>
	<nav class="user-toolbar-nav toolbar-user-action">
		<ul class="user-toolbar-nav-list">
			<li class="top-level-item has-submenu"><a href="<?php echo DOMAIN_ADMIN;?>"><?php echo icon( 'gauge', true ); lang()->p( 'Admin' ); echo icon( 'angle-down', true ); ?></a>
				<ul>
					<li>
						<a href="<?php echo DOMAIN_ADMIN;?>"><?php lang()->p( 'Dashboard' ); ?></a>
					</li>
					<?php if ( plugin() && checkRole( [ 'admin' ], false ) ) : ?>
					<li>
						<a href="<?php echo DOMAIN_ADMIN . 'configure-plugin/' . plugin()->className(); ?>"><?php lang()->p( 'Options' ); ?></a>
					</li>
					<?php endif; ?>
					<?php if ( checkRole( [ 'admin' ], false ) ) : ?>
					<li>
						<a href="<?php echo DOMAIN_ADMIN . 'settings'; ?>"><?php lang()->p( 'Settings' ); ?></a>
					</li>
					<?php endif; ?>
				</ul>
			</li>
			<li class="top-level-item has-submenu">
				<a href="<?php echo DOMAIN_ADMIN . 'content';?>"><?php echo icon( 'file', true ); ?><?php lang()->p( 'Content' ); ?><?php echo icon( 'angle-down', true ); ?></a>

				<ul>
					<li>
						<a href="<?php echo DOMAIN_ADMIN . 'new-content'; ?>"><?php echo ucwords( lang()->get( 'Compose' ) ); ?></a>
					</li>

					<li>
						<a href="<?php echo DOMAIN_ADMIN . 'content'; ?>"><?php lang()->p( 'Pages' ); ?></a>
					</li>

					<?php if ( checkRole( [ 'admin' ], false ) ) : ?>
					<li>
						<a href="<?php echo DOMAIN_ADMIN . 'categories'; ?>"><?php lang()->p( 'Categories' ); ?></a>
					</li>
					<?php endif; ?>
				</ul>
			</li>
			<?php echo $edit_link; ?>
		</ul>
	</nav>
	<nav class="user-info">
		<ul class="user-toolbar-nav-list">
			<li class="top-level-item has-submenu">
				<a id="profile-link" href="<?php echo $profile; ?>">
					<img class="avatar user-avatar user toolbar-avatar user-toolbar-avatar" src="<?php echo $avatar; ?>" width="24"> <span><?php echo $name; ?></span>
				</a>

				<ul class="user-actions-sublist">
					<li>
						<a href="<?php echo DOMAIN_ADMIN . 'edit-user/' . $name; ?>"><?php lang()->p( 'Your Profile' ); ?></a>
					</li>

					<li>
						<a id="toolbar-logout" href="<?php echo DOMAIN_ADMIN . 'logout'; ?>"><?php lang()->p( 'Log Out' ); ?></a>
					</li>
				</ul>
			</li>
		</ul>
	</nav>
</section>
