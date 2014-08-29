<?php

namespace KORD\Helper;

/**
 * UTF8 helper interface
 *
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface UTF8Interface
{

    /**
     * Detect encoding of a string
     * 
     * @param string $string
     * @return string detected encoding
     */
    public function detectEncoding($string);

    /**
     * Checks if PCRE is compiled with UTF-8 and Unicode support
     *
     * @return boolean
     */
    public function unicodeEnabled();

    /**
     * Checks if PHP mbstring extension is enabled (loaded)
     * 
     * @return boolean
     */
    public function mbstringEnabled();

    /**
     * Checks if PHP mbstring supports provided encoding
     * 
     * @param string $encoding tested encoding
     * @return boolean
     */
    public function mbstringEncodingSupported($encoding);

    /**
     * Recursively cleans arrays, objects, and strings. Removes ASCII control
     * codes and converts to the requested charset while silently discarding
     * incompatible characters.
     *
     *     $utf8->clean($_GET); // Clean GET data
     *
     * @param   mixed   $var        variable to clean
     * @param   string  $charset    character set, defaults to \KORD\Core::$charset
     * @return  mixed
     */
    public function clean($var, $charset = null);

    /**
     * Tests whether a string contains only 7-bit ASCII bytes. This is used to
     * determine when to use native functions or UTF-8 functions.
     *
     *     $ascii = $utf8->isAscii($str);
     *
     * @param   mixed   $str    string or array of strings to check
     * @return  boolean
     */
    public function isAscii($str);

    /**
     * Strips out device control codes in the ASCII range.
     *
     *     $str = $utf8->stripAsciiCtrl($str);
     *
     * @param   string  $str    string to clean
     * @return  string
     */
    public function stripAsciiCtrl($str);

    /**
     * Strips out all non-7bit ASCII bytes.
     *
     *     $str = $utf8->stripNonAscii($str);
     *
     * @param   string  $str    string to clean
     * @return  string
     */
    public function stripNonAscii($str);

    /**
     * Replaces special/accented UTF-8 characters by ASCII-7 "equivalents".
     *
     *     $ascii = $utf8->transliterateToAscii($utf8);
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str    string to transliterate
     * @param   integer $case   -1 lowercase only, +1 uppercase only, 0 both cases
     * @return  string
     */
    public function transliterateToAscii($str, $case = 0);

    /**
     * Returns the length of the given string. This is a UTF8-aware version
     * of [strlen](http://php.net/strlen).
     *
     *     $length = $utf8->strlen($str);
     *
     * @param   string  $str    string being measured for length
     * @param   string  $charset of input string
     * @return  integer
     */
    public function strlen($str, $charset = null);

    /**
     * Finds position of first occurrence of a UTF-8 string. This is a
     * UTF8-aware version of [strpos](http://php.net/strpos).
     *
     *     $position = $utf8->strpos($str, $search);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str    haystack
     * @param   string  $search needle
     * @param   integer $offset offset from which character in haystack to start searching
     * @return  integer position of needle
     * @return  boolean false if the needle is not found
     */
    public function strpos($str, $search, $offset = 0);

    /**
     * Finds position of last occurrence of a char in a UTF-8 string. This is
     * a UTF8-aware version of [strrpos](http://php.net/strrpos).
     *
     *     $position = $utf8->strrpos($str, $search);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str    haystack
     * @param   string  $search needle
     * @param   integer $offset offset from which character in haystack to start searching
     * @return  integer position of needle
     * @return  boolean false if the needle is not found
     */
    public function strrpos($str, $search, $offset = 0);

    /**
     * Returns part of a UTF-8 string. This is a UTF8-aware version
     * of [substr](http://php.net/substr).
     *
     *     $sub = $utf8->substr($str, $offset);
     *
     * @author  Chris Smith <chris@jalakai.co.uk>
     * @param   string  $str    input string
     * @param   integer $offset offset
     * @param   integer $length length limit
     * @return  string
     */
    public function substr($str, $offset, $length = null);

    /**
     * Replaces text within a portion of a UTF-8 string. This is a UTF8-aware
     * version of [substr_replace](http://php.net/substr_replace).
     *
     *     $str = $utf8->substrReplace($str, $replacement, $offset);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str            input string
     * @param   string  $replacement    replacement string
     * @param   integer $offset         offset
     * @return  string
     */
    public function substrReplace($str, $replacement, $offset, $length = null);

    /**
     * Makes a UTF-8 string lowercase. This is a UTF8-aware version
     * of [strtolower](http://php.net/strtolower).
     *
     *     $str = $utf8->strtolower($str);
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str mixed case string
     * @param   string  $charset of input string
     * @return  string
     */
    public function strtolower($str, $charset = null);

    /**
     * Makes a UTF-8 string uppercase. This is a UTF8-aware version
     * of [strtoupper](http://php.net/strtoupper).
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str mixed case string
     * @param   string  $charset of input string
     * @return  string
     */
    public function strtoupper($str, $charset = null);

    /**
     * Makes a UTF-8 string's first character uppercase. This is a UTF8-aware
     * version of [ucfirst](http://php.net/ucfirst).
     *
     *     $str = $utf8->ucfirst($str);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str mixed case string
     * @return  string
     */
    public function ucfirst($str);

    /**
     * Makes the first character of every word in a UTF-8 string uppercase.
     * This is a UTF8-aware version of [ucwords](http://php.net/ucwords).
     *
     *     $str = $utf8->ucwords($str);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str mixed case string
     * @return  string
     */
    public function ucwords($str);

    /**
     * Case-insensitive UTF-8 string comparison. This is a UTF8-aware version
     * of [strcasecmp](http://php.net/strcasecmp).
     *
     *     $compare = $utf8->strcasecmp($str1, $str2);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str1   string to compare
     * @param   string  $str2   string to compare
     * @return  integer less than 0 if str1 is less than str2
     * @return  integer greater than 0 if str1 is greater than str2
     * @return  integer 0 if they are equal
     */
    public function strcasecmp($str1, $str2);

    /**
     * Returns a string or an array with all occurrences of search in subject
     * (ignoring case) and replaced with the given replace value. This is a
     * UTF8-aware version of [str_ireplace](http://php.net/str_ireplace).
     *
     * [!!] This function is very slow compared to the native version. Avoid
     * using it when possible.
     *
     * @author  Harry Fuecks <hfuecks@gmail.com
     * @param   string|array    $search     text to replace
     * @param   string|array    $replace    replacement text
     * @param   string|array    $str        subject text
     * @param   integer         $count      number of matched and replaced needles will be returned via this parameter which is passed by reference
     * @return  string  if the input was a string
     * @return  array   if the input was an array
     */
    public function strIreplace($search, $replace, $str, & $count = null);

    /**
     * Case-insensitive UTF-8 version of strstr. Returns all of input string
     * from the first occurrence of needle to the end. This is a UTF8-aware
     * version of [stristr](http://php.net/stristr).
     *
     *     $found = $utf8->stristr($str, $search);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str    input string
     * @param   string  $search needle
     * @return  string  matched substring if found
     * @return  false   if the substring was not found
     */
    public function stristr($str, $search);

    /**
     * Finds the length of the initial segment matching mask. This is a
     * UTF8-aware version of [strspn](http://php.net/strspn).
     *
     *     $found = $utf8->strspn($str, $mask);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str    input string
     * @param   string  $mask   mask for search
     * @param   integer $offset start position of the string to examine
     * @param   integer $length length of the string to examine
     * @return  integer length of the initial segment that contains characters in the mask
     */
    public function strspn($str, $mask, $offset = null, $length = null);

    /**
     * Finds the length of the initial segment not matching mask. This is a
     * UTF8-aware version of [strcspn](http://php.net/strcspn).
     *
     *     $found = $utf8->strcspn($str, $mask);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str    input string
     * @param   string  $mask   mask for search
     * @param   integer $offset start position of the string to examine
     * @param   integer $length length of the string to examine
     * @return  integer length of the initial segment that contains characters not in the mask
     */
    public function strcspn($str, $mask, $offset = null, $length = null);

    /**
     * Pads a UTF-8 string to a certain length with another string. This is a
     * UTF8-aware version of [str_pad](http://php.net/str_pad).
     *
     *     $str = $utf8->strPad($str, $length);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str                input string
     * @param   integer $final_str_length   desired string length after padding
     * @param   string  $pad_str            string to use as padding
     * @param   string  $pad_type           padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
     * @return  string
     */
    public function strPad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT);

    /**
     * Converts a UTF-8 string to an array. This is a UTF8-aware version of
     * [str_split](http://php.net/str_split).
     *
     *     $array = $utf8->strSplit($str);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str            input string
     * @param   integer $split_length   maximum length of each chunk
     * @return  array
     */
    public function strSplit($str, $split_length = 1);

    /**
     * Reverses a UTF-8 string. This is a UTF8-aware version of [strrev](http://php.net/strrev).
     *
     *     $str = $utf8->strrev($str);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str string to be reversed
     * @return  string
     */
    public function strrev($str);

    /**
     * Strips whitespace (or other UTF-8 characters) from the beginning and
     * end of a string. This is a UTF8-aware version of [trim](http://php.net/trim).
     *
     *     $str = $utf8->trim($str);
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str        input string
     * @param   string  $charlist   string of characters to remove
     * @return  string
     */
    public function trim($str, $charlist = null);

    /**
     * Strips whitespace (or other UTF-8 characters) from the beginning of
     * a string. This is a UTF8-aware version of [ltrim](http://php.net/ltrim).
     *
     *     $str = $utf8->ltrim($str);
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str        input string
     * @param   string  $charlist   string of characters to remove
     * @return  string
     */
    public function ltrim($str, $charlist = null);

    /**
     * Strips whitespace (or other UTF-8 characters) from the end of a string.
     * This is a UTF8-aware version of [rtrim](http://php.net/rtrim).
     *
     *     $str = $utf8->rtrim($str);
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str        input string
     * @param   string  $charlist   string of characters to remove
     * @return  string
     */
    public function rtrim($str, $charlist = null);

    /**
     * Returns the unicode ordinal for a character. This is a UTF8-aware
     * version of [ord](http://php.net/ord).
     *
     *     $digit = $utf8->ord($character);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $chr    UTF-8 encoded character
     * @return  integer
     */
    public function ord($chr);

    /**
     * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
     * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
     * Occurrences of the BOM are ignored. Surrogates are not allowed.
     *
     *     $array = $utf8->toUnicode($str);
     *
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
     * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see <http://hsivonen.iki.fi/php-utf8/>
     * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>
     *
     * @param   string  $str    UTF-8 encoded string
     * @return  array   unicode code points
     * @return  false   if the string is invalid
     */
    public function toUnicode($str);

    /**
     * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
     * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
     * Occurrences of the BOM are ignored. Surrogates are not allowed.
     *
     *     $str = $utf8->fromUnicode($array);
     *
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
     * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/
     * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
     *
     * @param   array   $arr    unicode code points representing a string
     * @return  string  utf8 string of characters
     * @return  boolean false if a code point cannot be found
     */
    public function fromUnicode($arr);

}
