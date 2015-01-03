<?php

defined('JSON_PRETTY_PRINT') or define('JSON_PRETTY_PRINT', 128);
defined('JSON_NUMERIC_CHECK') or define('JSON_NUMERIC_CHECK', 32);
defined('JSON_UNESCAPED_SLASHES') or define('JSON_UNESCAPED_SLASHES', 64);
defined('JSON_UNESCAPED_UNICODE') or define('JSON_UNESCAPED_UNICODE', 256);

function future_json_encode()
{
    return call_user_func_array('Future\\Json::encode', func_get_args());
}
