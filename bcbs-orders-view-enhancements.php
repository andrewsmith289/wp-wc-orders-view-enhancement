<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://redacted.store
 * @since             1.0.0
 * @package           Bcbs_Orders_View_Enhancements
 *
 * @wordpress-plugin
 * Plugin Name:       Orders View Enhancements
 * Version:           1.0.0
 * Author:            webmaster
 * Author URI:        ********************
 * Text Domain:       bcbs-orders-view-enhancements
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bcbs-orders-view-enhancements-activator.php
 */
function activate_bcbs_orders_view_enhancements() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bcbs-orders-view-enhancements-activator.php';
	Bcbs_Orders_View_Enhancements_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bcbs-orders-view-enhancements-deactivator.php
 */
function deactivate_bcbs_orders_view_enhancements() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bcbs-orders-view-enhancements-deactivator.php';
	Bcbs_Orders_View_Enhancements_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bcbs_orders_view_enhancements' );
register_deactivation_hook( __FILE__, 'deactivate_bcbs_orders_view_enhancements' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bcbs-orders-view-enhancements.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bcbs_orders_view_enhancements() {

	$plugin = new Bcbs_Orders_View_Enhancements();
	$plugin->run();

}
run_bcbs_orders_view_enhancements();
