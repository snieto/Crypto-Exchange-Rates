<?php

use \CryptoExchangeRates\CoinAPI\CoinAPI;

global $wp;
$query_vars = $wp->query_vars;

$base = $query_vars['asset_base'];
$quote = $query_vars['asset_quote'];

$cryptoExchanger = new \CryptoExchangeRates\Exchange\CryptoExchanger(COINAPI_KEY);

echo $cryptoExchanger->get_exchange_rate($base, $quote);