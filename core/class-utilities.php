<?php
/**
 * Class Utilities
 *
 * This class provides various utility methods for the plugin.
 */

namespace AIDFW_Plugin\Core;

/**
 * Class Utilities
 *
 * This class provides utility methods for various operations.
 */
class Utilities {

	/**
	 * @var
	 */
	protected static $instance;

	protected $settings_page_tabs;

	/**
	 * Utilities constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return Utilities
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_settings_page_tab( $title, $page, $href, $position ) {
		$this->settings_page_tabs[ $page ][ $position ] = array(
			'title' => $title,
			'href'  => $href,
		);
	}

	public function get_settings_page_tabs( $page ) {
		$tabs = $this->settings_page_tabs[ $page ];
		ksort( $tabs );

		return $tabs;
	}

	public function debug_log( $data ) {
		if ( ! defined( 'WP_DEBUG' ) || true !== WP_DEBUG ) {
			return;
		}
		if ( is_array( $data ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- debug only
			$data = print_r( $data, true );
		}
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- debug only
		error_log( '>>> plugin debug: ' . $data );
	}
}
