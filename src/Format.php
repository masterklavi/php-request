<?php

namespace phprequest;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.1
 */
class Format
{
    public static function make($format, $body, $header)
    {
        switch ($format)
        {
            case 'json':
                return json_decode($body);

            case 'json_assoc':
                return json_decode($body, true);

            case 'xml':
                return simplexml_load_string($body);
        }

        if (is_callable($format))
        {
            return $format($body, $header);
        }

        trigger_error("unknown format: {$format}");
        return false;
    }
}
