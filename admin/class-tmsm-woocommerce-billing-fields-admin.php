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
	 * Sanitize field display.
	 *
	 * @param string $value '', 'subcategories', or 'both'.
	 * @return string
	 */
	public function sanitize_checkout_field_display( $value ) {
		$options = array( 'hidden', 'optional', 'required' );
		return in_array( $value, $options, true ) ? $value : '';
	}

	/**
	 * Add birthdate option to customizer
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @return array
	 */
	function billing_fields_sections( $wp_customize ) {

		// Checkout field controls.
		$fields = array(
			'title'   => __( 'Billing Title', 'woocommerce' ),
			'birthdate' => __( 'Birth Date', 'woocommerce' ),
		);
		foreach ( $fields as $field => $label ) {
			$wp_customize->add_setting(
				'tmsm_woocommerce_billing_fields_' . $field,
				array(
					'default'           => 'optional',
					'type'              => 'option',
					'capability'        => 'manage_woocommerce',
					'sanitize_callback' => array( $this, 'sanitize_checkout_field_display' ),
				)
			);
			$wp_customize->add_control(
				'woocommerce_checkout_' . $field . '_field',
				array(
					/* Translators: %s field name. */
					'label'    => sprintf( __( '%s field', 'woocommerce' ), $label ),
					'section'  => 'woocommerce_checkout',
					'settings' => 'tmsm_woocommerce_billing_fields_' . $field,
					'type'     => 'select',
					'choices'  => array(
						'hidden'   => __( 'Hidden', 'woocommerce' ),
						'optional' => __( 'Optional', 'woocommerce' ),
						'required' => __( 'Required', 'woocommerce' ),
					),
				)
			);
		}

		/*
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
				'desc'          => __( 'Birthdate field', 'tmsm-woocommerce-billing-fields' ),
				'id'            => 'tmsm_woocommerce_billing_fields_birthdate',
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
		*/
	}
}
