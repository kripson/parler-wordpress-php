<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 *
 * @package    Parler_For_WordPress_Activator*
 */

/**
 * Class Parler_For_WordPress_Activator
 */
class Parler_For_WordPress_Activator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! current_user_can( 'moderate_comments' ) ) {
			return;
		}

		/* * * Register Settings * * */

		// Parler Integration Settings.
		register_setting( 'parler-api-settings', 'parler_api_token', array( 'default' => null ) );
		register_setting( 'parler-api-settings', 'parler_user_id', array( 'default' => null ) );
		register_setting( 'parler-api-settings', 'parler_username', array( 'default' => null ) );
		register_setting( 'parler-api-settings', 'parler_profile_name', array( 'default' => null ) );

		// Plugin Settings for Parler.
		register_setting( 'parler-plugin-settings', 'parler_plugin_token', array( 'default' => null ) );
		register_setting( 'parler-plugin-settings', 'parler_plugin_domain', array( 'default' => null ) );
		register_setting( 'parler-plugin-settings', 'parler_plugin_hash', array( 'default' => null ) );

		// Display Settings.
		register_setting( 'parler-settings', 'parler_default_location', array( 'default' => true ) );
		register_setting( 'parler-settings', 'parler_custom_width', array( 'default' => null ) );
		register_setting( 'parler-settings', 'parler_custom_margin', array( 'default' => null ) );
		register_setting( 'parler-settings', 'parler_custom_padding', array( 'default' => null ) );

		// Import all posts.
		register_setting( 'parler-import-settings', 'parler_import_all_posts', array( 'default' => null ) );
	}

}
