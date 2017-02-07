<?php

namespace phprequest\Filter;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.3
 */
class Regex
{
    public static function row($pattern, $subject)
    {
        if ($result = preg_match($pattern, $subject, $matches))
        {
            return $matches;
        }
        else
        {
            return $result;
        }
    }

    public static function value($pattern, $subject)
    {
        if ($result = preg_match($pattern, $subject, $matches))
        {
            return isset($matches[1]) ? $matches[1] : null;
        }
        else
        {
            return $result;
        }
    }

    public static function all($pattern, $subject, $flags = PREG_PATTERN_ORDER)
    {
        if ($result = preg_match_all($pattern, $subject, $matches, $flags))
        {
            return $matches;
        }
        else
        {
            return $result;
        }
    }

    public static function col($pattern, $subject)
    {
        if ($result = preg_match_all($pattern, $subject, $matches))
        {
            return isset($matches[1]) ? $matches[1] : null;
        }
        else
        {
            return $result;
        }
    }
}
