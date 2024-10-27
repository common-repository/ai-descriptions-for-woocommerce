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

namespace AIDFW_Plugin\Admin;

use AIDFW_Plugin\Core\Utilities;

class Admin_Settings extends Admin_Pages {
	protected $settings_page;
	protected $settings_page_id = 'settings_page_ai-descriptions-for-woocommerce-settings';
	protected $option_group = 'ai-descriptions-for-woocommerce';
	protected $options;

	private $titles;

	/**
	 * Settings constructor.
	 *
	 * @param string $plugin_name
	 * @param string $version plugin version.
	 */

	public function __construct( ) {
		$this->titles      = array(
			'API Key' => array(
				'title' => esc_html__( 'OpenAI API Key', 'ai-descriptions-for-woocommerce' ),
				'tip'   => esc_html__( 'Get the API key from Open AI', 'ai-descriptions-for-woocommerce' ),
			),
		);

		$this->options = get_option( 'ai-descriptions-for-woocommerce' );
		$this->options = array_merge( self::option_defaults( 'ai-descriptions-for-woocommerce' ), $this->options );

		$this->settings_title = '<img src="' . dirname( plugin_dir_url( __FILE__ ) ) . '/admin/images/brand/brand-75h.jpg" class="logo" alt="Fullworks Logo"/><div class="text">' . __( 'Settings', 'ai-descriptions-for-woocommerce' ) . '</div>';
		parent::__construct();
	}

	public static function option_defaults( $option ) {
		switch ( $option ) {
			case 'ai-descriptions-for-woocommerce':
				$res = array(
					'openai_api_key' => '',
				);

				return $res;
			default:
				return false;
		}
	}

	public function plugin_action_links() {
		add_filter(
			'plugin_action_links_' . AIDFW_PLUGIN_BASENAME,
			array(
				$this,
				'add_plugin_action_links',
			)
		);
	}

	public function add_plugin_action_links( $links ) {
		$links = array_merge(
			array(
				'<a href="' . esc_url( admin_url( 'options-general.php?page=ai-descriptions-for-woocommerce-settings' ) ) . '">' . esc_html__( 'Settings' ) . '</a>',
			),
			$links
		);

		return $links;
	}

	public function register_settings() {
		/* Register our setting. */

		register_setting(
			$this->option_group,                         /* Option Group */
			'ai-descriptions-for-woocommerce',                   /* Option Name */
			array( $this, 'sanitize_settings' )          /* Sanitize Callback */
		);

		Utilities::get_instance()->register_settings_page_tab( __( 'Settings', 'ai-descriptions-for-woocommerce' ), 'settings', admin_url( 'admin.php?page=ai-descriptions-for-woocommerce-settings' ), 0 );
		/* Add settings menu page */
		$this->settings_page = add_submenu_page(
			'ai-descriptions-for-woocommerce-settings',
			esc_html__( 'Settings', 'ai-descriptions-for-woocommerce' ), /* Page Title */
			esc_html__( 'Settings', 'ai-descriptions-for-woocommerce' ),                       /* Menu Title */
			'manage_options',                 /* Capability */
			'ai-descriptions-for-woocommerce-settings',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);

		register_setting(
			$this->option_group,                         /* Option Group */
			"{$this->option_group}-reset",                   /* Option Name */
			array( $this, 'reset_sanitize' )          /* Sanitize Callback */
		);

	}

	public function reset_sanitize( $settings ) {
		// Detect multiple sanitizing passes.
		// Accomodates bug: https://core.trac.wordpress.org/ticket/21989

		if ( ! empty( $settings ) ) {
			add_settings_error( $this->option_group, '', esc_html__( 'Settings reset to defaults.', 'ai-descriptions-for-woocommerce' ), 'updated' );
			/* Delete Option */
			$this->delete_options();

		}

		return $settings;
	}

	public function delete_options() {
		update_option( 'ai-descriptions-for-woocommerce', self::option_defaults( 'ai-descriptions-for-woocommerce' ) );
	}

	public function add_meta_boxes() {

		$this->add_meta_box(
			'settings',                  /* Meta Box ID */
			esc_html__( 'Settings', 'ai-descriptions-for-woocommerce' ),               /* Title */
			array( $this, 'meta_box_settings' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default',                /* Priority */
			null,
			false
		);
	}

	private function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null, $closed = true ) {
		add_meta_box(
			$id,
			$title,
			$callback,
			$screen,
			$context,
			$priority,
			$callback_args
		);
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification not required.
		if ( ! isset( $_GET['settings-updated'] ) ) {
			if ( $closed ) {
				add_filter(
					"postbox_classes_{$screen}_{$id}",
					function ( $classes ) {
						array_push( $classes, 'closed' );

						return $classes;
					}
				);
			}
		}
	}

	public function sanitize_settings( $settings ) {
		if ( isset( $_REQUEST['ai-descriptions-for-woocommerce-reset'] ) ) {
			if ( ! isset( $_REQUEST['_aidwc_submit_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_aidwc_submit_meta_box_nonce'] ) ), 'fwas_submit_meta_box' ) ) {
				wp_die( esc_html__( 'Nonce verification failed.', 'ai-descriptions-for-woocommerce' ) );
			}

			return $settings;
		}
		if ( isset( $settings ['openai_api_key'] ) && ! empty( $settings ['openai_api_key'] ) ) {
			$settings['openai_api_key'] = sanitize_text_field( $settings['openai_api_key'] );
		}


		return $settings;
	}

	private function get_option( $option_name ) {
		$option = $this->options[ $option_name ];

		return $option;
	}

	public function meta_box_settings() {
		?>
        <table class="form-table">
            <tbody>
            <tr>
				<?php $this->display_th( 'API Key' ); ?>
                <td>
                    <label for="ai-descriptions-for-woocommerce[openai_api_key]"><input type="password"
                                                                                        name="ai-descriptions-for-woocommerce[openai_api_key]"
                                                                                        id="ai-descriptions-for-woocommerce[openai_api_key]"
                                                                                        value="<?php echo esc_attr( $this->get_option( 'openai_api_key' ) ); ?>"
                        >
						<?php esc_html_e( 'Get your OpenAI API key ', 'ai-descriptions-for-woocommerce' ); ?>
                        <a href="https://platform.openai.com/api-keys/" target="_blank" >
                        <?php esc_html_e( 'from OpenAI here (opens in new tab)', 'ai-descriptions-for-woocommerce' ); ?>
                        <span class="dashicons dashicons-external"></span></a>
                    </label>
                </td>
				<?php $this->display_tip( 'API Key' ); ?>
            </tr>
            </tbody>
        </table>
		<?php
	}

	private function display_th( $title ) {
		?>
        <th scope="row">
			<?php
			echo wp_kses_post( $this->titles[ $title ]['title'] );
			?>
        </th>
		<?php
	}

	private function display_tip( $title ) {
		?>
        <td>
			<?php
			echo ( isset( $this->titles[ $title ]['tip'] ) ) ? '<div class="help-tip"><p>' . esc_html( $this->titles[ $title ]['tip'] ) . '</p></div>' : '';
			?>
        </td>
		<?php
	}
}
