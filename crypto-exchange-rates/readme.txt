=== Crypto Exchange Rates sng ===
Contributors: snieto
Tags: cryptocurrencies
Requires at least: 4.5
Tested up to: 5.2.3
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This is a simple plugin that help to get info about BTC pair rates

== Description ==

This plugin is done for testing. It gets the info from CoinAPI.io using a private API KEY.
If you want to use this plugin, be sure to get you own valid API KEY.

The strategy is very simple.
It uses transients for caching the exchange rates values.
There is a scheduled task that gets the values (BTC/USD and BTC/EUR) every 5 minutes, and these values are cached.

To make the API request uses the class given by CoinAPI.io.

There are two shortcodes:
- One shows info. Example:  [cryptoexchange base="BTC" quote="USD"]
- The other renders a form to make exchange calculations [cryptoexchange_form]

There is also a widget to show the rate info.

And there is an url to access to the rate in case a JS development with ajax is needed (this is just to show how to make url rewrite in WP):
/getExchangeRate/BTC/USD/ or /getExchangeRate/BTC/EUR/


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the directory `crypto-exchange-rates` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcode [cryptoexchange_form] in any content

== Changelog ==

= 0.1 =
* First version
