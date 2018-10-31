<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/nicomollet
 * @since      1.0.0
 *
 * @package    Tmsm_Woocommerce_Billing_Fields
 * @subpackage Tmsm_Woocommerce_Billing_Fields/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tmsm_Woocommerce_Billing_Fields
 * @subpackage Tmsm_Woocommerce_Billing_Fields/public
 * @author     Nicolas Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Woocommerce_Billing_Fields_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-woocommerce-billing-fields-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if(self::checkout_birthdate_field_is_enabled()){
			wp_enqueue_script( 'jquery-mask', plugin_dir_url( __FILE__ ) . 'js/jquery.mask.min.js', array( 'jquery' ), null, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-woocommerce-billing-fields-public.js', array( 'jquery', 'jquery-mask' ), null, true );

			// Localize the script with new data
			$translation_array = array(
				'birthdateformat' => _x( 'mm/dd/yyyy', 'birthdate date format', 'tmsm-woocommerce-billing-fields' ),
			);
			wp_localize_script( $this->plugin_name, 'tmsm_woocommerce_billing_fields_i18n', $translation_array );
		}
	}


	/**
	 * Default checkout values: user firstname / lastname
	 *
	 * @param $input
	 * @param $key
	 *
	 * @return null|string
	 */
	function checkout_default_values_user( $input, $key ) {
		global $current_user;
		switch ( $key ) :
			case 'billing_first_name':
			case 'shipping_first_name':
				return $current_user->first_name;
				break;

			case 'billing_last_name':
			case 'shipping_last_name':
				return $current_user->last_name;
				break;
			case 'billing_email':
				return $current_user->user_email;
				break;
		endswitch;

	}

	/**
	 * Default checkout values: birthdate
	 *
	 * @param $input
	 * @param $key
	 *
	 * @var WP_User $current_user
	 *
	 * @return null|string
	 */
	function checkout_default_values_birthdate( $input, $key ) {
		global $current_user;

		switch ( $key ) :
			case 'billing_birthdate':
				if( method_exists('DateTime', 'createFromFormat') && !empty($current_user->ID)){
					$objdate = DateTime::createFromFormat( _x( 'Y-m-d', 'birthdate date format conversion', 'tmsm-woocommerce-billing-fields' ),
						get_user_meta($current_user->ID, 'billing_birthdate', true) );
					if( $objdate instanceof DateTime ){
						return $objdate->format(_x( 'm/d/Y', 'birthdate date format', 'tmsm-woocommerce-billing-fields' ));
					}
				}
				return '';
				break;
		endswitch;

	}

	/**
	 * Title field options
	 *
	 * @return mixed
	 */
	public static function billing_title_options(){

		$options = array(
			'2' => _x('Ms', 'honorific title', 'tmsm-woocommerce-billing-fields' ),
			'1' => _x('Mr', 'honorific title', 'tmsm-woocommerce-billing-fields' ),
		);

		return $options;
	}

	/**
	 * Add title field to checkout page
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	function billing_fields_title( $fields ) {

		if(self::checkout_title_field_is_enabled()){
			$new_fields['billing_title']  = array(
				'type'            => 'radio',
				'label'          => _x('Title', 'honorific title label', 'tmsm-woocommerce-billing-fields'),
				'required'       => true,
				'class'          => ['billing-title'],
				'label_class'          => ['control-label'],
				'input_class'          => [''],
				'priority' => -100,
				//'custom_attributes'          => ['style' => 'display:inline-block'],
				'options'     => self::billing_title_options()
			);
			$fields = array_merge($fields, $new_fields );
		}

		return $fields;
	}

	/**
	 * Add birthdate fields to checkout page
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	function billing_fields_birthdate( $fields ) {
		if(self::checkout_birthdate_field_is_enabled() && is_checkout()){
			$new_fields['billing_birthdate'] = array(
				'type'        => 'text',
				'label'       => _x( 'Date of birth', 'birthdate label', 'tmsm-woocommerce-billing-fields' ),
				//'description'          => _x('Day', 'birthdate day', 'tmsm-woocommerce-billing-fields'),
				'placeholder' => _x( 'mm/dd/yyyy', 'birthdate placeholder', 'tmsm-woocommerce-billing-fields' ),
				'required'    => false,
				'class'       => [ 'billing-birthdate' ],
				'label_class' => [ 'control-label' ],
				'input_class' => [ '' ],
				'priority'    => 2000,
				'autocomplete'    => 'bday',
				//'custom_attributes'          => ['style' => 'display:inline-block'],
			);

			$fields = array_merge($fields, $new_fields );
		}

		return $fields;
	}

/**
 * Reorder fields by priority
 *
 * @param $fields
 *
 * @return mixed
 */
function reorder_fields($fields) {
	uasort($fields['billing'], function($a, $b) {
		return $a['priority'] <=> $b['priority'];
	});
	return $fields;
}

	/**
	 * Update order meta fields: title
	 *
	 * @param $order_id integer
	 * @param $posted array
	 */
	function checkout_update_order_meta_title( $order_id, $posted ){

		if( isset( $posted['billing_title'] ) ) {
			update_post_meta( $order_id, '_billing_title', sanitize_text_field( $posted['billing_title'] ) );
		}

	}

	/**
	 * Update order meta fields: birthdate
	 *
	 * @param $order_id integer
	 * @param $posted array
	 */
	function checkout_update_order_meta_birthdate( $order_id, $posted ){

		if( isset( $posted['billing_birthdate'] ) ) {
			if( method_exists('DateTime', 'createFromFormat') ){
				$objdate = DateTime::createFromFormat( _x( 'm/d/Y', 'birthdate date format conversion', 'tmsm-woocommerce-billing-fields' ),
					sanitize_text_field( $posted['billing_birthdate'] ) );
				if( $objdate instanceof DateTime ){
					update_post_meta( $order_id, '_billing_birthdate', sanitize_text_field( $objdate->format('Y-m-d') ) );
				}
			}
		}

	}

	/**
	 * Mailchimp sync user merge tags: PRENOM, NOM, CIV, DDN
	 *
	 * @param array $merge_vars
	 * @param WP_User $user
	 *
	 * @return array
	 */
	function mailchimp_sync_user_mergetags($merge_vars, $user){

		// Firstname & Lastname
		$merge_vars['PRENOM'] = ( trim( get_user_meta( $user->ID, 'billing_first_name', true )) ? trim( get_user_meta( $user->ID, 'billing_first_name',
			true ) ) : trim( $user->first_name ) );
		$merge_vars['NOM']    = ( trim( get_user_meta( $user->ID, 'billing_last_name', true )) ? trim( get_user_meta( $user->ID, 'billing_last_name',
			true ) ) : trim( $user->last_name ) );

		// Title
		if(self::checkout_title_field_is_enabled()){
			$billing_title_value = get_user_meta($user->ID, 'billing_title', true);
			$billing_title_options = self::billing_title_options();


			if($billing_title_value && isset($billing_title_options[$billing_title_value])){
				$merge_vars['CIV'] = @$billing_title_options[$billing_title_value];
			}
		}

		// Birthdate
		if(self::checkout_birthdate_field_is_enabled()){
			$birthdatevalue = trim( get_user_meta( $user->ID, 'billing_birthdate', true ));
			if ( ! empty( $birthdatevalue ) ) {
				$objdate = DateTime::createFromFormat( _x( 'Y-m-d', 'birthdate date format conversion', 'tmsm-woocommerce-billing-fields' ),
					sanitize_text_field( $birthdatevalue ) );
				if ( $objdate instanceof DateTime ) {
					$merge_vars['DDN'] = $objdate->format( 'm/d' ); // Fixed format by Mailchimp
				}
			}
		}

		return $merge_vars;
	}

	/**
	 * Update birthdate value in user meta to format YYYY-MM-DD
	 *
	 * @param WC_Customer $customer
	 * @param $updated_props
	 */
	function woocommerce_customer_object_updated_props_birthdate($customer, $updated_props){

		if(self::checkout_birthdate_field_is_enabled()){
			if( method_exists('DateTime', 'createFromFormat') && !empty($customer->get_meta('billing_birthdate', true))){
				$objdate = DateTime::createFromFormat( _x( 'm/d/Y', 'birthdate date format conversion', 'tmsm-woocommerce-billing-fields' ),
					sanitize_text_field( $customer->get_meta('billing_birthdate', true) ) );
				if( $objdate instanceof DateTime ){
					$customer->update_meta_data('billing_birthdate', sanitize_text_field( $objdate->format('Y-m-d') ));
				}
			}
		}

	}

	/**
	 * Check if Title field is enabled
	 *
	 * @since    1.1.4
	 *
	 * @return bool
	 */
	private function checkout_title_field_is_enabled(){
		return get_option( 'tmsm_woocommerce_billing_fields_title', 'no' ) == 'yes';
	}

	/**
	 * Check if Birthdate field is enabled
	 *
	 * @since    1.1.4
	 *
	 * @return bool
	 */
	private function checkout_birthdate_field_is_enabled(){
		return get_option( 'tmsm_woocommerce_billing_fields_birthdate', 'no' ) == 'yes';
	}

}
