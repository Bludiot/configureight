<?php
/**
 * User profile template
 *
 * The User Profiles plugin must be installed.
 *
 * @package    Configure 8
 * @subpackage Templates
 * @category   Content
 * @since      1.0.0
 */

// Import namespaced functions.
use function CFE_Func\{
	lang,
	plugin,
	plugins_hook,
	loop_data,
	is_main_loop,
	is_cat,
	is_tag,
	profiles,
	user_slug,
	get_word_count
};
use function CFE_Tags\{
	loop_page_header,
	loop_style,
	loop_type,
	icon,
	sticky_icon,
	article_type,
	page_description,
	has_tags,
	get_author,
	get_loop_pagination
};

// Stop if the User Profiles plugin is not installed.
if ( ! profiles() ) {
	printf(
		'<h2>%s</h2>',
		lang()->get( 'The User Profiles plugin must be installed.' )
	);
	return;
}

// If no posts.
if ( empty( $content) ) {
	include( THEME_DIR . 'views/content/no-posts.php' );
	return;
}

$user     = UPRO_Func\user( user_slug() );
$username = $user->username();
$cover    = profiles()->cover_src( $username );
$website  = profiles()->getValue( 'website_' . $username );
$email    = $user->email();
$bio      = profiles()->getValue( 'bio_' . $username );

?>
<article id="<?php echo 'profile-' . $username; ?>" class="site-article" role="article" itemscope="itemscope" itemtype="author" data-profile>

	<div class="page-content" itemprop="articleBody" data-page-content>
		<?php
		echo plugins_hook( 'user_profile_before' );
		echo plugins_hook( 'user_profile_content' );
		echo plugins_hook( 'user_profile_after' );
		?>
	</div>
</article>
