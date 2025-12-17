<?php
/**
 * Class: Theme Init
 *
 * Handle Theme Needs
 *
 * @package KJRoelke
 */

namespace KJRoelke\Theme;

/**
 * Class: Theme Init
 */
class Theme_Init extends Base {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'wp_speculation_rules_configuration', array( $this, 'handle_speculative_loading' ) );
		new Gutenberg_Handler();
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts(): void {
		$files = array(
			'global' => array(
				'js'  => 'global',
				'css' => 'global',
			),
		);
		foreach ( $files as $handle => $paths ) {
			$assets = require_once get_stylesheet_directory() . "/build/{$paths['js']}.asset.php";

			$deps = $assets['dependencies'];
			wp_enqueue_script(
				$handle,
				get_stylesheet_directory_uri() . "/build/{$paths['js']}.js",
				$deps,
				$assets['version'],
				array( 'strategy' => 'defer' )
			);
			wp_enqueue_style(
				$handle,
				get_stylesheet_directory_uri() . "/build/{$paths['css']}.css",
				$deps,
				$assets['version'],
			);
		}
	}

	/**
	 * Handle speculative loading
	 *
	 * @param ?array $config the configuration array. Null if user is logged-in.
	 * @return ?array The new config file, or null
	 */
	public function handle_speculative_loading( $config ) {
		if ( is_array( $config ) ) {
			$config['mode']      = 'auto';
			$config['eagerness'] = 'moderate';
		}
		return $config;
	}
}
