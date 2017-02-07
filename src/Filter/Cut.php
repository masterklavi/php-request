<?php

namespace phprequest\Filter;

/**
 * @author      Master Klavi <masterklavi@gmail.com>
 * @version     0.3
 */
class Cut
{
    public static function make($subject, $options)
    {
        // functions
        $pos = 'strpos';
        $cut = 'substr';
        $len = 'strlen';
        if (isset($options['case_sensivity']) && $options['case_sensivity'] === true)
        {
            $pos = 'stripos';
        }
        if (isset($options['mbstring']) && $options['mbstring'] === true)
        {
            $pos = 'mb_'.$pos;
            $cut = 'mb_substr';
            $len = 'mb_strlen';
        }

        // cutting from the begin
        if (isset($options['begin']) && is_string($options['begin']))
        {
            $p = $pos($subject, $options['begin']);
            if ($p == false)
            {
                return null;
            }
            $subject = $cut($subject, $p + $len($options['begin']));
        }

        // cutting to the end
        if (isset($options['end']) && is_string($options['end']))
        {
            $p = $pos($subject, $options['end']);
            if ($p == false)
            {
                return null;
            }
            $subject = $cut($subject, 0, $p);
        }

        return $subject;
    }
}
