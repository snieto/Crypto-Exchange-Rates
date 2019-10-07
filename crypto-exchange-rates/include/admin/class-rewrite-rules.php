<?php
namespace CryptoExchangeRates\Admin;

class RewriteRules
{

    function __construct()
    {
        //create your rewrite rule
        add_action('init', [$this, 'crypto_rate_rewrite_rule']);
        add_filter('query_vars', [$this, 'crypto_rate_query_vars']);
        add_action('parse_request', [$this, 'crypto_rate_parse_request']);
    }

	/**
	 * Add a rewrite rule
	 */
    function crypto_rate_rewrite_rule()
    {
        add_rewrite_rule('getExchangeRate/([^/]*)/([^/]*)/?', 'index.php?BTCexchange=true&asset_base=$matches[1]&asset_quote=$matches[2]',
            'top');
    }

	/**
	 * Add the query vars for the pair rate
	 *
	 * @param $query_vars
	 *
	 * @return array
	 */
    function crypto_rate_query_vars($query_vars)
    {
        $query_vars[] = 'asset_base';
        $query_vars[] = 'asset_quote';

        return $query_vars;
    }

	/**
	 * 
	 * Includes the file for showing the value
	 * 
	 * @param $wp
	 */
    function crypto_rate_parse_request($wp)
    {
        $slash = DIRECTORY_SEPARATOR;

        if (array_key_exists('asset_base', $wp->query_vars)
            && array_key_exists('asset_quote', $wp->query_vars)
            && $wp->query_vars['asset_base'] == 'BTC'
            && ($wp->query_vars['asset_quote'] == 'USD'
                || $wp->query_vars['asset_quote'] == 'EUR')) {
            include(__DIR__ . $slash . '..' . $slash . '..' . $slash . 'templates' . $slash . 'return-rate.php');
            exit();
        }
    }
}