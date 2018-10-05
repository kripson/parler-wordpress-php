<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://parler.com
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/includes
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */
class Parler_For_Wordpressi18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'parler-for-wordpress',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }


}
