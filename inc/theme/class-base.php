<?php
/**
 * Base Theme Configs
 *
 * @package KJRoelke
 */

namespace KJRoelke\Theme;

/**
 * Class: Base
 */
class Base {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->disable_discussion();
		add_action( 'after_setup_theme', array( $this, 'configure_theme_support' ) );
		add_action( 'init', array( $this, 'alter_post_types' ) );
	}

	/** Remove comments, pings and trackbacks support from posts types. */
	public function disable_discussion() {
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

	/** Registers Theme Supports */
	public function configure_theme_support() {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );

		register_nav_menus(
			array(
				'primary_menu' => 'Primary Menu',
				'footer_menu'  => 'Footer Menu',
			)
		);
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
}
