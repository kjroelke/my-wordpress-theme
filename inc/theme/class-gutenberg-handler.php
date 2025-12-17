<?php
/**
 * Gutenberg Handler
 *
 * Handle all Block-Theme related functionality (since we're currently operating in hybrid theme mode)
 *
 * @package KJRoelke
 */

namespace KJRoelke\Theme;

/**
 * Gutenberg Handler
 */
class Gutenberg_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_assets' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'init', array( $this, 'register_theme_blocks' ) );
	}

	/**
	 * Enqueue the block editor assets that control the layout of the Block Editor.
	 */
	public function enqueue_block_assets() {
		$files = array(
			'editDefaultBlocks' => 'script',
			'editor'            => 'style',
		);
		foreach ( $files as $handle => $type ) {
			$assets = require_once get_stylesheet_directory() . "/build/admin/{$handle}.asset.php";
			if ( 'style' === $type || 'both' === $type ) {
				wp_enqueue_style(
					$handle,
					get_stylesheet_directory_uri() . "/build/admin/{$handle}.css",
					$assets['dependencies'],
					$assets['version']
				);
			}
			if ( 'script' === $type || 'both' === $type ) {
				wp_enqueue_script(
					$handle,
					get_stylesheet_directory_uri() . "/build/admin/{$handle}.js",
					$assets['dependencies'],
					$assets['version'],
					array( 'strategy' => 'defer' )
				);
			}
		}
	}

	/**
	 * Add theme supports for Gutenberg features
	 */
	public function theme_supports() {
		$opt_in_features = array(
			'responsive-embeds',
		);
		foreach ( $opt_in_features as $feature ) {
			add_theme_support( $feature );
		}

		$opt_out_features = array(
			'core-block-patterns',
		);
		foreach ( $opt_out_features as $feature ) {
			remove_theme_support( $feature );
		}
	}

	/**
	 * Register any theme-specific blocks
	 */
	public function register_theme_blocks() {
		// Load blocks
		$blocks_path = get_template_directory() . '/build';
		if ( file_exists( $blocks_path . '/blocks-manifest.php' ) ) {
			wp_register_block_types_from_metadata_collection( $blocks_path . '/js/blocks', $blocks_path . '/blocks-manifest.php' );
		}
	}
}