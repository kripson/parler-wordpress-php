<?php
/**
 * Background import process for parler comments.
 *
 * @since      1.0.0
 *
 * @package    Parler_Import_Posts_Process*
 */

/**
 * Class Parler_Import_Posts_Process
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
		$this->debug_log( "Importing WP post id [{$post_id}]" );

		if ( ! get_option( 'parler_api_token', null ) ) {
			$this->debug_log( 'No API Key Found! Bailing.' );

			return false;
		}

		// Get service and create a Parler post for this WordPress post.
		$parler_service = new Parler_Api_Service( get_option( 'parler_api_token' ) );

		// @todo change this to bulk import API endpoint
		$post     = get_post( $post_id );
		$response = $parler_service->create_retroactive_post( $post_id, $post );

		$this->debug_log( 'Background Job Done' );

		return false;
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
