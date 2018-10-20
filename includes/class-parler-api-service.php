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
class Parler_Api_Service
{

    const STAGING_PARLER_HOST = 'https://staging.par.pw/v1/';
    const PROD_PARLER_HOST = 'https://par.pw/v1/';

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
    public function __construct($secret_key = null)
    {
        $this->secret_key = $secret_key;
    }

    /**
     * Makes a GET request to the Parler API.
     *
     * @since  1.0.0
     * @param  string $urlPath The Parler endpoint to perform a GET request to.
     * @param  array $requestParams The params to be appended to the query.
     * @return mixed The response data.
     */
    public function get($urlPath, $requestParams)
    {
        $apiUri = $this->getParlerHost() . $urlPath;

        foreach ($requestParams as $key => $values_array) {
            if (!is_array($values_array)) {
                $values_array = array($values_array);
            }

            foreach ($values_array as $value) {
                $apiUri .= '&' . $key . '=' . urlencode($value);
            }
        }

        $parlerResponse = wp_remote_get($apiUri, array(
            'headers' => array(
                'Authorization' => $this->secret_key,
            ),
        ));

        return $this->get_response_body($parlerResponse);
    }

    /**
     * Makes a POST request to the Parler API.
     *
     * @since  1.0.0
     * @param  string $urlPath The Parler API endpoint to perform a POST request.
     * @param  array $originalParams The params to be added to the body.
     * @return mixed The response data.
     */
    public function post($urlPath, $originalParams)
    {
        // If we already authenticated with a perm token
        if ($this->secret_key) {
            $headers = array(
                'Authorization' => $this->secret_key,
            );
        } else {
            $headers = array();
        }

        $requestParams = array(
            'body' => $originalParams,
            'method' => 'POST',
            'headers' => $headers
        );

        $requestParams = array_merge($headers, $requestParams);

//        var_dump($requestParams);
        $apiUri = $this->getParlerHost() . $urlPath;
        $parlerResponse = wp_remote_post($apiUri, $requestParams);

        return $this->get_response_body($parlerResponse);
    }

    /**
     * @param string $tempToken The temp token from sso
     * @return bool|string
     */
    public function getPermToken($tempToken)
    {
        $payload = array('token' => $tempToken);
        // get perm token
        $response = $this->post('signin/sso/trade', json_encode($payload));

        if (!empty($response->message)) {
            // @todo Display this error message in a more formal way
            echo "<br /><b>ERROR: {$response->message}</b><br />Please try again.";
            return false;
        }

//        var_dump($response);
        if (isset($response->userId)) {
            update_option('parler_user_id', $response->userId);
        }
        if (isset($response->username)) {
            update_option('parler_username', $response->username);
        }
        if (isset($response->name)) {
            update_option('parler_profile_name', $response->name);
        }
        if (isset($response->token)) {
            update_option('parler_api_token', $response->token);
            return $response->token;
        }
        return false;
    }

    /**
     * @param string $domain The domain to verify
     */
    public function getPluginKey($domain)
    {
        $payload = array('domain' => $domain);
        // get perm token
        $response = $this->post('plugin/key', json_encode($payload));

//        var_dump($response);
        if (isset($response->token)) {
            update_option('parler_plugin_token', $response->token);
        }
        if (isset($response->domain)) {
            update_option('parler_plugin_domain', $response->domain);
        }
        if (isset($response->hash)) {
            update_option('parler_plugin_hash', $response->hash);
            return $response->hash;
        }
    }

    /**
     * @since 1.0.0
     * @param WP_Error|array $response The response.
     * @return mixed The response body for the Parler API in a friendly format.
     */
    private function get_response_body($response)
    {
        if (is_wp_error($response)) {
            $errMsg = $response->get_error_message();
            $response = new StdClass();
            $response->errorMsg = $errMsg;
        } else {
            $response = json_decode($response['body']);
        }

        return $response;
    }

    /**
     * @return string
     */
    private function getParlerHost()
    {
        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            return Parler_Api_Service::STAGING_PARLER_HOST;
        }
        return Parler_Api_Service::PROD_PARLER_HOST;
    }

    public function setSecretKey($secret_key)
    {
        $this->secret_key = $secret_key;
    }
}
