<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Parler_For_WordPress_Admin
 */

/**
 * Defines the admin-facing functionality of the Parler WP Plugin.
 */
class Parler_For_WordPress_Admin {


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
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->load_components();
	}

	/**
	 * Loading components.
	 */
	private function load_components() {
		/**
		 * Register the widget.
		 */
		function register_parler_widget() {
			register_widget( 'Parler_For_WordPress_Widget' );
		}

		add_action( 'widgets_init', 'register_parler_widget' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook The hook name.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		// Only load this for our plugin page.
		if ( $hook != 'toplevel_page_parler' ) {
			return;
		}

		if ( defined( 'PARLER4WP_REACT_CSS' ) ) {
			wp_enqueue_style( $this->plugin_name, PARLER4WP_REACT_CSS, array(), $this->version, 'all' );
		} else if ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
            wp_enqueue_style( $this->plugin_name, 'https://plugin.parler.com/staging/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all' );
        } else {
			wp_enqueue_style( $this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all' );
		}
		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/parler-for-wordpress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $hook The hook name.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		// Only load this for our plugin page.
		if ( $hook != 'toplevel_page_parler' ) {
			return;
		}

		if ( defined( 'PARLER4WP_REACT_JS' ) ) {
			wp_enqueue_script( $this->plugin_name, PARLER4WP_REACT_JS, array( 'jquery' ), $this->version, false );
		} else if ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
			wp_enqueue_script( $this->plugin_name, 'https://plugin.parler.com/staging/parler-for-wordpress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
        } else {
			wp_enqueue_script( $this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync', array( 'jquery' ), $this->version, false );
		}

		wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'js/parler-for-wordpress-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add admin menu for Parler
	 *
	 * @param $wp_admin_bar
	 *
	 * @since    1.0.0
	 *
	 * @param    WP_Admin_Bar $wp_admin_bar Instance of the WP_Admin_Bar.
	 */
	public function parley_add_admin_menu() {
		if ( ! current_user_can( 'moderate_comments' ) ) {
			return;
		}

		// Remove comments from menu since Parler stores it for you.
		remove_menu_page( 'edit-comments.php' );

		// Add options page for Parler in settings.
		add_menu_page( 'Parler Settings', 'Parler', 'manage_options', 'parler', array(
			__CLASS__,
			'parler_options_page'
		), 'dashicons-testimonial', 24 );

		// Uncomment to move menu item into options.
		// add_options_page('Parler Admin Settings', 'Parler', 'manage_options', 'parler', array(__CLASS__, 'parler_options_page'));
	}

	/**
	 * Add settings page for Parler
	 *
	 * @since    1.0.0
	 */
	public function parley_register_settings_page() {
		Parler_For_WordPress_Activator::activate();
	}

	/**
	 * @param null $url_params The url params in k=v array format or string with ampersands already placed &k=v&k2=data
	 */
	public static function redirect_javascript_response( $url_params = null ) {
		$params = '';
		if ( is_array( $url_params ) ) {
			foreach ( $url_params as $key => $value ) {
				$params .= "&{$key}={$value}";
			}
		}
		if ( is_string( $url_params ) ) {
			$params = $url_params;
		}
		echo '<script> window.location="?page=parler' . $params . '"; </script>';
	}

	/**
	 * Parler options page with tabs.
	 * @todo refactor this to not be so long.
	 */
	public static function parler_options_page() {

		// @todo isn't working to disable the added widget when default comments location is added back
		// if (get_option('parler_default_location')) {
		// function parler_deregister_widget() {
		// unregister_widget('Parler_Widget');
		// }
		// add_action( 'widgets_init', 'parler_deregister_widget', 99999 );
		// }
		//

		if ( get_option( 'parler_import_all_posts' ) && get_option( 'parler_import_all_posts' ) != 'complete' ) {
			echo '<br />Importing all posts...';
			self::background_import_process();
			update_option( 'parler_import_all_posts', 'complete' );
		}

		// If we need to reset integration and clear all settings
		if ( isset( $_GET['integration'] ) && $_GET['integration'] === 'clear' ) {
			Parler_For_WordPress_Deactivator::deactivate();
			echo '<br />Terminating Account...';
			self::redirect_javascript_response();
		}

		// Check if we need to show messaging
		if ( isset( $_GET['installation'] ) && $_GET['installation'] == 'complete' ) {
			echo '<br /><h3>Installation Complete!</h3>';
		} elseif ( isset( $_GET['installation'] ) && $_GET['installation'] == 'verify_failed' ) {
			echo '<br /><h3>Verification of your domain name has failed! <br /></h3>';
		}

		if ( isset( $_GET['error'] ) ) {
			echo '<br />An unknown error occurred.<br />';
			if ( PARLER4WP_ENV === 'DEV' ) {
				echo $_GET['error'];
			}
		}

		// See if we already have the secret stored
		$plugin_key = get_option( 'parler_plugin_token' );
		// Setup active tab option
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		if ( ! $plugin_key ) {
			$active_tab = 'setup';
		}

		$temp_token = isset($_GET['temp_token']) ? $_GET['temp_token'] : null;
		if ($temp_token && !$plugin_key) {
			// Lets get a permanent token and a plugin key
			$parler_api_service = new Parler_Api_Service();
			// Get perm token
			$secret_key = $parler_api_service->get_permanent_token($temp_token);
			if ($secret_key) {
				$parler_api_service->set_secret_key($secret_key);
				// Get domain name
				$domain_name = $_SERVER['SERVER_NAME']; // Not sure if this will always be set
				$hash_pass = $parler_api_service->get_plugin_key($domain_name);
				$verification_file = '/parler-domain.txt';
				$fp = fopen($_SERVER['DOCUMENT_ROOT'] . $verification_file, 'wb');

				if ($hash_pass instanceof \stdClass && !empty($hash_pass->message)) {
					echo "<br /><!-- Get Plugin Key --><h3>" . $hash_pass->message . "</h3><br />";
				} else if ($fp) {
					fwrite($fp, $hash_pass);
					fclose($fp);
					$verificationResponse = $parler_api_service->verify_plugin_key(get_option('parler_plugin_token'));
					echo '<br/>';
					if (!empty($verificationResponse->message)) {
						$cssAlert = 'warning';
						if ($parler_api_service->get_last_response_code() == 200) {
							$cssAlert = 'success';
						}
						echo sprintf('<div class="alert alert-%s">%s</div>', esc_html($cssAlert), esc_html($verificationResponse->message));
					} else if ($parler_api_service->get_last_response_code() == 400) {
						echo '<div class="alert alert-warning">An error has occured during domain validation.</div>';
					} else if ($parler_api_service->get_last_response_code() == 200) {
						echo '<div class="alert alert-success">Installation Complete!</div>';
					} else {
						echo '<div class="alert alert-warning">Domain verification has failed.</div>';
					}
					// Refresh token in-case this was previously verified as token was returned with message
					$plugin_key = get_option('parler_plugin_token');
				} else {
					echo "<br /><h3>An error occurred when trying to save the verification file to <b>$verification_file</b></h3><br />";
					echo "<h4>Please save the following access key into <b>$verification_file</b></h4><br />";
					echo "<div><p>File Contents: </p></div><b>$hash_pass</b>";
					echo "<br /><p><a href='?page=parler&verify=plugin'>Click here</a> once the file is saved and can be <a href='$verification_file'>viewed</a></p>";
				}
			}
		} elseif ($temp_token && $plugin_key) {
			echo '<br />An API Plugin Token already exists for this site. If you wish to refresh your install you may click the terminate integration link at the bottom of the setup tab.';
		}

        // Set the parler-display-settings url based on environment
        if ( defined( 'PARLER4WP_SSO_URI' ) ) {
            $sso_url = PARLER4WP_SSO_URI;
        } else if ( PARLER4WP_ENV === 'DEV' || PARLER4WP_ENV === 'STAGING' ) {
            $sso_url = 'https://sso.staging.parler.com/?source=';
        } else {
            $sso_url = 'https://sso.parler.com/?source=';
        }

		// VIEWS
		?>
        <div class="wrap">
            <div style="display: inline-block;">
                <img style="float: left;" width="42" height="42" src="<?php echo plugin_dir_url( __FILE__ ) . 'images/Parler_Logo.svg'; ?>" />
                <h1 style="float: left;"> &nbsp; Parler Settings</h1>
            </div>
            <h2 class="nav-tab-wrapper">
                <a href="?page=parler&tab=setup"
                   class="nav-tab <?php echo $active_tab == 'setup' ? 'nav-tab-active' : ''; ?>">Setup</a>

				<?php if ( $plugin_key ) { ?>

                    <a href="?page=parler&tab=general"
                       class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
                    <a href="?page=parler&tab=import"
                       class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>">Import</a>
				<?php } ?>
            </h2>

			<?php if ( $active_tab == 'setup' ) {  // Setup Tab ?>

                <h2>Setup Parler</h2>

				<?php if ( $plugin_key ) { ?>

                    <p>Integration Completed</p>
                    <p>You can use the Parler Widget or leave it in the default comments section.<br />
                        Moderate comments at <a href="https://moderation.parler.com/">https://moderation.parler.com/</a><br />

                    </p>
                    <?php
                    // Check first if we have overrides before echoing anything out
                    $pp_width = get_option('parler_custom_width');
                    $pp_margin = get_option('parler_custom_margin');
                    $pp_padding = get_option('parler_custom_padding');
                    echo '<div id="comments" style=" ';
                    if ($pp_width) {
                        echo 'max-width: ' . $pp_width . '; ';
                    }
                    if ($pp_margin) {
                        echo 'margin: ' . $pp_margin . '; ';
                    }
                    if ($pp_padding) {
                        echo 'padding: ' . $pp_padding . ';';
                    }
                    echo '"></div>';
                    ?>
                    </div>
                    <br/>
                    <hr/>
                    <p>Click the link below only if you need to redo your integration key</p>
                    <a href="?page=parler&integration=clear"
                       onclick="return confirm('Are you sure you want to remove your API keys and terminate all plugin settings?');">Terminate
                        Integration</a>

					<?php
				} else {
					// Integration Incomplete
					$source_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					?>

                    <p>To activate the Parler Commenting System, simply login to Parler by clicking the "Sign In" button below.</p>
                    <div class="parler_button "><a href="<?php echo $sso_url . urlencode( $source_url ); ?>">
                            <button type="button" id="parler-signin-button" class="button button-primary"><span
                                        class="dashicons dashicons-lock"></span> Sign in to Parler
                            </button>
                        </a></div>
                    <br /><br />
                    <i>If you encounter problems while activating our plugin, please email <a href="mailto:Support@Parler.com">Support@Parler.com</a>.</i>

					<?php
				}
			} elseif ( $active_tab == 'general' ) { // General Tab
				?>

                <form method="post" action="options.php">
					<?php settings_fields( 'parler-settings' ); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">
                                <label title="Place comments in the default WordPress comments area location."
                                       for="parler_default_location">Default Comment Location</label><br/>
                                <p style="font-weight: normal;"></p></th>
                            <td><input title="Toggle Parler Comments" type="checkbox" name="parler_default_location" value="1"
									<?php checked( get_option( 'parler_default_location', true) ); ?>/>
                                <p><i> Disabled automatically when using Parler widget</i></p>
                            </td>
                        </tr>
                        <tr title="Only for main default location comments, these do not effect the widget." valign="top">
                            <th scope="row">
                                <b>CSS Styles</b>
                                <hr/>
                            </th>
                            <td>
                                <i>Some themes may cause issues with the way Parler renders.
                                    Use the settings below to override the CSS if needed.</i>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label title="Manually adjust the commenting sections maximum width."
                                       for="parler_custom_width">Custom Width</label>
                            </th>
                            <td><input title="Enter a custom width" type="text" name="parler_custom_width"
                                       value="<?php echo esc_attr( get_option( 'parler_custom_width') ); ?>"
                                       class="parler-text-entry"/>
                                <p><i>CSS Value for max-width. Ex: "80%" or "520px"</i></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label title="Manually adjust the commenting sections margin."
                                       for="parler_custom_margin">Custom Margin</label></th>
                            <td><input title="Enter a custom margin" type="text" name="parler_custom_margin"
                                       value="<?php echo esc_attr( get_option( 'parler_custom_margin') ); ?>"
                                       class="parler-text-entry"/>
                                <p><i>CSS Value for margin. Ex: "0 10%"</i></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label title="Manually adjust the commenting sections padding."
                                                   for="parler_custom_padding">Custom Padding</label></th>
                            <td><input title="Enter a custom padding" type="text" name="parler_custom_padding"
                                       value="<?php echo esc_attr( get_option( 'parler_custom_padding') ); ?>"
                                       class="parler-text-entry"/>
                                <p><i>CSS Value for padding. Ex: "0 60px"</i></p>
                            </td>
                        </tr>
                    </table>
					<?php submit_button(); ?>
                    <br /><br />
                    <i>If you encounter problems while using our plugin, please email <a href="mailto:Support@Parler.com">Support@Parler.com</a>.</i>
                </form>

				<?php
			} elseif ( $active_tab == 'import' ) { // Import Tab

				?>

                <form method="post" action="options.php">
					<?php
					settings_fields( 'parler-import-settings' );
					do_settings_sections( 'parler-import-settings' );
					?>
                    <br/>
                    <h3>Import all posts into Parler</h3>
					<?php
					if ( get_option( 'parler_import_all_posts' ) == 'complete' ) {
						echo '<p>You have already imported all posts. Any newly created posts will be synced automatically.</p>';
					} else { ?>
                        <p>Click the button below to sync all posts with our servers.</p>
                        <input type="hidden" name="parler_import_all_posts" value="1"/>
						<?php
						submit_button( 'Sync all posts and comments' );
					}
					?>
                </form>

				<?php

			} else {
				// Fail out of options
				// self::redirectJavascriptResponse(array('error' => 'Unknown Page'));
			}
			?>
        </div>
		<?php
	}


	/**
	 * Create a retroactive post.
	 *
	 * @param int $ID The post id.
	 * @param string $post The Post body.
	 *
	 * @return string|void
	 */
	public function parler_post_published_notification( $ID, $post ) {
		$token = get_option( 'parler_api_token', null );
		if ( ! $token ) {
			error_log( sprintf( 'Attempted to save post id %d without Parler integration setup yet.', $ID ) );

			return;
		}
		// If we do have a token, lets use it and create the post.
		$parler_api_service = new Parler_Api_Service( $token );
		$response           = $parler_api_service->create_retroactive_post( $ID, $post );

		return $response;
	}

	/**
	 * Background the process to import bulk posts.
	 */
	protected static function background_import_process() {
		$wp_import_process = new Parler_Import_Posts_Process();

		// @todo we might have to paginate these posts for large datasets
		$args       = array(
			'posts_per_page' => - 1,
			'post_type'      => 'post',
			'order'          => 'ASC',
            'post_status'    => 'publish' // Admin AJAX calls ask for all posts, lets just get published stuff.
		);
		$post_query = new WP_Query( $args );
		$total      = 0;
		if ( $post_query->have_posts() ) {
			while ( $post_query->have_posts() ) {
				// increment the post
				$post_query->the_post();
				$id = get_the_ID();
				$total ++;
				// echo "POST ID $id ".PHP_EOL;
				// pass this post to the queue
				$wp_import_process->push_to_queue( $id );
				// Dispatch them all
			}

			$response = $wp_import_process->save()->dispatch();

			echo "<br />Backgrounding import jobs for $total posts.";
		} else {
			// Insert any content or load a template for no posts found.
			echo '<br />No posts found!';
		}

		wp_reset_query();
	}

}
