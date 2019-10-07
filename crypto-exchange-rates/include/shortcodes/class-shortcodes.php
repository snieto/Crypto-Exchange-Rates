<?php

namespace CryptoExchangeRates\Frontend;

use CryptoExchangeRates\Exchange\CryptoExchanger;

class ShortCodes {

	public function __construct() {
		add_shortcode( 'cryptoexchange', [ $this, 'show_exchange_rate_shortcode' ] );
		add_shortcode( 'cryptoexchange_form', [ $this, 'show_exchange_rate_shortcode_form' ] );
		//TODO: A good idea is to improve the calculation form with a JS based
		add_shortcode( 'cryptoexchange_ajax_form', [ $this, 'show_exchange_rate_shortcode_ajax_form' ] );
	}

	/**
	 * Simple shortcode to show pair rate values
	 *
	 * @param array $atts
	 * @param null  $content
	 *
	 * @return mixed|void
	 */
	public function show_exchange_rate_shortcode( $atts = array(), $content = null ) {
		$base  = isset( $atts['base'] ) ? $atts['base'] : 'BTC';
		$quote = isset( $atts['quote'] ) ? $atts['quote'] : 'USD';

		if ( $base != 'BTC'
		     || ! in_array( $quote, [ 'EUR', 'USD' ] ) ) {
			return;
		}

		$cryptoExchanger = new CryptoExchanger( COINAPI_KEY );
		$rate            = $cryptoExchanger->get_exchange_rate( $base, $quote );

		return '1 ' . $base . ': ' . $rate . ' ' . $quote;
	}

	/**
	 * The shortcode that renders the form to make exchange rate calculations
	 *
	 * @param array $atts
	 * @param null  $content
	 *
	 * @return mixed|void
	 */
	public function show_exchange_rate_shortcode_form( $atts = array(), $content = null ) {
		$qty      = isset( $_REQUEST['qty'] ) ? sanitize_key( $_REQUEST['qty'] ) : 0;
		$pair     = isset( $_REQUEST['pair'] ) ? sanitize_key( $_REQUEST['pair'] ) : 'btc/usd';
		$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_key( $_REQUEST['_wpnonce'] ) : '';

		$cryptoExchanger = new CryptoExchanger( COINAPI_KEY );
		$btc_usd_rate    = $cryptoExchanger->get_exchange_rate( 'BTC', 'USD' );
		$btc_eur_rate    = $cryptoExchanger->get_exchange_rate( 'BTC', 'EUR' );

		$html = "";

		if ( ! empty( $qty )
		     && ! empty( $wp_nonce ) ) {

			$verified = wp_verify_nonce( $wp_nonce, 'cryptoexchange_form' );

			if ( $verified ) {
				switch ( $pair ) {
					case 'btcusd':
						$result   = number_format_i18n( $qty * $btc_usd_rate, 2 );
						$currency = '$';
						break;
					case 'btceur':
						$result   = number_format_i18n( $qty * $btc_eur_rate, 2 );
						$currency = '€';
						break;
					case 'usdbtc':
						$result   = number_format_i18n( $qty / $btc_usd_rate, 2 );
						$currency = 'BTC';
						break;
					case 'eurbtc':
						$result   = number_format_i18n( $qty / $btc_eur_rate, 2 );
						$currency = 'BTC';
						break;
				}
			}
		}
		$html .= '<form name="cryptoform" method="post" class="cryptoform">'
		         . '<h3>Select Pair and Input a quantity</h3>'
		         . '<input type="radio" name="pair" value="btcusd" ' . ( $pair == 'btcusd' ? 'checked="checked"' : '' ) . '> BTC / USD'
		         . '<input type = "radio" name = "pair" value = "btceur" ' . ( $pair == 'btceur' ? 'checked="checked"' : '' ) . '> BTC / EUR<br > '
		         . '<input type = "radio" name = "pair" value = "usdbtc" ' . ( $pair == 'usdbtc' ? 'checked="checked"' : '' ) . '> USD / BTC'
		         . '<input type = "radio" name = "pair" value = "eurbtc" ' . ( $pair == 'eurbtc' ? 'checked="checked"' : '' ) . '> EUR / BTC<br > '
		         . '<input type = "text" name = "qty" value = "' . $qty . '" > '
		         . '<input type = "submit" name = "submit" value = "Calc" > '
		         . wp_nonce_field( 'cryptoexchange_form', '_wpnonce', true, false )
		         . ' </form > ';

		if ( ! empty( $result ) ) {
			$html .= '<h4>RESULT: ' . $result . $currency . '</h4>';
		}

		$html .= '<h3 > Cryptocurrencies Rates </h3 > '
		         . '<ul > '
		         . '<li > BTC / USD: ' . $btc_usd_rate . ' </li > '
		         . '<li > BTC / EUR: ' . $btc_eur_rate . ' </li > '
		         . '</ul > ';

		return $html;
	}

	/**
	 * @param array $atts
	 * @param null  $content
	 *
	 * @return mixed|void
	 */
	public function show_exchange_rate_shortcode_ajax_form( $atts = array(), $content = null ) {

	}
}