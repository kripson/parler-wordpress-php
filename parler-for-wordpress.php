<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://home.parler.com/
 * @since             1.0.0
 * @package           Parler_For_WordPress
 *
 * @WordPress-plugin
 * Plugin Name:       Parler For WordPress
 * Plugin URI:        https://home.parler.com/
 * Description:       A Social News and Content Engagement System working to increase community activity, grow audience exposure, and drive site traffic.
 * Version:           1.0.0
 * Author:            Parler LLC
 * Author URI:        https://home.parler.com/
 * Text Domain:       parler-for-WordPress
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
define( 'PARLER4WP_VERSION', '1.0.0' );
define( 'PARLER4WP_ENV', 'DEV' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-parler-for-WordPress-activator.php
 */
function activate_parler_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parler-for-WordPress-activator.php';

	if ( version_compare( phpversion(), '5.4', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'Parler requires PHP 5.4 or higher. Please upgrade your PHP version.' );
	}
	Parler_For_WordPress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-parler-for-WordPress-deactivator.php
 */
function deactivate_parler_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parler-for-WordPress-deactivator.php';
	Parler_For_WordPress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_parler_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_parler_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-parler-for-WordPress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_parler_plugin() {
	$plugin = new Parler_For_WordPress();
	$plugin->run();

}

run_parler_plugin();
