<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://redacted.store
 * @since      1.0.0
 *
 * @package    Bcbs_Orders_View_Enhancements
 * @subpackage Bcbs_Orders_View_Enhancements/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bcbs_Orders_View_Enhancements
 * @subpackage Bcbs_Orders_View_Enhancements/includes
 * @author     webmaster <webmaster@redacted.store>
 */
class Bcbs_Orders_View_Enhancements_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bcbs-orders-view-enhancements',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
