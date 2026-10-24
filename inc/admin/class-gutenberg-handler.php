<?php
/**
 * Gutenberg Handler
 * Handles the Controls and Settings for the Block Editor
 *
 * @package KJRoelke
 */

namespace KJRoelke\Admin;

use KJRoelke\Utils\Asset_Loader;
use KJRoelke\Utils\Enqueue_Type;

/**
 * Gutenberg Handler
 */
class Gutenberg_Handler {
	/**
	 * Register the blocks.
	 */
	public function init_block_theme() {
		$blocks_path = get_template_directory() . '/dist';
		if ( file_exists( $blocks_path . '/blocks-manifest.php' ) ) {
			wp_register_block_types_from_metadata_collection( $blocks_path . '/js/blocks', $blocks_path . '/blocks-manifest.php' );
		}
		$this->register_theme_pattern_categories();
	}
	/**
	 * Register the theme pattern categories.
	 */
	public function register_theme_pattern_categories() {
		$name_prefix = 'kjr-';
		$categories  = array(
			'layouts' => array(
				'label'       => 'Layouts',
				'description' => 'Patterns for various layout structures.',
			),
		);
		foreach ( $categories as $name => $properties ) {
			register_block_pattern_category( $name_prefix . $name, $properties );
		}
	}

	/**
	 * Enqueue the block editor assets that control the layout of the Block Editor.
	 */
	public function enqueue_block_editor_assets() {
		new Asset_Loader( 'editDefaultBlocks', Enqueue_Type::script, 'admin', array() );
	}

	/**
	 * Init theme supports specific to the block editor.
	 */
	public function kjr_block_theme_support() {
		$opt_in_features = array(
			'responsive-embeds',
			'editor-styles',
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
}
