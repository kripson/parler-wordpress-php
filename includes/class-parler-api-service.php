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
    public function __construct($secret_key)
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
     * @param  array $requestParams The params to be added to the body.
     * @return mixed The response data.
     */
    public function post($urlPath, $requestParams)
    {
        $apiUri = $this->getParlerHost() . $urlPath;
        $parlerResponse = wp_remote_post($apiUri, array(
            'body' => $requestParams,
            'headers' => array(
                'Authorization' => $this->secret_key,
            ),
            'method' => 'POST',
        ));

        return $this->get_response_body($parlerResponse);
    }

    /**
     * @param string $domain The domain to verify
     */
    public function getPluginKey($domain)
    {
        $payload = array('domain' => $domain);
        var_dump($this->post('plugin/key', json_encode($payload)));
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
}
