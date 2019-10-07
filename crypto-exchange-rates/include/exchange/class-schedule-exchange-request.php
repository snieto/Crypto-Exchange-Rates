<?php

namespace CryptoExchangeRates\Exchange;


class ScheduleExchangeRequest {

	public function __construct() {
		add_filter('cron_schedules', [$this, 'custom_cron_schedules']);
		add_action( 'cryptoexchange_btc_rate_request_5min', [ $this, 'do_btc_rates_request' ] );

		if ( ! wp_next_scheduled( 'cryptoexchange_btc_rates_request_5min' ) ) {
			wp_schedule_event( time(), '5min', 'cryptoexchange_btc_rates_request_5min' );
		}
	}

	/**
	 * Make the calls to force the caching of the values managed by CryptoExchanger class
	 */
	public function do_btc_rates_request(){
		$cryptoExchanger = new CryptoExchanger( COINAPI_KEY );
		$btc_usd_rate    = $cryptoExchanger->get_exchange_rate( 'BTC', 'USD' );
		$btc_eur_rate    = $cryptoExchanger->get_exchange_rate( 'BTC', 'EUR' );
	}

	/**
	 * Custom cron schedule (5 minutes)
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public function custom_cron_schedules($schedules){
		if(!isset($schedules["5min"])){
			$schedules["5min"] = array(
				'interval' => 5*60,
				'display' => __('Once every 5 minutes'));
		}

		return $schedules;
	}

}
