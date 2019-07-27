<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean up configs when uninstalling the plugin.
update_option( 'parler_api_token', null );
update_option( 'parler_custom_width', null );
update_option( 'parler_default_location', null );
update_option( 'parler_import_all_posts', null );
update_option( 'parler_plugin_domain', null );
update_option( 'parler_plugin_hash', null );
update_option( 'parler_plugin_token', null );
update_option( 'parler_profile_name', null );
update_option( 'parler_user_id', null );
update_option( 'parler_username', null );
