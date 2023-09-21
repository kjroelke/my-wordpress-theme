<?php
/**
 * The Theme Loader
 *
 * @package KJRoelke
 */

namespace KJR;

use KJR\Theme_Builder;
/**
 * Calls the WP Hooks
 */
class Theme_Loader extends Theme_Builder {
	public function __construct() {
		require_once WPMU_PLUGIN_DIR . '/advanced-custom-fields/acf.php';
		require_once get_template_directory() . '/php/cpts.php';
		require_once get_template_directory() . '/php/custom-fields.php';
		require_once get_template_directory() . '/php/theme-functions.php';
		add_action( 'after_setup_theme', array( $this, 'add_theme_supports' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		$this->enqueue_scripts();
		$this->enqueue_styles();
	}
}
