<?php
/**
 * Register theme classes
 *
 * The autoloaders register theme classes for later use.
 *
 * @see Demo function at end of this file.
 *
 * @package    BS Bludit
 * @subpackage Includes
 * @category   Classes
 * @since      1.0.0
 */

namespace BSB\Classes\Autoload;

// Restrict direct access.
if ( ! defined( 'BLUDIT' ) ) {
	die( $L->get( 'direct-access' ) );
}

/**
 * Load classes
 *
 * Runs the autoload functions.
 *
 * @since  1.0.0
 * @return void
 */
function classes() {
	core();
	users();
	media();
	front();
	admin();
}

/**
 * Namespace & class name
 *
 * Class namespaces must contain `Classes` and a
 * category following the theme namespace.
 * Example: `BSB\Classes\Category\My_Class`
 *
 * @since  1.0.0
 * @param  string $cat
 * @param  string $class
 * @return string Returns the namespace with category and class name.
 *                Example: BSB\Classes\Admin\My_Class.
 */
function ns( $cat, $class ) {
	return 'BSB\Classes\\' . $cat . '\\' . $class;
};

/**
 * File path
 *
 * Works for subdirectories of the `includes/classes` directory.
 * Files require the `class-` prefix.
 *
 * @since  1.0.0
 * @param  string $dir
 * @param  string $file
 * @return string Returns the file path in classes subdirectory.
 */
function f( $dir, $file ) {
	return THEME_DIR . 'includes/classes/' . $dir .'/class-' . $file;
};

/**
 * Core classes
 *
 * @since  1.0.0
 * @return void
 */
function core() {

	$classes = [];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Users classes
 *
 * @since  1.0.0
 * @return void
 */
function users() {

	$classes = [];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Media classes
 *
 * @since  1.0.0
 * @return void
 */
function media() {

	$classes = [];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Frontend classes
 *
 * @since  1.0.0
 * @return void
 */
function front() {

	$classes = [
		ns( 'Front', 'Search_Form' )   => f( 'frontend', 'search-form.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Backend classes
 *
 * @since  1.0.0
 * @return void
 */
function admin() {

	$classes = [];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

// Stop here for demo.
return;

/**
 * Autoload demo
 *
 * The namespace and file path function are not
 * required in the array of classes to load.
 *
 * In this demo function, various combinations
 * are used in the array.
 *
 * @since  1.0.0
 * @return void
 */
function demo() {

	/**
	 * All key => value examples would work together in
	 * the array if these files actually existed.
	 */
	$classes = [

		// Both functions used.
		ns( 'Demo', 'Demo_One' ) => f( 'demo', 'demo-one.php' ),

		// Full namespace & class name, path function.
		'BSB\Classes\Demo\Demo_Two' => f( 'demo', 'demo-two.php' ),

		// Namespace function, full path.
		ns( 'Demo', 'Demo_Three' ) => THEME_DIR . 'includes/classes/demo/class-demo-three.php',

		// Fully custom.
		'BSB\Custom\Namespace\Demo_Four' => THEME_DIR . 'includes/custom/directory/class-demo-four.php'
	];

	// Autoload when in use.
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}
