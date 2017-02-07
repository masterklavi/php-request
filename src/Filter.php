<?php

namespace phprequest;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.2
 */
class Filter
{
    public static function apply($filter, $body, $header)
    {
        switch ($filter)
        {
            case 'json':
                return json_decode($body);

            case 'json_assoc':
                return json_decode($body, true);

            case 'xml':
                return simplexml_load_string($body);
        }

        if (is_callable($filter))
        {
            return $format($body, $header);
        }

        trigger_error("unknown filter: {$filter}");
        return false;
    }
}
