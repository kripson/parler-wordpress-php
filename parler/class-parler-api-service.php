<?php
/**
 * API Service for Parler
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
class Parler_Api_Service {


	const STAGING_PARLER_HOST = 'https://staging.par.pw/v1/';
	const PROD_PARLER_HOST    = 'https://par.pw/v1/';

	/**
	 * An API secret key for Authentication.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $secret_key The Parler secret key for auth.
	 */
	private $secret_key;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $secret_key The admin secret key associated with the $api_secret.
	 */
	public function __construct( $secret_key = null ) {
		$this->secret_key = $secret_key;
	}

	/**
	 * Makes a GET request to the Parler API.
	 *
	 * @since  1.0.0
	 * @param  string $url_path The Parler endpoint to perform a GET request to.
	 * @param  array  $request_params The params to be appended to the query.
	 * @return mixed The response data.
	 */
	public function get( $url_path, $request_params ) {
		$api_uri = $this->getParlerHost() . $url_path;

		foreach ( $request_params as $key => $values_array ) {
			if ( ! is_array( $values_array ) ) {
				$values_array = array( $values_array );
			}

			foreach ( $values_array as $value ) {
				$api_uri .= '&' . $key . '=' . rawurlencode( $value );
			}
		}

		$parler_response = wp_remote_get(
			$api_uri,
			array(
				'headers' => array(
					'Authorization' => $this->secret_key,
				),
			)
		);

		return $this->get_response_body( $parler_response );
	}

	/**
	 * Makes a POST request to the Parler API.
	 *
	 * @since  1.0.0
	 * @param  string $url_path The Parler API endpoint to perform a POST request.
	 * @param  array  $original_params The params to be added to the body.
	 * @param  bool   $return_status_code Optional if you just want to get back the HTTP status code.
	 * @return mixed The response data.
	 */
	public function post( $url_path, $original_params, $return_status_code = false ) {
		if ( $this->secret_key ) {
			// If we already authenticated with a perm token.
			$headers = array(
				'Authorization' => $this->secret_key,
			);
		} elseif ( get_option( 'parler_api_token', false ) ) {
			// Pull secret key if we have it in the db.
			$this->secret_key = get_option( 'parler_api_token' );
			$headers          = array(
				'Authorization' => $this->secret_key,
			);
		} else {
			// Assume we have no credentials yet.
			$headers = array();
		}

		$request_params = array(
			'body'    => $original_params,
			'method'  => 'POST',
			'headers' => $headers,
		);

		$api_uri         = $this->getParlerHost() . $url_path;
		$parler_response = wp_remote_post( $api_uri, $request_params );

		if ( $return_status_code ) {
			return $parler_response['response']['code'];
		}

		return $this->get_response_body( $parler_response );
	}

	/**
	 * Create a post retroactively
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

		$response = $this->post(
			'post/retroactive',
			wp_json_encode(
				array(
					'id'        => $id,
					'title'     => $title,
					'excerpt'   => $excerpt,
					'permalink' => $permalink,
					'createdAt' => $post_date,
				)
			)
		);

		return $response;
	}

	/**
	 * Get a permenant token from a temp token.
	 *
	 * @param string $temp_token The temp token from sso.
	 * @return bool|string
	 */
	public function get_permanent_oken( $temp_token ) {
		$payload = array( 'token' => $temp_token );
		// get perm token.
		$response = $this->post( 'signin/sso/trade', wp_json_encode( $payload ) );

		if ( ! empty( $response->message ) ) {
			// @todo Display this error message in a more formal way.
			echo '<br /><b>An error has occured:' . esc_html( $response->message ) . '</b><br /><p>Please contact support or try again later.</p>';
			return false;
		}

		if ( isset( $response->userId ) ) {
			update_option( 'parler_user_id', $response->userId );
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
	 */
	public function get_plugin_key( $domain ) {
		$payload = array( 'domain' => $domain );

		$response = $this->post( 'plugin/key', wp_json_encode( $payload ) );

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
	}

	/**
	 * Verify the domain is owned by the user.
	 *
	 * @param string $plugin_token The plugin token to verify.
	 */
	public function verify_plugin_key( $plugin_token ) {
		$payload = array( 'key' => $plugin_token );

		$response = $this->post( 'plugin/verify', wp_json_encode( $payload ) );

		return $response;
	}

	/**
	 * Get the response body.
	 *
	 * @since 1.0.0
	 * @param WP_Error|array $response The response.
	 * @return mixed The response body for the Parler API in a friendly format.
	 */
	private function get_response_body( $response ) {
		if ( is_wp_error( $response ) ) {
			$error_msg          = $response->get_error_message();
			$response           = new StdClass();
			$response->errorMsg = $error_msg;
		} else {
			$response = wp_json_encode( $response['body'] );
		}

		return $response;
	}

	/**
	 * Get the host of the parler API based on env.
	 *
	 * @return string The API url.
	 */
	private function get_parler_host() {
		// @todo A dev may want to change this to point at local
		if ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
			return self::STAGING_PARLER_HOST;
		}
		return self::PROD_PARLER_HOST;
	}

	/**
	 * Set the secret token used for auth.
	 *
	 * @param string $secret_key The secret key to auth with against the Parler API.
	 */
	public function set_secret_key( $secret_key ) {
		$this->secret_key = $secret_key;
	}
}
