<?php
/**
 * Theme Functions
 *
 * @package KJRoelke
 */

use KJRoelke\Theme_Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$autoload_path = get_stylesheet_directory() . '/vendor/autoload.php';
if ( file_exists( $autoload_path ) ) {
	include $autoload_path;
} elseif ( is_admin() ) {
	wp_die(
		'Autoload file not found. Please run composer install inside the theme directory.',
		'KJ Roelke WP Theme Error',
		array( 'response' => 500 )
	);
}

/**
 * Get the theme init class
*/
$theme = new Theme_Init();
add_action( 'after_setup_theme', array( $theme, 'setup_theme' ) );
