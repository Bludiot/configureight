<?php
/**
 * Initialize theme
 *
 * @package    BS Bludit
 * @subpackage Functions
 * @since      1.0.0
 */

namespace BS_Init;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed to access this file.' );
}

function user_logged_in() {

	$login = new \Login();

	if ( $login->isLogged() ) {
		return true;
	}
	return false;
}
