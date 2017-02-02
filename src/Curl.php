<?php

namespace phprequest;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.1
 */
class Curl
{
    public static function getOptSet(array $options = [])
    {
        // default options
        $set = [
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_ENCODING => '', // decompress automatically (zlib)
        ];

        // custom options
        if (isset($options['method']))
        {
            if ($options['method'] === 'POST')
            {
                $set[CURLOPT_POST] = true;
            }
            else
            {
                $set[CURLOPT_CUSTOMREQUEST] = $options['method'];
            }
        }
        if (isset($options['follow']))
        {
            $set[CURLOPT_FOLLOWLOCATION] = (bool)$options['follow'];
            $set[CURLOPT_MAXREDIRS] = 10;
        }
        if (isset($options['encoding']))
        {
            $set[CURLOPT_ENCODING] = $options['encoding'];
        }
        if (isset($options['timeout']))
        {
            $set[CURLOPT_TIMEOUT] = (int)$options['timeout'];
        }
        if (isset($options['cookie']))
        {
            $set[CURLOPT_COOKIE] = $options['cookie'];
        }
        if (isset($options['headers']))
        {
            $set[CURLOPT_HTTPHEADER] = (array)$options['headers'];
        }
        if (isset($options['referer']))
        {
            $set[CURLOPT_REFERER] = $options['referer'];
        }

        return $set;
    }

    public static function setOptData(array $set, array $options = [])
    {
        if (!isset($options['data']))
        {
            return $set;
        }

        if (!isset($options['method']) || in_array($options['method'], ['GET', 'HEAD', 'DELETE']))
        {
            $qs = http_build_query($options['data']);
            if (strpos($set[CURLOPT_URL], '?') === false)
            {
                $set[CURLOPT_URL] .= '?'.$qs;
            }
            else
            {
                $set[CURLOPT_URL] .= '&'.$qs;
            }
        }
        else
        {
            $set[CURLOPT_POSTFIELDS] = $options['data'];
        }

        return $set;
    }
}