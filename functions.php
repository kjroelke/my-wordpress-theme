<?php
/**
 * Theme Functions
 *
 * @package KJRoelke
 */

// Autoloader for the KJRoelke namespace. Maps namespace to /inc/ directory.
// Converts Pascal_Case or underscore class names to WPCS filenames,
// e.g. KJRoelke\Theme\Theme_Init -> inc/theme/class-theme-init.php
spl_autoload_register(
	function ( $class_name ) {
		$prefix = 'KJRoelke\\';

		// Only handle classes in our namespace
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}

		// Strip the namespace prefix and normalize
		$relative = substr( $class_name, strlen( $prefix ) );
		$relative = ltrim( $relative, '\\/' );

		// Break into namespace parts
		$parts      = explode( '\\', $relative );
		$class_name = array_pop( $parts );

		// Convert namespace segments to directory names (kebab-case)
		$dir_parts = array_map(
			function ( $p ) {
				$p = preg_replace( '/([a-z])([A-Z])/', '$1-$2', $p );
				$p = str_replace( '_', '-', $p );
				return strtolower( $p );
			},
			$parts
		);

		// Convert class name to wpcs file name: Pascal_Case or Underscored -> kebab
		$file_name = preg_replace( '/([a-z])([A-Z])/', '$1-$2', $class_name );
		$file_name = str_replace( '_', '-', $file_name );
		$file_name = strtolower( $file_name );

		// Ensure file name starts with class- (matches WPCS convention)
		if ( strpos( $file_name, 'class-' ) !== 0 ) {
			$file_name = 'class-' . $file_name;
		}

		$path = get_template_directory() . '/inc/' . ( empty( $dir_parts ) ? '' : implode( '/', $dir_parts ) . '/' ) . $file_name . '.php';

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
);