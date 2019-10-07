<?php
/**
 * Plugin Name:     Crypto Exchange Rates sng
 * Plugin URI:      https://snieto.com
 * Description:     This plugin is an example of a simple btc/eur and btc/usd exchange rate service
 * Author:          Sergio Nieto
 * Author URI:      https://snieto.com
 * Text Domain:     cryptoexchange-rates
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         CryptoExchange_Rates
 */

use CryptoExchangeRates\Admin\RewriteRules;
use CryptoExchangeRates\Frontend\ShortCodes;
use CryptoExchangeRates\Frontend\CryptoWidget;
use CryptoExchangeRates\Exchange\ScheduleExchangeRequest;

if ( ! defined( 'COINAPI_KEY' ) ) {
	define( 'COINAPI_KEY', '--REPLACEWITHAVALID_KEY--' );
}
define( 'COINAPI_CACHE_EXPIRE_TIME', 864 );
define( 'COINAPI_CACHE_KEY_PREFIX', 'cryptoexchange-rate-' );
define( 'COINAPI_CACHE_GROUP', 'crypto-exchange-rate' );

$slash = DIRECTORY_SEPARATOR;
require_once plugin_dir_path( __FILE__ ) . 'include' . $slash . 'admin' . $slash . 'class-rewrite-rules.php';
require_once plugin_dir_path( __FILE__ ) . 'include' . $slash . 'coinapi' . $slash . 'class-coinapi.php';
require_once plugin_dir_path( __FILE__ ) . 'include' . $slash . 'exchange' . $slash . 'class-cryptoexchanger.php';
require_once plugin_dir_path( __FILE__ ) . 'include' . $slash . 'exchange' . $slash . 'class-schedule-exchange-request.php';
require_once plugin_dir_path( __FILE__ ) . 'include' . $slash . 'shortcodes' . $slash . 'class-shortcodes.php';
require_once plugin_dir_path( __FILE__ ) . 'include' . $slash . 'widgets' . $slash . 'class-cryptowidget.php';


class CryptoExchangeRates {

	public function __construct() {
		$rewriteRules = new RewriteRules();
		$shortcodes   = new ShortCodes();
		$widget       = new CryptoWidget();
		$scheduler    = new ScheduleExchangeRequest();

		//Add the widget (shows only info about pairs)
		add_action( 'widgets_init', function () {
			register_widget( '\CryptoExchangeRates\Frontend\CryptoWidget' );
		} );

		//
		add_action( 'wp_enqueue_scripts', function () {
			wp_enqueue_style( 'cryptoexchange-main-css', plugin_dir_url( __FILE__ ) . 'include/assets/css/main.css', [],
				'0.1' );
		} );
	}
}

$cryptoExchangesRates = new CryptoExchangeRates();

return;