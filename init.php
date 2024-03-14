<?php
/**
 * Initialize theme
 *
 * @package    Configure 8
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

namespace CFE_Theme;

// Alias namespaces.
use CFE\Classes\{
	Autoload as Autoload
};

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( $L->get( 'You are not allowed direct access to this file.' ) );
}

/**
 * Constant: Minimum PHP version
 *
 * @since 1.0.0
 */
define( 'CFE_MIN_PHP_VERSION', '7.4' );

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

	if ( version_compare( phpversion(), CFE_MIN_PHP_VERSION, '<' ) ) {
		return false;
	}
	return true;
}

// Die if PHP minimum is not met.
if ( ! min_php_version() ) {
	die( text_replace( 'die-php-version', CFE_MIN_PHP_VERSION ) );
}

// Autoload classes.
require_once( THEME_DIR . 'includes/classes/autoload.php' );
Autoload\classes();

// Access Login class from the front end.
$login = new \Login();

// Required theme functions.
require( THEME_DIR . 'includes/helpers.php' );
require( THEME_DIR . 'includes/meta-data.php' );
require( THEME_DIR . 'includes/template-tags.php' );
