<?php
/**
 * Class: Plugin Handler
 *
 * @package KJRoelke
 */

namespace KJRoelke\Plugins;

/**
 * Handles plugin functionality
 */
class Plugins_Handler {
	/**
	 * The current environment type (e.g., 'production', 'staging', 'development', 'local').
	 *
	 * @var string $environment
	 */
	private string $environment;

	/**
	 * Constructor to initialize the environment type.
	 */
	public function __construct() {
		$this->environment = wp_get_environment_type();
	}
	/**
	 * Disable certain plugins based on the environment type.
	 */
	public function disable_plugins_per_environment() {
		if ( 'production' === $this->environment || ! is_admin() ) {
			return;
		}

		$plugins_to_disable = array(
			'google-site-kit/google-site-kit.php' => array( 'local', 'development', 'staging' ),
			'autoptimize/autoptimize.php'         => array( 'local', 'development' ),
			'wordpress-seo/wp-seo.php'            => array( 'local', 'development' ),
		);

		foreach ( $plugins_to_disable as $plugin => $environments ) {
			if ( in_array( $this->environment, $environments, true ) ) {
				if ( is_plugin_active( $plugin ) ) {
					deactivate_plugins( $plugin );
				}
			}
		}
	}

	/**
	 * Handle automatic plugin updates based on environment.
	 *
	 * @param ?bool $update Whether to update the plugin.
	 * @return ?bool
	 */
	public function handle_auto_update_plugin( ?bool $update ): ?bool {
		if ( 'production' === $this->environment ) {
			return $update;
		}
		return true;
	}

	/**
	 * Handle ACF functionality, including saving and loading JSON files from the theme directory.
	 */
	public function handle_acf() {
		if ( ! defined( 'ACF_PRO' ) || ! defined( 'ACF_VERSION' ) ) {
			return;
		}
		$acf = new ACF_Handler();
		$acf->init_save_filters();
		add_filter( 'acf/settings/enable_datastore', '__return_true' );
		add_filter( 'acf/settings/load_json', array( $acf, 'load_json_paths' ) );
	}
}
