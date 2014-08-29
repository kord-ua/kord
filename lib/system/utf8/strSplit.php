<?php

/**
 * UTF8::strSplit
 *
 * @copyright  (c) 2007-2012 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function utf8_str_split(\KORD\Helper\UTF8Interface $utf8, $str, $split_length = 1)
{
    $split_length = (int) $split_length;

    if ($utf8->isAscii($str)) {
        return str_split($str, $split_length);
    }

    if ($split_length < 1) {
        return false;
    }

    if ($utf8->strlen($str) <= $split_length) {
        return [$str];
    }

    preg_match_all('/.{' . $split_length . '}|[^\x00]{1,' . $split_length . '}$/us', $str, $matches);

    return $matches[0];
}
