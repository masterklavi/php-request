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
        $ch = curl_init();

        // default options
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_ENCODING => '', // decompress automatically (zlib)
        ]);

        // custom options
        if (isset($options['follow']))
        {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, (bool)$options['follow']);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        }
        if (isset($options['encoding']))
        {
            curl_setopt($ch, CURLOPT_ENCODING, $options['encoding']);
        }
        if (isset($options['timeout']))
        {
            curl_setopt($ch, CURLOPT_TIMEOUT, (int)$options['timeout']);
        }
        if (isset($options['cookie']))
        {
            curl_setopt($ch, CURLOPT_COOKIE, $options['cookie']);
        }
        if (isset($options['headers']))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $options['headers']);
        }
        if (isset($options['referer']))
        {
            curl_setopt($ch, CURLOPT_REFERER, $options['referer']);
        }

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
}
