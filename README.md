Future\Json
=======

[![Build Status](https://travis-ci.org/im-denisenko/php-json-encode.svg?branch=master)](https://travis-ci.org/im-denisenko/php-json-encode)

Provides features, added to json_encode since version 5.3.0, for version 5.3.0.

# Reference

- \>=5.5.0 Third parameter $depth allow let you set the maximum depth.

- \>=5.4.0 Option JSON_PRETTY_PRINT let use whitespace in returned data to format it.

- \>=5.4.0 Option JSON_UNESCAPED_SLASHES let don't escape /.

- \>=5.4.0 Option JSON_UNESCAPED_UNICODE let encode multibyte Unicode characters literally (default is to escape as \uXXXX).

- \>=5.3.3 Option JSON_NUMERIC_CHECK let you encode numeric strings as numbers.

# Usage

```php
$data = array('foo' => 'bar');
echo Future\Json::encode($data, JSON_PRETTY_PRINT);
echo Future\Json::encode($data, JSON_NUMERIC_CHECK);
echo Future\Json::encode($data, JSON_UNESCAPED_SLASHES);
echo Future\Json::encode($data, JSON_UNESCAPED_UNICODE);
echo Future\Json::encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo Future\Json::encode($data, null, 4);
```

# Shortcut

If you dont like Namespace\Classname::staticMethod call, you can use shortcuts.

```php
future_json_encode($data, JSON_UNESCAPED_UNICODE, 10);
```

# Testing

If current version PHP allow to use features described above, then encoder will pass all arguments to native function instead.

Directory ```tests/``` contains json files that were generated using native function in PHP 5.5.6.

You can run the unit tests just to be sure that you will get exactly the same result regardless of your system:

```bash
$ composer install --dev
$ vendor/bin/phpunit tests/
```
