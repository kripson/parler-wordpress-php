<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Parler_For_WordPress
 * @subpackage Parler_For_WordPress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Parler_For_WordPress
 * @subpackage Parler_For_WordPress/public
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */
class Parler_For_WordPress_Public {


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
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Parler_For_WordPressLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Parler_For_WordPressLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( PARLER4WP_ENV === 'DEV' ) {
			wp_enqueue_style( $this->plugin_name, '/wp-content/plugins/parler/public/css/parler-for-WordPress-public.css#parlerasync', array(), $this->version, 'all' );
		} elseif ( PARLER4WP_ENV === 'STAGING' ) {
			wp_enqueue_style( $this->plugin_name, 'https://plugin.parler.com/staging/parler-for-WordPress-public.css#parlerasync', array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->plugin_name, 'https://plugin.parler.com/production/parler-for-WordPress-public.css#parlerasync', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * Adds the async option to the javascript tags on parler js/css code
		 *
		 * @todo See if we really need this...
		 * @param string $url the url to see if async tags need to be thrown on.
		 * @return string
		 */
		function parler_async_scripts( $url ) {
			if ( strpos( $url, '#parlerasync' ) === false ) {
				return $url;
			} elseif ( is_admin() ) {
				return str_replace( '#parlerasync', '', $url );
			} else {
				return str_replace( '#parlerasync', '', $url ) . "' async='async";
			}
		}

		add_filter( 'clean_url', 'parler_async_scripts', 11, 1 );

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Parler_For_WordPressLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Parler_For_WordPressLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( PARLER4WP_ENV === 'DEV' ) {
			wp_enqueue_script( $this->plugin_name, '/wp-content/plugins/parler/public/js/parler-for-WordPress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
		} elseif ( PARLER4WP_ENV === 'STAGING' ) {
			wp_enqueue_script( $this->plugin_name, 'https://plugin.parler.com/staging/parler-for-WordPress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
		} else {
			wp_enqueue_script( $this->plugin_name, 'https://plugin.parler.com/production/parler-for-WordPress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
		}
	}

}
