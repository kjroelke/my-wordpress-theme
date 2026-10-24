<?php
/**
 * Initializes the Theme
 *
 * @package KJRoelke
 */

namespace KJRoelke;

use KJRoelke\Utils\Asset_Loader;
use KJRoelke\Utils\Enqueue_Type;

/** Builds the Theme */
class Theme_Init {
	/**
	 * Bootstraps the theme in the `after_setup_theme` hook
	 */
	public function setup_theme() {
		$this->load_required_files();
		$this->disable_discussion();
		$this->allow_svg();
		$this->handle_plugins();
		$this->kjr_theme_support();
		$this->handle_gutenberg();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_global_assets' ) );
		add_action( 'init', array( $this, 'alter_post_types' ) );
		add_filter( 'wp_speculation_rules_configuration', array( $this, 'handle_speculative_loading' ) );
	}


	/** Load required files. */
	private function load_required_files() {
		$base_path = get_template_directory() . '/inc';
		/** Loads the Theme Functions File (to keep the actual functions.php file clean) */
		require_once $base_path . '/theme/theme-functions.php';
	}

	/** Remove comments, pings and trackbacks support from posts types. */
	private function disable_discussion() {
		// Close comments on the front-end
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );

		// Hide existing comments.
		add_filter( 'comments_array', '__return_empty_array', 10, 2 );

		// Remove comments page in menu.
		add_action(
			'admin_menu',
			function () {
				remove_menu_page( 'edit-comments.php' );
			}
		);

		// Remove comments links from admin bar.
		add_action(
			'init',
			function () {
				if ( is_admin_bar_showing() ) {
					remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
				}
			}
		);
	}

	/**
	 * Allow SVG uploads
	 */
	private function allow_svg() {
		$svg = new Admin\Allow_SVG();
		add_filter( 'upload_mimes', array( $svg, 'cc_mime_types' ) );
		add_action( 'admin_head', array( $svg, 'fix_svg' ) );
	}

	/**
	 * Handle Plugins
	 */
	private function handle_plugins() {
		$handler = new Plugins\Plugins_Handler();
		$handler->handle_acf();
		add_action( 'init', array( $handler, 'disable_plugins_per_environment' ) );
		add_filter( 'auto_update_plugin', array( $handler, 'handle_auto_update_plugin' ) );
	}

	/**
	 * Handle the block editor
	 */
	private function handle_gutenberg() {
		$gutenberg_handler = new Admin\Gutenberg_Handler();
		$gutenberg_handler->kjr_block_theme_support();
		add_action( 'init', array( $gutenberg_handler, 'init_block_theme' ) );
		add_action( 'enqueue_block_editor_assets', array( $gutenberg_handler, 'enqueue_block_editor_assets' ) );
		add_filter( 'should_load_remote_block_patterns', '__return_false' );
		$query_loop_post_registry = new Theme\Query_Loop_Post_Registry();
		add_filter( 'query_loop_block_query_vars', array( $query_loop_post_registry, 'filter_query_vars' ), 10, 3 );
		add_action( 'loop_end', array( $query_loop_post_registry, 'register_posts' ) );
	}

	/**
	 * Adds scripts with the appropriate dependencies
	 */
	public function enqueue_frontend_assets() {

		// style.css
		wp_enqueue_style(
			'main',
			get_stylesheet_uri(),
			array( 'global' ),
			wp_get_theme()->get( 'Version' )
		);

		$this->remove_wordpress_styles(
			array(
				'classic-theme-styles',
				'dashicons',
			)
		);
	}

	/** Enqueues Assets needed by both the Block Editor and the Front End */
	public function enqueue_global_assets() {
		new Asset_Loader(
			'global',
			Enqueue_Type::both,
			null,
			array(
				'scripts' => array(),
				'styles'  => array(),
			)
		);
		// enqueue gutenberg-specific css
		if ( is_admin() ) {
			new Asset_Loader(
				'editor',
				Enqueue_Type::style,
				'admin',
				array()
			);

		}
	}

	/**
	 * Provide an array of handles to dequeue.
	 *
	 * @param array $handles the script/style handles to dequeue.
	 */
	private function remove_wordpress_styles( array $handles ) {
		foreach ( $handles as $handle ) {
			wp_dequeue_style( $handle );
		}
	}

	/** Registers Theme Supports */
	public function kjr_theme_support() {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
	}

	/** Remove post type support from posts types. */
	public function alter_post_types() {
		$post_types = array(
			'post',
			'page',
		);
		foreach ( $post_types as $post_type ) {
			$this->disable_post_type_support( $post_type );
		}
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Disable post-type-supports from posts
	 *
	 * @param string $post_type the post type to remove supports from.
	 */
	private function disable_post_type_support( string $post_type ) {
		$supports = array(
			'comments',
			'trackbacks',
		);
		foreach ( $supports as $support ) {
			if ( post_type_supports( $post_type, $support ) ) {
				remove_post_type_support( $post_type, $support );
			}
		}
	}

	/**
	 * Handle speculative loading
	 *
	 * @since WP 6.8.0
	 * @link https://make.wordpress.org/core/2025/03/06/speculative-loading-in-6-8/
	 *
	 * @param ?array $config the configuration array. Null if user is logged-in.
	 * @return ?array The new config file, or null
	 */
	public function handle_speculative_loading( ?array $config ): ?array {
		if ( is_array( $config ) ) {
			$config['mode']      = 'auto';
			$config['eagerness'] = 'moderate';
		}
		return $config;
	}

	/**
	 * Add resource hints
	 *
	 * @param array                                              $hints         The array of resource hints.
	 * @param 'dns-prefetch'|'preconnect'|'prefetch'|'prerender' $relation_type The relation type the hints are for.
	 * @return array The modified array of resource hints.
	 */
	public function add_resource_hints( array $hints, string $relation_type ) {
		if ( 'preconnect' === $relation_type ) {
			return $hints;
		}
		return $hints;
	}
}
