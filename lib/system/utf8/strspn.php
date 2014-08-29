<?php

/**
 * UTF8::strspn
 *
 * @copyright  (c) 2007-2012 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function utf8_strspn(\KORD\Helper\UTF8Interface $utf8, $str, $mask, $offset = null, $length = null)
{
    if ($str == '' OR $mask == '') {
        return 0;
    }

    if ($utf8->isAscii($str) AND $utf8->isAscii($mask)) {
        return ($offset === null) ? strspn($str, $mask) : (($length === null) ? strspn($str, $mask, $offset) : strspn($str, $mask, $offset, $length));
    }

    if ($offset !== null OR $length !== null) {
        $str = $utf8->substr($str, $offset, $length);
    }

    // Escape these characters:  - [ ] . : \ ^ /
    // The . and : are escaped to prevent possible warnings about POSIX regex elements
    $mask = preg_replace('#[-[\].:\\\\^/]#', '\\\\$0', $mask);
    preg_match('/^[^' . $mask . ']+/u', $str, $matches);

    return isset($matches[0]) ? $utf8->strlen($matches[0]) : 0;
}
