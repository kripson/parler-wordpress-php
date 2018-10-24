<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://parler.com
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/admin
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */
class Parler_For_WordpressAdmin
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
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->load_classes();
    }

    private function load_classes()
    {
        require_once plugin_dir_path(__FILE__) . '../includes/class-parler-api-service.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {
        // Only load this for our plugin page
        if ($hook != 'toplevel_page_parler') {
            return;
        }

        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            wp_enqueue_style($this->plugin_name, '/wp-content/plugins/parler/public/css/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all');
        } else if (PARLER_FOR_WORDPRESS_ENV === 'STAGING') {
            wp_enqueue_style($this->plugin_name, 'https://plugin.parler.com/staging/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all');
        } else {
            wp_enqueue_style($this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.css#parlerasync', array(), $this->version, 'all');
        }

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/parler-for-wordpress-admin.css#parlerasync', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        // Only load this for our plugin page
        if ($hook != 'toplevel_page_parler') {
            return;
        }

        if (PARLER_FOR_WORDPRESS_ENV === 'DEV') {
            wp_enqueue_script($this->plugin_name, '/wp-content/plugins/parler/public/js/parler-for-wordpress-public.js#parlerasync', array('jquery'), $this->version, false);
        } else if (PARLER_FOR_WORDPRESS_ENV === 'STAGING') {
            wp_enqueue_script($this->plugin_name, 'https://plugin.parler.com/staging/parler-for-wordpress-public.js#parlerasync', array('jquery'), $this->version, false);
        } else {
            wp_enqueue_script($this->plugin_name, 'https://plugin.parler.com/production/parler-for-wordpress-public.js#parlerasync', array('jquery'), $this->version, false);
        }


        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/parler-for-wordpress-admin.js', array('jquery'), $this->version, false);
    }

    /**
     * Add admin menu for Parler
     *
     * @param $wp_admin_bar
     * @since    1.0.0
     * @param    WP_Admin_Bar $wp_admin_bar Instance of the WP_Admin_Bar.
     */
    public function parley_add_admin_menu()
    {
        if (!current_user_can('moderate_comments')) {
            return;
        }

        // Remove comments from menu since Parler stores it for you.
        remove_menu_page('edit-comments.php');

        // Add options page for Parler in settings
//        add_options_page('Parler Admin Settings', 'Parler', 'manage_options', 'parler', array(__CLASS__, 'parler_options_page'));
        add_menu_page('Parler Settings', 'Parler', 'manage_options', 'parler', array(__CLASS__, 'parler_options_page'), 'dashicons-testimonial', 24);

    }

    /**
     * Add settings page for Parler
     *
     * @since    1.0.0
     */
    public function parley_register_settings_page()
    {
        if (!current_user_can('moderate_comments')) {
            return;
        }

        /* * * Register Settings * * */

        // Parler Integration Settings
        register_setting('parler-api-settings', 'parler_api_token', array('default' => null));
        register_setting('parler-api-settings', 'parler_user_id', array('default' => null));
        register_setting('parler-api-settings', 'parler_username', array('default' => null));
        register_setting('parler-api-settings', 'parler_profile_name', array('default' => null));

        // Plugin Settings for Parler
        register_setting('parler-plugin-settings', 'parler_plugin_token', array('default' => null));
        register_setting('parler-plguin-settings', 'parler_plugin_domain', array('default' => null));
        register_setting('parler-plugin-settings', 'parler_plugin_hash', array('default' => null));

        // Display Settings
        register_setting('parler-settings', 'parler_enabled', array('default' => false));
        register_setting('parler-settings', 'parler_custom_width', array('default' => '480px'));
        
        // Import all posts
        register_setting('parler-import-settings', 'parler_import_all_posts', array('default' => null));
    }

    /**
     * @param null $urlParams The url params in k=v array format or string with ampersands already placed &k=v&k2=data
     */
    public function redirectJavascriptResponse($urlParams = null)
    {
        $params = '';
        if (is_array($urlParams)) {
            foreach($urlParams as $key => $value) {
                $params .= "&{$key}={$value}";
            }
        }
        if (is_string($urlParams)) {
            $params = $urlParams;
        }
        echo '<script> window.location="?page=parler' . $params . '"; </script>';
    }

    public function parler_options_page()
    {
        if (get_option('parler_import_all_posts')) {
            update_option('parler_import_all_posts', null);
            // self::redirectJavascriptResponse();
            echo "Importing all posts...";
            // exit();
        }

        // If we need to reset integration and clear all settings
        if (isset($_GET['integration']) && $_GET['integration'] === 'clear') {
            update_option('parler_api_token', null);
            update_option('parler_user_id', null);
            update_option('parler_username', null);
            update_option('parler_profile_name', null);
            update_option('parler_plugin_token', null);
            update_option('parler_plugin_domain', null);
            update_option('parler_plugin_hash', null);
            update_option('parler_import_all_posts', null);
            self::redirectJavascriptResponse();
            // exit();
            echo "Terminating Account...";
//            exit();
        }

        // Check if we need to show messaging
        if (isset($_GET['installation']) && $_GET['installation'] == 'complete') {
                echo "<br /><h3>Installation Complete!</h3>";
        } else if (isset($_GET['installation']) && $_GET['installation'] == 'verify_failed') {
            echo "<br /><h3>Verification of your domain name has failed! <br /></h3>";
            if (PARLER_FOR_WORDPRESS_ENV === 'DEV')
            {
                echo "You now have a key for development only.";
            }
        }

        if (isset($_GET['error'])) {

            echo "An unknown error occured.<br />";
            if (PARLER_FOR_WORDPRESS_ENV === 'DEV')
            {
                echo $_GET['error'];
            }
        }

        // See if we already have the secret stored
        $secretKey = get_option('parler_api_token');
        // Setup active tab option
        $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        if (!$secretKey) {
            $activeTab = 'setup';
        }

        // var_dump($tempToken);
        // var_dump($secretKey);

        $tempToken = isset($_GET['temp_token']) ? $_GET['temp_token'] : null;
        if ($tempToken && !$secretKey) {
            // Lets get a permanent token and a plugin key
            $parlerApiService = new Parler_Api_Service();
            // Get perm token
            $permSecretKey = $parlerApiService->getPermToken($tempToken);
            if ($permSecretKey) {
                $parlerApiService->setSecretKey($permSecretKey);
                // Get domain name
                $domainName = $_SERVER['SERVER_NAME']; // Not sure if this will always be set
                $hashPass = $parlerApiService->getPluginKey($domainName);

                $verificationFile = "/parler-domain.txt";
                $fp = fopen($_SERVER['DOCUMENT_ROOT'] . $verificationFile, "wb");

                if ($fp) {
                    fwrite($fp, $hashPass);
                    fclose($fp);
                    $success = $parlerApiService->verifyPluginKey($hashPass);
                    if ($success) {
                        echo "Installation Complete...";
                        // exit();
            
                        // self::redirectJavascriptResponse(array('installation' => 'complete'));
                    } else {
                        echo "Domain verification failed...";
                        // exit();
                        $secretKey = $hashPass;
            
                        // self::redirectJavascriptResponse(array('installation' => 'verify_failed'));
                    }
                } else {
                    echo "<br /><h3>An error occured when trying to save the verification file to <b>$verificationFile</b></h3><br />";
                    echo "<h4>Please save the following access key into <b>$verificationFile</b></h4><br />";
                    echo "<div><p>File Contents: </p></div><b>$hashPass</b>";
                    echo "<br /><p><a href='?page=parler&verify=plugin'>Click here</a> once the file is saved and can be <a href='$verificationFile'>viewed</a></p>";
                }

            }
        } else if ($tempToken && $secretKey) {
            echo "<br />Sorry but a permanant token already exists for this site. Please terminate your integration if you want to remove your key and get a new one.";
        }
        // Set the parler-display-settings url based on envrionment
        if (PARLER_FOR_WORDPRESS_ENV === 'DEV' || PARLER_FOR_WORDPRESS_ENV === 'STAGING') {
            $ssoUrl = 'https://sso.staging.parler.com/?source=';
        } else {
            $ssoUrl = 'https://sso.parler.com/?source=';
        }
        ////// VIEWS
        ?>
        <div class="wrap">
            <div style="display: inline-block;">
                <img style="float: left;" src="https://home.parler.com/wp-content/uploads/2018/06/logo_431.png" width="42" height="42" />
                <h1 style="float: left;" > &nbsp; Parler Settings</h1>
            </div>
            <h2 class="nav-tab-wrapper">
                <a href="?page=parler&tab=setup"
                   class="nav-tab <?php echo $activeTab == 'setup' ? 'nav-tab-active' : ''; ?>">Setup</a>
                
                   <?php if ($secretKey) { ?>

                    <a href="?page=parler&tab=general"
                       class="nav-tab <?php echo $activeTab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
                    <a href="?page=parler&tab=import"
                       class="nav-tab <?php echo $activeTab == 'import' ? 'nav-tab-active' : ''; ?>">Import</a>
                <?php } ?>
            </h2>
            
            <?php if ($activeTab == 'setup') { ?>
                
                <h2>Setup Parler</h2>
                
                <?php if ($secretKey) { ?>

                    <p>Integration Completed</p>
                    <div style="max-width: <?php echo esc_attr(get_option('parler_custom_width')); ?>">
                        <div id="comments"></div>
                    </div>
                    <br/>
                    <hr/>
                    <p>Click the link below only if you need to redo your integration key</p>
                    <a href="?page=parler&integration=clear"
                       onclick="return confirm('Are you sure you want to remove your API keys?');">Terminate
                        Integration</a>
                
                <?php } else {

                    // Integration Incomplete
                    $sourceUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                    ?>

                    <p>To activate the Parler Commenting System, simply click on Login button below.</p>
                    <a href="<?php echo $ssoUrl . urlencode($sourceUrl); ?>">
                        Sign in to Parler
                    </a>

                <?php }

            } else if ($activeTab == 'general') { // General Tab ?>

                <form method="post" action="options.php"><?php
                    settings_fields('parler-settings');
                    do_settings_sections('parler-settings');
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><label for="parler_enabled">Default Comment Location</label><br/>
                                <p style="font-weight: normal;">Place comments in the default wordpress comments
                                    location.</p></th>
                            <td><input title="Toggle Parler Comments" type="checkbox" name="parler_enabled"
                                       value="1" 
                                    <?php checked(get_option('parler_enabled'), 1); ?>/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label for="parler_custom_width">Custom Width</label><br/>
                                <p style="font-weight: normal;">Manually adjust the commenting sections maximum
                                    width.
                                    Leave empty for fluid width.</p></th>
                            <td><input title="Enter a custom width" type="text" name="parler_custom_width"
                                       value="<?php echo esc_attr(get_option('parler_custom_width')); ?>"
                                       class="parler-text-entry"/>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form> 
                
                <?php
            } else if ($activeTab == 'import') {

                ?>

                <form method="post" action="options.php"><?php
                    settings_fields('parler-import-settings');
                    do_settings_sections('parler-import-settings');
                    ?>
                    <br />
                    <h3>Import all posts into Parler</h3> 
                    <p>Click the button below to sync all posts with our servers.</p>
                    <input type="hidden" name="parler_import_all_posts" value="1" />
                    <?php submit_button('Sync all posts and comments'); ?>
                </form> 

                <?php

            } else {
                // Fail out of options
//                self::redirectJavascriptResponse(array('error' => 'Unknown Page'));
            }
            ?>
        </div>
    <?php }
}
