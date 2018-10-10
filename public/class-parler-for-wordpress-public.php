<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/public
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */
class Parler_For_WordpressPublic
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Parler_For_WordpressLoader as all of the hooks are defined
         * in that particular class.
         *
         * The Parler_For_WordpressLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            wp_enqueue_style($this->plugin_name, '/wp-content/plugins/parler/public/css/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all');
        } else {
            wp_enqueue_style($this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        function parler_async_scripts($url)
        {
            if ( strpos( $url, '#parlerasync') === false )
                return $url;
            else if ( is_admin() )
                return str_replace( '#parlerasync', '', $url );
            else
                return str_replace( '#parlerasync', '', $url )."' async='async";
        }

        add_filter( 'clean_url', 'parler_async_scripts', 11, 1 );


        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Parler_For_WordpressLoader as all of the hooks are defined
         * in that particular class.
         *
         * The Parler_For_WordpressLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            wp_enqueue_script($this->plugin_name, '/wp-content/plugins/parler/public/js/parler-for-wordpress-public.js#parlerasync', array('jquery'), $this->version, false);
        } else {
            wp_enqueue_script($this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync', array('jquery'), $this->version, false);
        }
    }

}
