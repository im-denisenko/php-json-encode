<?php
namespace Future;

class Json
{
    /**
     * Encode an php object to json
     *
     * @param  mixed  $data
     * @param  int    $options
     * @return string
     */
    public static function encode($data, $options = 0, $depth = 512)
    {
        $filters = array();

        if (version_compare(phpversion(), '5.3.3', '<')) {
            if ($options & JSON_NUMERIC_CHECK) {
                $filters[] = array(__CLASS__, 'numericCheck');
            }
        }

        if (version_compare(phpversion(), '5.4.0', '<')) {
            if ($options & JSON_PRETTY_PRINT) {
                $filters[] = array(__CLASS__, 'prettyPrint');
            }

            if ($options & JSON_UNESCAPED_UNICODE) {
                $filters[] = array(__CLASS__, 'unescapedUnicode');
            }

            if ($options & JSON_UNESCAPED_SLASHES) {
                $filters[] = array(__CLASS__, 'unescapedSlashes');
            }
        }

        if (version_compare(phpversion(), '5.5.0', '<')) {
            $json = json_encode($data, $options);
            if (func_num_args() === 3) {
                $json = self::checkDepth($json, $depth);
            }
        } else {
            $json = json_encode($data, $options, $depth);
        }

        foreach ($filters as $filter) {
            $json = call_user_func_array($filter, array($json));
        }

        return $json;
    }

    /**
     * Modify given string as if json_encode was called with JSON_UNESCAPED_UNICODE option
     *
     * @param  string $json
     * @return string
     */
    protected static function unescapedUnicode($json)
    {
        $callback = function ($matches) {
            $sym = mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');

            return $sym;
        };

        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', $callback, $json);
    }

    /**
     * Modify given string as if json_encode was called with JSON_PRETTY_PRINT option
     *
     * @param  string $json
     * @return string
     */
    protected static function prettyPrint($json)
    {
        $result = '';
        $level = 0;
        $isQuoted = false;
        $prevChar = '';

        foreach (str_split($json) as $char) {
            if ($char === '"' && $prevChar !== '\\') {
                $isQuoted = !$isQuoted;
            }

            if ($isQuoted) {
                $result .= $char;
            } elseif ($char === '{' || $char === '[') {
                $level++;
                $result .= $char."\n".str_repeat('    ', $level);
            } elseif ($char === '}' || $char === ']') {
                $level--;
                $result .= "\n".str_repeat('    ', $level).$char;
            } elseif ($char === ',') {
                $result .= $char."\n".str_repeat('    ', $level);
            } elseif ($char === ':') {
                $result .= $char.' ';
            } elseif ($char === '"') {
                $result .= $char;
            } else {
                $result .= $char;
            }

            $prevChar = $char;
        }

        return $result;
    }

    /**
     * Modify given string as if json_encode was called with JSON_UNESCAPED_SLASHES option
     *
     * @param  string $json
     * @return string
     */
    protected static function unescapedSlashes($json)
    {
        $result = '';
        $isEscaped = false;

        foreach (str_split($json) as $char) {
            if ($isEscaped) {
                $isEscaped = false;

                if ($char === '/') {
                    $result .= $char;
                } else {
                    $result .= '\\'.$char;
                }
            } elseif ($char === '\\') {
                $isEscaped = true;
            } else {
                $result .= $char;
            }
        }

        return $result;
    }

    /**
     * Modify given string as if json_encode was called with JSON_NUMERIC_CHECK option
     *
     * @param  string $json
     * @return string
     */
    protected static function numericCheck($json)
    {
        return preg_replace('/"([0-9]+)"/', '\1', $json);
    }

    /**
     * Modify given string as if json_encode was called with $depth parameter from 5.5.0
     *
     * @param  string $json
     * @param  int    $depth
     * @return string
     */
    protected static function checkDepth($json, $depth)
    {
        $isQuoted = false;
        $isFail = false;
        $level = 0;
        $prevChar = '';

        foreach (str_split($json) as $char) {
            if ($char === '"' && $prevChar !== '\\') {
                $isQuoted = !$isQuoted;
            }

            if (!$isQuoted) {
                if ($char === '{' || $char === '[') {
                    $level++;
                    if ($level > $depth) {
                        $isFail = true;
                        break;
                    }
                } elseif ($char === '}' || $char == ']') {
                    $level--;
                }
            }

            $prevChar = $char;
        }

        return $isFail ? false : $json;
    }
}
