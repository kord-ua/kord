<?php

/**
 * UTF8::strIreplace
 *
 * @copyright  (c) 2007-2012 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function utf8_str_ireplace(\KORD\Helper\UTF8Interface $utf8, $search, $replace, $str, & $count = null)
{
    if ($utf8->isAscii($search) AND $utf8->isAscii($replace) AND $utf8->isAscii($str)) {
        return str_ireplace($search, $replace, $str, $count);
    }

    if (is_array($str)) {
        foreach ($str as $key => $val) {
            $str[$key] = $utf8->strIreplace($search, $replace, $val, $count);
        }
        return $str;
    }

    if (is_array($search)) {
        $keys = array_keys($search);

        foreach ($keys as $k) {
            if (is_array($replace)) {
                if (array_key_exists($k, $replace)) {
                    $str = $utf8->strIreplace($search[$k], $replace[$k], $str, $count);
                } else {
                    $str = $utf8->strIreplace($search[$k], '', $str, $count);
                }
            } else {
                $str = $utf8->strIreplace($search[$k], $replace, $str, $count);
            }
        }
        return $str;
    }

    $search = $utf8->strtolower($search);
    $str_lower = $utf8->strtolower($str);

    $total_matched_strlen = 0;
    $i = 0;

    while (preg_match('/(.*?)' . preg_quote($search, '/') . '/s', $str_lower, $matches)) {
        $matched_strlen = strlen($matches[0]);
        $str_lower = substr($str_lower, $matched_strlen);

        $offset = $total_matched_strlen + strlen($matches[1]) + ($i * (strlen($replace) - 1));
        $str = substr_replace($str, $replace, $offset, strlen($search));

        $total_matched_strlen += $matched_strlen;
        $i++;
    }

    $count += $i;
    return $str;
}