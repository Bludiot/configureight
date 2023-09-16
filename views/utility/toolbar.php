<?php
/**
 * User toolbar
 *
 * Not a complete set of links as this
 * is a starter theme and you can add
 * links as you like, or disable the
 * user toolbar.
 *
 * @package    BS Bludit
 * @subpackage Templates
 * @category   Utility
 * @since      1.0.0
 */

// Edit link.
$edit_link = '';
if ( 'page' == $url->whereAmI() ) {
	$edit_link = sprintf(
		'<a href="%s">%s</a>',
		DOMAIN_ADMIN . 'edit-content/' . $page->slug(),
		$L->get( 'edit-link' )
	);
}

 // Get a username or fa;;back.
$user = new User( Session :: get( 'username' ) );
$name = $L->get( 'profile-link-default' );

if ( $user->nickname() ) {
	$name = $user->nickname();
} elseif ( $user->firstName() ) {
	$name = $user->firstName();
}

?>
<section class="user-toolbar" data-user-toolbar>
	<div class="user-action">
		<a href="<?php echo DOMAIN_ADMIN;?>" target="_blank"><?php $L->p( 'dashboard-link' ); ?></a>
		<?php echo $edit_link; ?>
		<a href="<?php echo DOMAIN_ADMIN . 'new-content';?>"><?php $L->p( 'new-content-link' ); ?></a>
	</div>
	<div class="user-info">
		<a id="profile-link" href="<?php echo DOMAIN_ADMIN;?>edit-user/<?php echo Session :: get( 'username' ); ?>">
			<img src="<?php echo $user->profilePicture(); ?>" width="24"> <?php echo $name; ?>
		</a>
	</div>
</section>
