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
	site,
	url,
	lang,
	page,
	page_type
};

// Edit link.
$edit_link = '';
if ( 'page' == url()->whereAmI() ) {

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
		'<a href="%s">%s</a>',
		DOMAIN_ADMIN . 'edit-content/' . $slug ,
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
	<div class="user-action">
		<a href="<?php echo DOMAIN_ADMIN;?>" target="_blank"><?php lang()->p( 'dashboard-link' ); ?></a>
		<?php echo $edit_link; ?>
		<a href="<?php echo DOMAIN_ADMIN . 'new-content';?>"><?php lang()->p( 'new-content-link' ); ?></a>
	</div>
	<div class="user-info">
		<a id="profile-link" href="<?php echo $profile; ?>">
			<img src="<?php echo $avatar; ?>" width="24"> <?php echo $name; ?>
		</a>
	</div>
</section>
