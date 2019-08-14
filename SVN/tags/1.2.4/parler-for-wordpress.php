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
 * Plugin Name:       Parler
 * Plugin URI:        https://home.parler.com/installation/
 * Description:       A Social News and Content Engagement System working to increase community activity, grow audience exposure, and drive site traffic.
 * Version:           1.2.4
 * Author:            Parler LLC
 * Author URI:        https://home.parler.com/
 * Text Domain:       parler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Autoload classes in parler/ directory.
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Plugin Version and Target Parler Environment.
define( 'PARLER4WP_VERSION', '1.2.4' );
define( 'PARLER4WP_ENV', 'PROD' );

if ( file_exists( plugin_dir_path( __FILE__ ) . 'config.php' ) ) {
	require plugin_dir_path( __FILE__ ) . 'config.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-parler-for-wordpress-activator.php
 */
function activate_parler_plugin() {
	if ( version_compare( phpversion(), '5.4', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( 'Parler requires PHP 5.4 or higher. Please upgrade your PHP version.' );
	}
	Parler_For_WordPress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-parler-for-wordpress-deactivator.php
 */
function deactivate_parler_plugin() {
	Parler_For_WordPress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_parler_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_parler_plugin' );


/**
 * Adds the async option to the javascript tags on parler js/css code
 *
 * @param string $url the url to see if async tags need to be thrown on.
 * @return string
 */
function parler_async_scripts( $url ) {
	if ( strpos( $url, '#parlerasync' ) === false ) {
		return $url;
	}
	return str_replace( '#parlerasync', '', $url ) . "' async='async";
}

add_filter( 'clean_url', 'parler_async_scripts', 11, 1 );

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
