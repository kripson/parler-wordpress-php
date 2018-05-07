<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Parler_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Parler For WordPress
 * Plugin URI:        http://localhost:3000/commentloader
 * Description:       Integrates Parler into the WordPress comment system.
 * Version:           1.0.0
 * Author:            Seaboden
 * Author URI:        http://parler.com/
 * Text Domain:       parler-for-wordpress
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
define( 'PARLER_FOR_WORDPRESS', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-parler-for-wordpress-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parler-for-wordpress-activator.php';
	Parler_For_WordpressActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-parler-for-wordpress-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parler-for-wordpress-deactivator.php';
	Parler_For_WordpressDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-parler-for-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Parler_For_Wordpress();
	$plugin->run();

}
run_plugin_name();
