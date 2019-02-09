<?php
/**
 * API Service for Parler
 *
 * @since      1.0.0
 *
 * @package    Parler_Api_Service*
 */

/**
 * Class Parler_Api_Service
 */
class Parler_Api_Service {

	/**
	 * The last response if one was received.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $last_response
	 */
	private $last_response;

	/**
	 * Get the default request headers.
	 *
	 * @param bool $plugin_auth A bool to trigger plugin auth vs. api key.
	 * @return array
	 */
	public function get_default_headers( $plugin_auth = false ) {
		if ( $plugin_auth && get_option( 'parler_plugin_token', false ) ) {
			// Pull secret key if we have it in the db.
			$headers = array(
				'Authorization' => get_option( 'parler_plugin_token' ),
			);
		} elseif ( get_option( 'parler_api_token', false ) ) {
			// Pull secret key if we have it in the db.
			$headers = array(
				'Authorization' => get_option( 'parler_api_token' ),
			);
		} else {
			// Assume we have no credentials yet.
			$headers = array();
		}

		$headers = array_merge(
			$headers,
			array(
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			)
		);

		return array( 'headers' => $headers );
	}

	/**
	 * Makes a GET request to the Parler API.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $url_path The Parler endpoint to perform a GET request to.
	 * @param  array  $request_params The params to be appended to the query.
	 *
	 * @return mixed The response data.
	 */
	public function get( $url_path, $request_params ) {
		$api_uri = $this->get_parler_host() . $url_path;

		foreach ( $request_params as $key => $values_array ) {
			if ( ! is_array( $values_array ) ) {
				$values_array = array( $values_array );
			}

			foreach ( $values_array as $value ) {
				$api_uri .= '&' . $key . '=' . rawurlencode( $value );
			}
		}

		$headers = $this->get_default_headers();

		$parler_response     = wp_remote_get( $api_uri, $headers );
		$this->last_response = $parler_response;

		return $this->get_response_body( $parler_response );
	}

	/**
	 * Makes a POST request to the Parler API.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $url_path The Parler API endpoint to perform a POST request.
	 * @param  array  $original_params The params to be added to the body.
	 * @param  bool   $return_status_code Optional if you just want to get back the HTTP status code.
	 * @param  bool   $plugin_auth Optional Set to true for authenticating with the plugin key instead of the user key.
	 *
	 * @return mixed The response data.
	 */
	public function post( $url_path, $original_params, $return_status_code = false, $plugin_auth = false ) {

		$request_params = array_merge(
			$this->get_default_headers( $plugin_auth ),
			array(
				'body'   => $original_params,
				'method' => 'POST',
			)
		);

		$api_uri             = $this->get_parler_host() . $url_path;
		$parler_response     = wp_remote_post( $api_uri, $request_params );
		$this->last_response = $parler_response;

		if ( $return_status_code ) {
			return $parler_response['response']['code'];
		}

		return $this->get_response_body( $parler_response );
	}

	/**
	 * Create a post retroactively and any comments on the post.
	 *
	 * @param int    $id the id of the post.
	 * @param string $post the post slug.
	 *
	 * @return string $response
	 */
	public function create_retroactive_post( $id, $post ) {
		$title     = $post->post_title;
		$permalink = get_permalink( $id );
		$excerpt   = get_the_excerpt( $post );
		$post_date = get_the_date( 'YmdHis', $id );
		$post_data = array(
			'id'        => $id,
			'title'     => $title,
			'excerpt'   => $excerpt,
			'permalink' => $permalink,
			'createdAt' => $post_date,
		);

		// Load Post to Parler.
		$this->debug_log( 'Loading Post to Parler: ' . wp_json_encode( $post_data ) );
		$response = $this->post( 'f0irTo9n', wp_json_encode( $post_data ), false, true );
		$this->debug_log( 'Retro Post Response: ' . wp_json_encode( $response ) );

		// Get all WordPress comments for this post.
		$comments = get_comments( [ 'post_id' => $id ] );
		$this->debug_log( "[id:$id] Total Comments: " . (string) count( $comments ) );

		// @todo Refactor to do bulk comment loading
		foreach ( $comments as $comment ) {
			$comment_data = array(
				'body'         => $comment->comment_content,
				'createdAt'    => $comment->comment_date,
				'creatorEmail' => $comment->comment_author_email,
				'creatorName'  => $comment->comment_author,
				'id'           => $comment->comment_ID,
				'parent'       => $comment->comment_parent,
				'post'         => $permalink,
			);
			$this->debug_log( "[post_id:{$id}][comment_id:{$comment->comment_ID}] Loading Comment to Parler: " . wp_json_encode( $comment_data ) );
			// Load Comments into Parler.
			$comment_response = $this->post( '0JFJvZ79', wp_json_encode( $comment_data ), false, true );
			$this->debug_log( "[post_id:{$id}][comment_id:{$comment->comment_ID}] Retro Comment Response:" . wp_json_encode( $comment_response ) );
		}

		return $response;
	}

	/**
	 * Get a permenant token from a temp token.
	 *
	 * @param string $temp_token The temp token from sso.
	 *
	 * @return bool|string
	 */
	public function get_permanent_token( $temp_token ) {
		$payload = array( 'token' => $temp_token );
		// get perm token.
		$response = $this->post( 'JFFf6vNd', wp_json_encode( $payload ) );

		if ( ! empty( $response->message ) ) {
			// @todo Display this error message in a more formal way.
			echo '<br /><b>An error has occured:' . esc_html( $response->message ) . '</b><br /><p>Please contact support or try again later.</p>';

			return false;
		}

		if ( isset( $response->id ) ) {
			update_option( 'parler_user_id', $response->id );
		}
		if ( isset( $response->username ) ) {
			update_option( 'parler_username', $response->username );
		}
		if ( isset( $response->name ) ) {
			update_option( 'parler_profile_name', $response->name );
		}
		if ( isset( $response->token ) ) {
			update_option( 'parler_api_token', $response->token );

			return $response->token;
		}

		return false;
	}

	/**
	 * Get a plugin key for this domain name.
	 *
	 * @param string $domain The domain to get a key for.
	 *
	 * @return string The response string.
	 */
	public function get_plugin_key( $domain ) {
		$payload = array( 'domain' => $domain );

		$response = $this->post( '8uTp6xYl', wp_json_encode( $payload ) );

		if ( isset( $response->token ) ) {
			update_option( 'parler_plugin_token', $response->token );
		}
		if ( isset( $response->domain ) ) {
			update_option( 'parler_plugin_domain', $response->domain );
		}
		if ( isset( $response->hash ) ) {
			update_option( 'parler_plugin_hash', $response->hash );

			return $response->hash;
		}

		return $response;
	}

	/**
	 * Verify the domain is owned by the user.
	 *
	 * @param string $plugin_token The plugin token to verify.
	 *
	 * @return string The response string.
	 */
	public function verify_plugin_key( $plugin_token ) {
		$payload = array( 'key' => $plugin_token );

		$response = $this->post( 'zNMxvZTZ', wp_json_encode( $payload ) );

		return $response;
	}

	/**
	 * Get the response body.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error|array $response The response.
	 *
	 * @return mixed The response body for the Parler API in a friendly format.
	 */
	private function get_response_body( $response ) {
		if ( is_wp_error( $response ) ) {
			$error_msg          = $response->get_error_message();
			$response           = new StdClass();
			$response->errorMsg = $error_msg;
		} else {
			$response = json_decode( $response['body'] );
		}

		return $response;
	}

	/**
	 * Get the host of the parler API based on the constant or default to live.
	 *
	 * @return string The API url.
	 */
	private function get_parler_host() {
		// Override the url in the root parler-for-wordpress.php file.
		if ( defined( 'PARLER4WP_API_HOST' ) ) {
			return PARLER4WP_API_HOST;
		} elseif ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
			return 'https://staging.par.pw/v1/';
		}
		return 'https://par.pw/v1/';
	}

	/**
	 * Set the secret token used for auth.
	 *
	 * @param string $secret_key The secret key to auth with against the Parler API.
	 */
	public function set_secret_key( $secret_key ) {
		$this->secret_key = $secret_key;
	}

	/**
	 * Get the last http response code if there was one. Null if none.
	 *
	 * @return int|null
	 */
	public function get_last_response_code() {
		if ( isset( $this->last_response['response'] ) && ! empty( $this->last_response['response']['code'] ) ) {
			return (int) $this->last_response['response']['code'];
		}
		return null;
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
				plugin_dir_path( __FILE__ ) . '../logs/service_debug.log',
				'[' . date( DATE_RFC2822 ) . '] ' . $msg . PHP_EOL,
				FILE_APPEND | LOCK_EX
			);
		}
	}

}
