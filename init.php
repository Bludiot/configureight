<?php
/**
 * Initialize theme
 *
 * @package    BS Bludit
 * @subpackage Functions
 * @since      1.0.0
 */

/**
 * License & Warranty
 *
 * This product is free software. It can be redistributed and/or modified
 * ad libidum. There is no license distributed with this product.
 *
 * This product is distributed WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @see DISCLAIMER.md
 */

/**
 * Author's Note
 *
 * To all who may read this,
 *
 * I hope you find this code to be easily deciphered. I have
 * learned much by examining the code of well written & well
 * documented products so I have done my best to document this
 * code with comments where necessary, even where not necessary,
 * and by using logical, descriptive names for PHP classes &
 * methods, HTML IDs, CSS classes, etc.
 *
 * Beginners, note that the short array syntax ( `[]` rather than
 * `array()` ) is used. Use of the `array()` function is encouraged
 * by some to make the code more easily read by beginners. I argue
 * that beginners will inevitably encounter the short array syntax
 * so they may as well learn to recognize this early. If the code
 * is well documented then it will be clear when the brackets (`[]`)
 * represent an array. And someday you too will be writing many
 * arrays in your code and you will find the short syntax to be
 * a time saver. Let's not unnecessarily dumb-down code; y'all
 * are smart folk if you are reading this and you'll figure it out
 * like I did.
 *
 * Greg Sweet, Controlled Chaos Design, former mule packer, cook,
 * landscaper, & janitor who learned PHP by breaking stuff and by
 * reading code comments.
 */

namespace BSB_Theme;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( $L->get( 'direct-access' ) );
}

/**
 * Constant: Minimum PHP version
 *
 * @since 1.0.0
 */
define( 'BSB_MIN_PHP_VERSION', '7.4' );

/**
 * Minimum PHP version
 *
 * Checks the PHP version running on the current host
 * against the minimum version required by this theme.
 *
 * @since  1.0.0
 * @return boolean Returns false if the minimum is not met.
 */
function min_php_version() {

	if ( version_compare( phpversion(), BSB_MIN_PHP_VERSION, '<' ) ) {
		return false;
	}
	return true;
}

// Die if PHP minimum is not met.
if ( ! min_php_version() ) {
	die( text_replace( 'die-php-version', BSB_MIN_PHP_VERSION ) );
}

/**
 * Get configuration file
 *
 * Looks first in the Bludit root directory
 * for `theme-config.php`, then in this theme's
 * `includes` directory for `theme-config.php`.
 *
 * This is a starter theme and you may create
 * more than one theme from this boilerplate.
 * For this reason the ability to access a common
 * configuration file is provided to you.
 *
 * To add the configuration file to the root,
 * simply copy the `theme-config.php` file in
 * the `includes` directory and paste into the
 * root Bludit directory where you find the
 * content, kernel, plugins, themes directories.
 */
if ( file_exists( PATH_ROOT . 'theme-config.php' ) ) {
	require_once( PATH_ROOT . 'theme-config.php' );
} else {
	require_once( THEME_DIR . 'includes/theme-config.php' );
}

// Get theme functions.
require_once( THEME_DIR . 'includes/helpers.php' );
require_once( THEME_DIR . 'includes/template-tags.php' );
