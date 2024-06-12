<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.mdmostakshahid.com/
 * @since             1.0.0
 * @package           Mos_Restricted_Categories_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Mos Restricted Categories for Woocommerce
 * Plugin URI:        https://www.mdmostakshahid.com/mos-restricted-categories-for-woocommerce
 * Description:       Lock entire WooCommerce categories - restrict access to certain users, and user roles, or create a secret password. All products are completely hidden.
 * Version:           1.0.0
 * Author:            Md. Mostak Shahid
 * Author URI:        https://www.mdmostakshahid.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mos-restricted-categories-for-woocommerce
 * Domain Path:       /languages
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
define( 'MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
define( 'MOS_RESTRICTED_CATEGORIES_FOR_WOOCOMMERCE_NAME', 'Mos Restricted Categories for Woocommerce' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mos-restricted-categories-for-woocommerce-activator.php
 */
function mos_restricted_categories_for_woocommerce_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mos-restricted-categories-for-woocommerce-activator.php';
	Mos_Restricted_Categories_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mos-restricted-categories-for-woocommerce-deactivator.php
 */
function mos_restricted_categories_for_woocommerce_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mos-restricted-categories-for-woocommerce-deactivator.php';
	Mos_Restricted_Categories_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'mos_restricted_categories_for_woocommerce_activate' );
register_deactivation_hook( __FILE__, 'mos_restricted_categories_for_woocommerce_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mos-restricted-categories-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mos_restricted_categories_for_woocommerce_run() {

	$plugin = new Mos_Restricted_Categories_For_Woocommerce();
	$plugin->run();

}
mos_restricted_categories_for_woocommerce_run();
//mos_restricted_categories_for_woocommerce
//mos_restricted_categories_for_woocommerce_cat_list_display


add_action('edit_form_after_editor', function(){
	global $typenow;
	//if($typenow == 'product') {
		echo 'Hello';
	//}
});