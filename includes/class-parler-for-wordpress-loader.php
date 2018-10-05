<?php

// @todo refactor this into a class and replace the auth link with perm token
function post_published_notification($ID, $post)
{
//    $PARLER_POST_SERVER = 'https://par.pw/';
    $PARLER_POST_SERVER = 'https://staging.par.pw/'; // staging
//    $PARLER_POST_SERVER = 'http://localhost:3000/'; // localhost
    $PARLER_POST_PATH = 'v1/post/retroactive';

    $title = $post->post_title;
    $permalink = get_permalink($ID);
    $excerpt = get_the_excerpt($post);
    $post_date = get_the_date("YmdHis", $ID);
    $url = $PARLER_POST_SERVER.$PARLER_POST_PATH;

    $response = wp_remote_post($url,
        array(
            'headers' => array(
                'Authorization' => '6xpc91LdOa7ryusy583gMEFsuzNzysDdm8aF6yk6PSg05TQxM8LqLVknWxNKzp0i6d1Hj7TxfCF3cvQIM87IAR7ApExEe3mCJE3QvJt5a1d3VTpHzN0NPSxsHzHCBaaa'
                // This code is for localhost, @todo make this dynamic
            ),
            'title' => $title,
            'excerpt' => $excerpt,
            'permalink' => $permalink,
            'createdAt' => $post_date
        )
    );

}
// END TODO

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @link       https://parler.com
 * @since      1.0.0
 *
 * @package    Parler_For_Wordpress
 * @subpackage Parler_For_Wordpress/includes
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */
class Parler_For_WordpressLoader
{

    /**
     * The array of actions registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->actions = array();
        $this->filters = array();

        add_action('publish_post', 'post_published_notification', 10, 2);

    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @since    1.0.0
     * @param    string $hook The name of the WordPress action that is being registered.
     * @param    object $component A reference to the instance of the object on which the action is defined.
     * @param    string $callback The name of the function definition on the $component.
     * @param    int $priority Optional. The priority at which the function should be fired. Default is 10.
     * @param    int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);

    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @since    1.0.0
     * @param    string $hook The name of the WordPress filter that is being registered.
     * @param    object $component A reference to the instance of the object on which the filter is defined.
     * @param    string $callback The name of the function definition on the $component.
     * @param    int $priority Optional. The priority at which the function should be fired. Default is 10.
     * @param    int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @since    1.0.0
     * @access   private
     * @param    array $hooks The collection of hooks that is being registered (that is, actions or filters).
     * @param    string $hook The name of the WordPress filter that is being registered.
     * @param    object $component A reference to the instance of the object on which the filter is defined.
     * @param    string $callback The name of the function definition on the $component.
     * @param    int $priority The priority at which the function should be fired.
     * @param    int $accepted_args The number of arguments that should be passed to the $callback.
     * @return   array                                  The collection of actions and filters registered with WordPress.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args)
    {

        $hooks[] = array(
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;

    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {

        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

    }

}
