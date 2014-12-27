<?php

defined('JSON_PRETTY_PRINT') or define('JSON_PRETTY_PRINT', 128);
defined('JSON_NUMERIC_CHECK') or define('JSON_NUMERIC_CHECK', 32);
defined('JSON_UNESCAPED_SLASHES') or define('JSON_UNESCAPED_SLASHES', 64);
defined('JSON_UNESCAPED_UNICODE') or define('JSON_UNESCAPED_UNICODE', 256);

function future_json_decode($json, $assoc=false, $depth=512, $options=0)
{
    return Mint\Json::decode($json, $assoc, $depth, $options);
}

function future_json_encode($value, $options=0, $depth=512)
{
    return Mint\Json::encode($value, $options, $depth);
}
