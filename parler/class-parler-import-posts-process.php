<?php
/**
 * Import process for parler comments.
 *
 * @link       https://parler.com
 * @since      1.0.0
 *
 * @package    Parler_Api_Service
 * @subpackage Parler_Api_Service/includes
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */

/**
 * Class Parler_Api_Service
 */
class Parler_Import_Posts_Process extends Parler_Background_Process {


	/**
	 * The action name.
	 *
	 * @var string $action
	 */
	protected $action = 'import_posts';

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $post_id Queue item to iterate over.
	 *
	 * @return mixed
	 */
	protected function task( $post_id ) {
		$this->debug_log( "Importing post $post_id" );

		if ( ! get_option( 'parler_api_token', null ) ) {
			$this->debug_log( 'No API Key Found! Bailing.' );
			return false;
		}

		// Get service and create a Parler post for this WordPress post.
		$parler_service = new Parler_Api_Service( get_option( 'parler_api_token' ) );

		// @todo change this to bulk import
		$post     = get_post( $id );
		$response = $parler_service->create_retroactive_post( $id, $post );

		$this->debug_log( (string) $response );

		return false;
	}

	/**
	 * Log a message to plugin debug logs.
	 *
	 * @param string $msg The message to log if in debug mode.
	 */
	protected function debug_log( $msg ) {
		if ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
			// phpcs:ignore
			file_put_contents(
				WP_PLUGIN_DIR . '/parler-for-wordpress/logs/parler_debug.log',
				'[' . date( DATE_RFC2822 ) . '] ' . $msg . PHP_EOL,
				FILE_APPEND | LOCK_EX
			);
		}
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		$this->debug_log( 'Complete' );
		parent::complete();
		// Show notice to user or perform some other arbitrary task...
	}
}
