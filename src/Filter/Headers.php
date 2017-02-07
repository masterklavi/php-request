<?php

namespace phprequest\Filter;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.4
 */
class Headers
{
    public static function parse($response)
    {
        $set = [];
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $line)
        {
            if (strpos($line, 'HTTP/') === 0)
            {
                $set['http_code'] = $line;
            }
            else
            {
                list($key, $value) = explode(': ', $line);
                $set[$key] = $value;
            }
        }
        return $set;
    }
}
