<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 *
 * @package    Parler_For_WordPress_Deactivator*
 */

/**
 * Class Parler_For_WordPress_Deactivator
 */
class Parler_For_WordPress_Deactivator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'moderate_comments' ) ) {
			return;
		}

		update_option( 'parler_api_token', null );
		update_option( 'parler_custom_width', null );
		update_option( 'parler_custom_margin', null );
		update_option( 'parler_custom_padding', null );
		update_option( 'parler_default_location', true );
		update_option( 'parler_import_all_posts', null );
		update_option( 'parler_plugin_domain', null );
		update_option( 'parler_plugin_hash', null );
		update_option( 'parler_plugin_token', null );
		update_option( 'parler_profile_name', null );
		update_option( 'parler_user_id', null );
		update_option( 'parler_username', null );
	}

}
