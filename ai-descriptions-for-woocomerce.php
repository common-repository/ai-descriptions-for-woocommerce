<?php
/**
 * @copyright (c) 2024.
 * @author            Alan Fuller (support@fullworksplugins.com)
 * @licence           GPL V3 https://www.gnu.org/licenses/gpl-3.0.en.html
 * @link                  https://fullworks.net
 *
 * This file is part of Fullworks Plugins.
 *
 *     Fullworks Plugins is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Fullworks Plugins is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with   Fullworks Plugins.  https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 *
 */

/**
 *
 * Plugin Name:       AI Descriptions for WooCommerce
 * Plugin URI:        https://fullworksplugins.com/products/ai-descriptions-for-woocommerce/
 * Description:       AI Descriptions for WooCommerce
 * Version:           1.0.0
 * Author:            Fullworks
 * Author URI:        https://fullworksplugins.com/
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       ai-descriptions-for-woocommerce
 * Domain Path:       /languages
 *
 * @package ai-descriptions-for-woocommerce
 *
 *
 *
 */

namespace AIDFW_Plugin;

use AIDFW_Plugin\Control\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'AIDFW_Plugin\aidfw_run' ) ) {
	define( 'AIDFW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'AIDFW_CONTENT_DIR', dirname( plugin_dir_path( __DIR__ ) ) );
	define( 'AIDFW_PLUGINS_TOP_DIR', plugin_dir_path( __DIR__ ) );
	define( 'AIDFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
	define( 'AIDFW_PLUGIN_VERSION', '1.0.0' );


	require_once AIDFW_PLUGIN_DIR . 'control/autoloader.php';
	require_once AIDFW_PLUGIN_DIR . 'vendor/autoload.php';

	function aidfw_run() {

		register_activation_hook( __FILE__, array( '\AIDFW_Plugin\Control\Activator', 'activate' ) );
		register_deactivation_hook(
			__FILE__,
			array(
				'\AIDFW_Plugin\Control\Deactivator',
				'deactivate',
			)
		);
		register_uninstall_hook(
			__FILE__,
			array(
				'\AIDFW_Plugin\Control\Uninstaller',
				'uninstall',
			)
		);

		$plugin = new Core();
		$plugin->run();
	}


	aidfw_run();
} else {
	die( esc_html__( 'Cannot execute as the plugin already exists, if you have a another version installed deactivate that and try again', 'ai-descriptions-for-woocommerce' ) );
}

