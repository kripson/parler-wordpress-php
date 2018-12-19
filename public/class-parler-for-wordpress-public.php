<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://home.parler.com/
 * @since      1.0.0
 *
 * @package    Parler_For_WordPress_Public*
 */

/**
 * Defines the everything for the public-facing Parler WP Plugin.
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
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Handles the comments template.
	 *
	 * @return string The comments template location.
	 */
	public function comments_template() {
		return dirname( __FILE__ ) . '/../includes/comments-template.php';
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

		if ( defined( 'PARLER4WP_REACT_CSS' ) ) {
			wp_enqueue_style( $this->plugin_name, PARLER4WP_REACT_CSS, array(), $this->version, 'all' );
		} elseif ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
			wp_enqueue_style( $this->plugin_name, 'https://plugin.parler.com/staging/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all' );
		}

		/**
		 * Remove comments section code
		 */
		// Kill the comments template.
		add_filter( 'comments_template', array( $this, 'comments_template' ), 20 );
		// Remove comment-reply script for themes that include it indiscriminately.
		wp_deregister_script( 'comment-reply' );
		// feed_links_extra inserts a comments RSS link.
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		if ( defined( 'PARLER4WP_REACT_JS' ) ) {
			wp_enqueue_script( $this->plugin_name, PARLER4WP_REACT_JS, array( 'jquery' ), $this->version, false );
		} elseif ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
			wp_enqueue_script( $this->plugin_name, 'https://plugin.parler.com/staging/parler-for-wordpress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
		} else {
			wp_enqueue_script( $this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
		}
	}

}
