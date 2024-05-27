<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.mdmostakshahid.com/
 * @since      1.0.0
 *
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mos_Restricted_Categories_For_Woocommerce
 * @subpackage Mos_Restricted_Categories_For_Woocommerce/includes
 * @author     Md. Mostak Shahid <mostak.shahid@gmail.com>
 */
class Mos_Restricted_Categories_For_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mos-restricted-categories-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
