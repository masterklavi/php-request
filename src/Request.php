<?php

namespace phprequest;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.2
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
        // additional params
        $allowed_codes = isset($options['allowed_codes']) ? (array)$options['allowed_codes'] : [200];
        $allow_empty = isset($options['allow_empty']) ? (bool)$options['allow_empty'] : false;
        $format = isset($options['format']) ? $options['format'] : null;
        $charset = isset($options['charset']) && $options['charset'] !== 'utf8' ? $options['charset'] : null;
        $attempts = isset($options['attempts']) ? (int)$options['attempts'] : 5;

        // curl options
        $ch = curl_init();
        $set = Curl::getOptSet($options);
        $set[CURLOPT_URL] = $url;
        $set = Curl::setOptData($set, $options);
        curl_setopt_array($ch, $set);

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
            unset($header, $body);

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
}
