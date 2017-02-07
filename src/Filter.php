<?php

namespace phprequest;

use phprequest\Filter\Cut;
use phprequest\Filter\Regex;


/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.3
 */
class Filter
{
    public static function apply($filter, $body, $header)
    {
        if (is_callable($filter))
        {
            return $filter($body, $header);
        }
        elseif (is_array($filter) && count($filter) > 1)
        {
            switch ($filter[0])
            {
                case 'cut':
                    return Cut::make($body, $filter[1]);
                    
                case 'regex':
                    return Regex::row($filter[1], $body);

                case 'regex_one':
                    return Regex::value($filter[1], $body);

                case 'regex_all':
                    return Regex::all($filter[1], $body);

                case 'regex_set':
                    return Regex::all($filter[1], $body, PREG_SET_ORDER);

                case 'regex_col':
                    return Regex::col($filter[1], $body);
            }
        }
        else
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
        }

        trigger_error("unknown filter: {$filter}");
        return false;
    }
}
