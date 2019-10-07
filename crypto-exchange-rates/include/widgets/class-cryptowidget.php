<?php

namespace CryptoExchangeRates\Frontend;


class CryptoWidget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			// Base ID of your widget
			'cryptoexchange_widget',

			// Widget name will appear in UI
			__('CryptoExchange Widget', 'cryptoexchange_widget_domain'),

			// Widget description
			array( 'description' => __( 'Sample widget for showing btc/usdandbtc/eur exchangerate', 'cryptoexchange_widget_domain' ), )
		);
//		add_action( 'widgets_init', [ $this, 'load_widget' ] );
	}

	// Create the function to output the contents of our Dashboard Widget.

	function example_dashboard_widget_function() {

		// Callback here

	}

	/**
	 * Render the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the output
		echo __( 'CRYTPO RATES', 'cryptoexchange_widget_domain' );

		$cryptoExchanger = new \CryptoExchangeRates\Exchange\CryptoExchanger( COINAPI_KEY );
		$btc_usd_rate = $cryptoExchanger->get_exchange_rate('BTC', 'USD');
		$btc_eur_rate = $cryptoExchanger->get_exchange_rate('BTC', 'EUR');

		echo '<ul>';
		echo '<li>BTC/USD: '. $btc_usd_rate .'</li>';
		echo '<li>BTC/EUR: '. $btc_eur_rate .'</li>';
		echo '</ul>';

		echo $args['after_widget'];
	}

	/**
	 * Controls the widget form
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'BTC Pairs', 'cryptoexchange_widget_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}