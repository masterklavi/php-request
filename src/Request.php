<?php

namespace phprequest;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.1
 */
class Request
{
    public static $silent_mode = false;

    
    public static function get($url, array $options = [])
    {
        return self::request($url, $options);
    }
    
    public static function post($url, array $options = [])
    {
        $options['method'] = 'POST';
        return self::request($url, $options);
    }


    public static function request($url, array $options = [])
    {
        $ch = curl_init();

        // curl options
        curl_setopt_array($ch, self::getOptSet($url, $options));

        // additional params
        $allowed_codes = isset($options['allowed_codes']) ? (array)$options['allowed_codes'] : [200];
        $allow_empty = isset($options['allow_empty']) ? (bool)$options['allow_empty'] : false;
        $format = isset($options['format']) ? $options['format'] : null;
        $charset = isset($options['charset']) && $options['charset'] !== 'utf8' ? $options['charset'] : null;
        $attempts = isset($options['attempts']) ? (int)$options['attempts'] : 5;

        // requests
        for ($i = 0; $i < $attempts; $i++)
        {
            $response = curl_exec($ch);
            $error = curl_error($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            // check errors
            if ($error)
            {
                self::$silent_mode OR trigger_error("curl: {$error} at {$url}");
                continue;
            }
            if (!in_array($code, $allowed_codes))
            {
                self::$silent_mode OR trigger_error("http: {$code} at {$url}");
                continue;
            }

            // handle response
            $header = substr($response, 0, $size);
            $body = substr($response, $size);
            unset($response);

            if ($charset)
            {
                $body = iconv($charset, 'utf8', $body);
            }

            $result = $format ? Format::make($format, $body, $header) : $body;

            if ($result === false || !$allow_empty && !$result)
            {
                self::$silent_mode OR trigger_error("empty result at {$url}");
                continue;
            }

            curl_close($ch);
            return $result;
        }

        self::$silent_mode OR trigger_error("no attemps");
        curl_close($ch);
        return false;
    }
    

    protected static function getOptSet($url, array $options = [])
    {
        // default options
        $set = [
            CURLOPT_URL => $url,
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
        if (isset($options['data']))
        {
            if (!isset($options['method']) || in_array($options['method'], ['GET', 'HEAD', 'DELETE']))
            {
                $qs = http_build_query($options['data']);
                if (strpos($url, '?') === false)
                {
                    $set[CURLOPT_URL] = $url.'?'.$qs;
                }
                else
                {
                    $set[CURLOPT_URL] = $url.'&'.$qs;
                }
            }
            else
            {
                $set[CURLOPT_POSTFIELDS] = $options['data'];
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
}
