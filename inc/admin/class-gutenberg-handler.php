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
use WP_Block_Editor_Context;

/**
 * Gutenberg Handler
 */
class Gutenberg_Handler {
	/**
	 * Check if the current user is an administrator.
	 *
	 * @return bool
	 */
	private function is_admin(): bool {
		return current_user_can( 'can_unlock_blocks' );
	}

	/**
	 * Register the blocks.
	 */
	public function init_block_theme() {
		$blocks_path = get_template_directory() . '/dist';
		if ( file_exists( $blocks_path . '/blocks-manifest.php' ) ) {
			wp_register_block_types_from_metadata_collection( $blocks_path . '/js/blocks', $blocks_path . '/blocks-manifest.php' );
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

	/**
	 * Callback to alter the Gutenberg UI.
	 *
	 * @param array $settings Default editor settings.
	 */
	public function alter_gutenberg_ui( $settings, ) {
		if ( ! $this->is_admin() ) {
			$settings['canLockBlocks']      = false;
			$settings['codeEditingEnabled'] = false;
		}

		return $settings;
	}

	/**
	 * Filters the list of allowed block types in the block editor.
	 *
	 * This function restricts the available block types to Heading, List, Image, and Paragraph only.
	 *
	 * @param array|bool               $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
	 * @param ?WP_Block_Editor_Context $context               The block editor context
	 *
	 * @return array|bool The array of allowed block types or boolean to enable/disable all.
	 */
	public function restrict_block_types( array|bool $allowed_block_types, ?WP_Block_Editor_Context $context ): array|bool {
		if ( ! $context ) {
			return $allowed_block_types;
		}
		// Get all registered blocks if $allowed_block_types is not already set.
		if ( ! is_array( $allowed_block_types ) || empty( $allowed_block_types ) ) {
			$registered_blocks   = \WP_Block_Type_Registry::get_instance()->get_all_registered();
			$allowed_block_types = array_keys( $registered_blocks );
		}
		$disallowed_blocks = array(
			'core/avatar',
			'core/comments',
			'core/comment-author-name',
			'core/comment-content',
			'core/comment-date',
			'core/comment-edit-link',
			'core/comment-reply-link',
			'core/comment-template',
			'core/comment-pagination-previous',
			'core/comments-author-avatar',
			'core/comments-pagination',
			'core/comments-pagination-next',
			'core/comments-pagination-numbers',
			'core/comments-title',
			'core/latest-comments',
			'core/loginout',
			'core/math',
			'core/page-list-item',
			'core/page-list',
			'core/post-comment',
			'core/post-comments',
			'core/post-comments-count',
			'core/post-comments-form',
			'core/post-comments-link',
			'core/preformatted',
			'core/rss',
			'core/tag-cloud',
			'core/term-description',
			'core/video',
			'core/verse',
			'core/home-link',
			'core/media-text',
			'core/more',
			'core/archives',
			'core/categories',
			'core/calendar',
			'core/post-terms',
			'core/site-tagline',
			'core/code',
			'core/embed',
			'core/search',
			'core/latest-posts',
		);
		if ( ! $this->is_admin() ) {
			array_push(
				$disallowed_blocks,
				'core/missing',
				'core/post-author',
				'core/post-author-biography',
				'core/post-author-name',
			);
		}
		$filtered = array_filter(
			$allowed_block_types,
			function ( $block ) use ( $disallowed_blocks ) {
				return ! in_array( $block, $disallowed_blocks, true );
			}
		);
		return array_values( $filtered );
	}

	/**
	 * Disallows the Block Editor (and editor altogether) for certain post types
	 *
	 * @return bool
	 */
	public function handle_page_templates(): bool {
		if ( ! is_admin() ) {
			return true;
		}
		global $post;
		if ( ! $post ) {
			return true;
		}
		$current_template     = get_page_template_slug( $post );
		$homepage_id          = (int) get_option( 'page_on_front' );
		$is_homepage          = ( $homepage_id && $homepage_id === $post->ID );
		$disallowed_templates = array();
		if ( in_array( $current_template, $disallowed_templates, true ) ) {
			// could also use $is_homepage check here if needed
			return false;
		}
		return true;
	}
}
