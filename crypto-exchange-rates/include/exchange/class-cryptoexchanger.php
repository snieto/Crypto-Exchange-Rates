<?php

namespace CryptoExchangeRates\Exchange;

use \CryptoExchangeRates\CoinAPI\CoinAPI;

class CryptoExchanger {

	private static $coinAPI;

	public function __construct( $coinApiKey ) {
		self::$coinAPI = new CoinAPI( $coinApiKey );
	}

	/**
	 * Retrieves the rates for the selected pair
	 *
	 * @param $base base currency
	 * @param $quote quote currency
	 *
	 * @return float|mixed|void
	 */
	public function get_exchange_rate( $base, $quote ) {
		$cache_key = COINAPI_CACHE_KEY_PREFIX . $base . '-' . $quote;
		$rate      = get_transient( $cache_key );

		if ( false === $rate ) {
			try{
				$exchangeRate = self::$coinAPI->GetExchangeRate( $base, $quote );
				$rate         = $exchangeRate->rate;
				set_transient( $cache_key, $rate, COINAPI_CACHE_EXPIRE_TIME );
				update_option( $cache_key, $rate );
			}catch (\Exception $e){
				//Fallback in case of API limit exceeded or another kind of error
				$rate = get_option($cache_key);

				//Send mail to be aware of the problem
				wp_mail('sergio@snieto.com', 'Problem with CoinAPI and '.get_bloginfo('url'), serialize($e));
			}
		}

		return $rate;
	}
}