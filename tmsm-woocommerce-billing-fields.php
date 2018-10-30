<?php

/**
 * @link              https://github.com/nicomollet
 * @since             1.0.0
 * @package           Tmsm_Woocommerce_Billing_Fields
 *
 * @wordpress-plugin
 * Plugin Name:       TMSM WooCommerce Billing Fields
 * Plugin URI:        https://github.com/thermesmarins/tmsm-woocommerce-billing-fields
 * Description:       WooCommerce Billing Fields for Thermes Marins de Saint-Malo
 * Version:           1.0.3
 * Author:            Nicolas Mollet
 * Author URI:        https://github.com/nicomollet
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tmsm-woocommerce-billing-fields
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/thermesmarins/tmsm-woocommerce-billing-fields
 * Github Branch:     master
 * Requires PHP:      5.6
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TMSM_WOOCOMMERCE_BILLING_FIELDS_VERSION', '1.0.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tmsm-woocommerce-billing-fields-activator.php
 */
function activate_tmsm_woocommerce_billing_fields() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-woocommerce-billing-fields-activator.php';
	Tmsm_Woocommerce_Billing_Fields_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tmsm-woocommerce-billing-fields-deactivator.php
 */
function deactivate_tmsm_woocommerce_billing_fields() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-woocommerce-billing-fields-deactivator.php';
	Tmsm_Woocommerce_Billing_Fields_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tmsm_woocommerce_billing_fields' );
register_deactivation_hook( __FILE__, 'deactivate_tmsm_woocommerce_billing_fields' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-woocommerce-billing-fields.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tmsm_woocommerce_billing_fields() {

	$plugin = new Tmsm_Woocommerce_Billing_Fields();
	$plugin->run();

}
run_tmsm_woocommerce_billing_fields();
