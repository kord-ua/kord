<?php

/**
 * UTF8::strpos
 *
 * @copyright  (c) 2007-2012 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
function utf8_strpos(\KORD\Helper\UTF8Interface $utf8, $str, $search, $offset = 0)
{
	$offset = (int) $offset;

        if ($utf8->isAscii($str) AND $utf8->isAscii($search)) {
            return strpos($str, $search, $offset);
        }

        if ($offset == 0) {
            $array = explode($search, $str, 2);
            return isset($array[1]) ? $utf8->strlen($array[0]) : false;
        }

        $str = $utf8->substr($str, $offset);
        $pos = $utf8->strpos($str, $search);
        return ($pos === false) ? false : ($pos + $offset);
}
