<?php
/**
 * The widget-specific functionality of the plugin.
 *
 * @link       https://parler.com
 * @since      1.0.0
 *
 * @package    Parler_For_WordPress
 * @subpackage Parler_For_WordPress/widget
 * @author     Joshua Copeland <Josh@RemoteDevForce.com>
 */

/**
 * Class Parler_For_WordPress_Widget
 */
class Parler_For_WordPress_Widget extends WP_Widget {


	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'parler_widget',
			'description' => 'The Parler Comments Plugin Widget',
		);
		parent::__construct( 'parler_widget', 'Parler Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args The widget prebuilt arguments.
	 * @param array $instance The instance configs.
	 */
	public function widget( $args, $instance ) {
		echo esc_html( $args['before_widget'] );
		echo esc_html( $args['before_title'] . 'Parler' . $args['after_title'] );
		echo "<div style='padding-top: 0em; max-width: " . esc_attr( $instance['width'] ) . "' id='comments'></div>";
		echo esc_html( $args['after_widget'] );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		$width = ! empty( $instance['width'] ) ? $instance['width'] : esc_html__( '300px', 'width' );
		?>
		<i>Using this widget will disable the default comments section.</i>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_attr_e( 'Width:', 'width' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="text"
				value="<?php echo esc_attr( $width ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		update_option( 'parler_default_location', 0 );
		$instance          = array();
		$instance['width'] = ( ! empty( $new_instance['width'] ) ) ? sanitize_text_field( $new_instance['width'] ) : '';

		return $instance;
	}
}
