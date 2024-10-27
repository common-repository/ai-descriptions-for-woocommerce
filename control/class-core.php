<?php
/**
 * Class Core
 *
 * The Core class handles the initialization and setup of the plugin.
 */

namespace AIDFW_Plugin\Control;

use /**
 * Class Admin
 *
 * This class is responsible for handling the administration functionality of the plugin.
 */
	AIDFW_Plugin\Admin\Admin;
use /**
 * Class Admin_Settings
 *
 * Represents the administrative settings for the AIDFW Plugin.
 */
	AIDFW_Plugin\Admin\Admin_Settings;

/**
 * Core class
 *
 * This class represents the core functionality of the application.
 * It sets up options data, defines settings pages, and defines admin hooks.
 *
 * @package YourPackageName
 */
class Core {
	public function __construct() {
	}

	/**
	 *
	 */
	public function run() {
		$this->set_options_data();
		$this->settings_pages();
		$this->define_admin_hooks();
	}

	/**
	 *
	 */
	private function set_options_data() {
		// set up options & cron - do it here rather than activator to cover multi sites
		$options = get_option( 'ai-descriptions-for-woocommerce' );
		if ( false === $options ) {
			add_option( 'ai-descriptions-for-woocommerce', Admin_Settings::option_defaults( 'ai-descriptions-for-woocommerce' ) );
		}
	}

	/**
	 *
	 */
	private function settings_pages() {
		$settings = new Admin_Settings();
		add_action( 'admin_menu', array( $settings, 'settings_setup' ) );
		add_action( 'init', array( $settings, 'plugin_action_links' ) );
	}

	/**
	 *
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Admin();
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
	}
}
