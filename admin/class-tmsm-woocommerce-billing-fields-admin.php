<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/nicomollet
 * @since      1.0.0
 *
 * @package    Tmsm_Woocommerce_Billing_Fields
 * @subpackage Tmsm_Woocommerce_Billing_Fields/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tmsm_Woocommerce_Billing_Fields
 * @subpackage Tmsm_Woocommerce_Billing_Fields/admin
 * @author     Nicolas Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Woocommerce_Billing_Fields_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-woocommerce-billing-fields-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-woocommerce-billing-fields-admin.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Add birthday option to checkout tab
	 *
	 * @param $settings
	 * @param $current_section
	 *
	 * @return array
	 */
	function woocommerce_get_settings_checkout_birthdaytitle( $settings, $current_section ) {

		$new_settings = array(

			array(
				'title'         => __( 'Checkout fields', 'tmsm-woocommerce-billing-fields' ),
				'desc'          => __( 'Title field', 'tmsm-woocommerce-billing-fields' ),
				'id'            => 'tmsm_woocommerce_billing_fields_title',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'start',
				'autoload'      => false,
			),
			array(
				'desc'          => __( 'Birthday field', 'tmsm-woocommerce-billing-fields' ),
				'id'            => 'tmsm_woocommerce_billing_fields_birthday',
				'default'       => 'no',
				'type'          => 'checkbox',
				'checkboxgroup' => 'end',
				'autoload'      => false,
			)
		);


		$offset = isset( $settings['unforce_ssl_checkout'] ) ? 2 : 1;
		// Add new settings to the existing ones.
		foreach ( $settings as $key => $setting ) {
			if ( isset( $setting['id'] ) && 'woocommerce_checkout_page_id' == $setting['id'] ) {
				array_splice( $settings, $key + $offset, 0, $new_settings );
				break;
			}
		}

		return $settings;

	}
}
