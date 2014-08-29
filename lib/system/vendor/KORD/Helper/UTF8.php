<?php

namespace KORD\Helper;

use KORD\Filesystem\FileSystemInterface;

/**
 * A port of [phputf8](http://phputf8.sourceforge.net/) to a unified set
 * of files. Provides multi-byte aware replacement string functions.
 *
 * For UTF-8 support to work correctly, the following requirements must be met:
 *
 * - PCRE needs to be compiled with UTF-8 support (--enable-utf8)
 * - Support for [Unicode properties](http://php.net/manual/reference.pcre.pattern.modifiers.php)
 *   is highly recommended (--enable-unicode-properties)
 * - The [mbstring extension](http://php.net/mbstring) is highly recommended,
 *   but must not be overloading string functions
 *
 * [!!] This file is licensed differently from the rest of KORD. As a port of
 * [phputf8](http://phputf8.sourceforge.net/), this file is released under the LGPL.
 *
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 * @copyright  (c) 2005 Harry Fuecks
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class UTF8 implements UTF8Interface
{

    /**
     * @var  array  List of called methods that have had their required file included.
     */
    protected static $called = [];

    /**
     * @var  boolean  Does the server support UTF-8 natively?
     */
    protected $unicode_enabled = null;

    /**
     * @var  boolean  Is mbstring extension available
     */
    protected $mbstring_enabled = null;

    /**
     * @var  string  default encoding
     */
    protected $encoding_default = 'UTF-8';

    /**
     * @var array  The list of most popular encodings
     */
    protected $encodings = [
        'UTF-8', 'ISO-8859-1', 'Windows-1251', 'GB2312', 'Shift JIS',
        'Windows-1252', 'GBK', 'EUC-JP', 'ISO-8859-2', 'EUC-KR', 'Windows-1256',
        'ISO-8859-15', 'ISO-8859-9', 'Windows-1250', 'Windows-1254', 'Big5',
        'Windows-874', 'US-ASCII'
    ];

    /**
     * @var \KORD\Filesystem\FileSystemInterface 
     */
    protected $filesystem;

    /**
     * Construct new utf-8 helper
     * 
     * @param array $config
     */
    public function __construct(FileSystemInterface $filesystem, array $config = [])
    {
        $this->filesystem = $filesystem;

        if (isset($config['encoding_default'])) {
            $this->encoding_default = $config['encoding_default'];
        }

        if (isset($config['encodings'])) {
            $this->encodings = $config['encodings'];
        }
    }

    /**
     * Detect encoding of a string
     * 
     * @param string $string
     * @return string detected encoding
     */
    public function detectEncoding($string)
    {
        if ($this->mbstringEnabled()) {
            $encoding = mb_detect_encoding($string);
            if ('ISO-8859-2' === $encoding AND preg_match('~[\x7F-\x9F\xBC]~', $string)) {
                $encoding = 'WINDOWS-1250';
            }
        } else {
            foreach ($this->encodings as $item) {
                if (strcmp(@iconv($item, $item, $string), $string) == 0) {
                    return $item;
                }
            }
            return $this->encoding_default;
        }

        return $encoding;
    }

    /**
     * Checks if PCRE is compiled with UTF-8 and Unicode support
     *
     * @return boolean
     */
    public function unicodeEnabled()
    {
        if ($this->unicode_enabled === null) {
            // Determine if this server supports UTF-8 natively
            $this->unicode_enabled = (@preg_match('/\pL/u', 'a')) ? true : false;
        }

        return $this->unicode_enabled;
    }

    /**
     * Checks if PHP mbstring extension is enabled (loaded)
     * 
     * @return boolean
     */
    public function mbstringEnabled()
    {
        if ($this->mbstring_enabled === null) {
            // Determine if this server supports mbstring functions
            $this->mbstring_enabled = extension_loaded('mbstring');
        }

        return $this->mbstring_enabled;
    }

    /**
     * Checks if PHP mbstring supports provided encoding
     * 
     * @param string $encoding tested encoding
     * @return boolean
     */
    public function mbstringEncodingSupported($encoding)
    {
        if (!$this->mbstringEnabled()) {
            return false;
        }

        return in_array(strtolower($encoding), array_map('strtolower', mb_list_encodings()));
    }

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
    public function clean($var, $charset = null)
    {
        if (is_array($var) OR is_object($var)) {
            foreach ($var as $key => $val) {
                if (!$charset) {
                    // Detect character set
                    $charset = $this->detectEncoding($val);
                }
                // Recursion!
                $var[$this->clean($key)] = $this->clean($val);
            }
        } elseif (is_string($var) AND $var !== '') {
            if (!$charset) {
                // Detect character set
                $charset = $this->detectEncoding($var);
            }

            // Remove control characters
            $var = $this->stripAsciiCtrl($var);

            if (!$this->isAscii($var)) {
                // Disable notices
                $error_reporting = error_reporting(~E_NOTICE);

                $var = mb_convert_encoding($var, $charset, $charset);

                // Turn notices back on
                error_reporting($error_reporting);
            }
        }

        return $var;
    }

    /**
     * Tests whether a string contains only 7-bit ASCII bytes. This is used to
     * determine when to use native functions or UTF-8 functions.
     *
     *     $ascii = $utf8->isAscii($str);
     *
     * @param   mixed   $str    string or array of strings to check
     * @return  boolean
     */
    public function isAscii($str)
    {
        if (is_array($str)) {
            $str = implode($str);
        }

        return !preg_match('/[^\x00-\x7F]/S', $str);
    }

    /**
     * Strips out device control codes in the ASCII range.
     *
     *     $str = $utf8->stripAsciiCtrl($str);
     *
     * @param   string  $str    string to clean
     * @return  string
     */
    public function stripAsciiCtrl($str)
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
    }

    /**
     * Strips out all non-7bit ASCII bytes.
     *
     *     $str = $utf8->stripNonAscii($str);
     *
     * @param   string  $str    string to clean
     * @return  string
     */
    public function stripNonAscii($str)
    {
        return preg_replace('/[^\x00-\x7F]+/S', '', $str);
    }

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
    public function transliterateToAscii($str, $case = 0)
    {
        $this->load(__FUNCTION__);

        return utf8_transliterate_to_ascii($this, $str, $case);
    }

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
    public function strlen($str, $charset = null)
    {
        if (!$charset) {
            // Detect character set
            $charset = $this->detectEncoding($str);
        }

        if ($this->mbstringEnabled() AND $this->mbstringEncodingSupported($charset)) {
            return mb_strlen($str, $charset);
        }

        if ($this->isAscii($str)) {
            return strlen($str);
        }

        return strlen(utf8_decode(iconv($charset, "UTF-8", $str)));
    }

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
    public function strpos($str, $search, $offset = 0)
    {
        if ($this->mbstringEnabled()) {
            return mb_strpos($str, $search, $offset, $this->detectEncoding($str));
        }

        $this->load(__FUNCTION__);

        return utf8_strpos($this, $str, $search, $offset);
    }

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
    public function strrpos($str, $search, $offset = 0)
    {
        if ($this->mbstringEnabled()) {
            return mb_strrpos($str, $search, $offset, $this->detectEncoding($str));
        }

        $this->load(__FUNCTION__);

        return utf8_strrpos($this, $str, $search, $offset);
    }

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
    public function substr($str, $offset, $length = null)
    {
        if ($this->mbstringEnabled()) {
            return ($length === null) ? mb_substr($str, $offset, mb_strlen($str), $this->detectEncoding($str)) : mb_substr($str, $offset, $length, $this->detectEncoding($str));
        }

        $this->load(__FUNCTION__);

        return utf8_substr($this, $str, $offset, $length);
    }

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
    public function substrReplace($str, $replacement, $offset, $length = null)
    {
        $this->load(__FUNCTION__);

        return utf8_substr_replace($this, $str, $replacement, $offset, $length);
    }

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
    public function strtolower($str, $charset = null)
    {
        if ($charset === null) {
            $charset = $this->detectEncoding($str);
        }

        if ($this->mbstringEnabled() AND $this->mbstringEncodingSupported($charset)) {
            return mb_strtolower($str, $charset);
        }

        $this->load(__FUNCTION__);

        return iconv("UTF-8", $charset, utf8_strtolower($this, iconv($charset, "UTF-8", $str)));
    }

    /**
     * Makes a UTF-8 string uppercase. This is a UTF8-aware version
     * of [strtoupper](http://php.net/strtoupper).
     *
     * @author  Andreas Gohr <andi@splitbrain.org>
     * @param   string  $str mixed case string
     * @param   string  $charset of input string
     * @return  string
     */
    public function strtoupper($str, $charset = null)
    {
        if ($charset === null) {
            $charset = $this->detectEncoding($str);
        }

        if ($this->mbstringEnabled() AND $this->mbstringEncodingSupported($charset)) {
            return mb_strtoupper($str, $charset);
        }

        $this->load(__FUNCTION__);

        return iconv("UTF-8", $charset, utf8_strtoupper($this, iconv($charset, "UTF-8", $str)));
    }

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
    public function ucfirst($str)
    {
        if ($this->isAscii($str)) {
            return ucfirst($str);
        }

        preg_match('/^(.?)(.*)$/us', $str, $matches);
        return $this->strtoupper($matches[1]) . $matches[2];
    }

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
    public function ucwords($str)
    {
        if ($this->isAscii($str)) {
            return ucwords($str);
        }

        // [\x0c\x09\x0b\x0a\x0d\x20] matches form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns.
        // This corresponds to the definition of a 'word' defined at http://php.net/ucwords
        return preg_replace('/(?<=^|[\x0c\x09\x0b\x0a\x0d\x20])[^\x0c\x09\x0b\x0a\x0d\x20]/ue', '$this->strtoupper(\'$0\')', $str);
    }

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
    public function strcasecmp($str1, $str2)
    {
        if ($this->isAscii($str1) AND $this->isAscii($str2)) {
            return strcasecmp($str1, $str2);
        }

        $str1 = $this->strtolower($str1);
        $str2 = $this->strtolower($str2);
        return strcmp($str1, $str2);
    }

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
    public function strIreplace($search, $replace, $str, & $count = null)
    {
        $this->load(__FUNCTION__);
        
        return utf8_str_ireplace($this, $search, $replace, $str, $count);
    }

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
    public function stristr($str, $search)
    {
        $this->load(__FUNCTION__);
        
        return utf8_stristr($this, $str, $search);
    }

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
    public function strspn($str, $mask, $offset = null, $length = null)
    {
        $this->load(__FUNCTION__);
        
        return utf8_strspn($this, $str, $mask, $offset, $length);
    }

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
    public function strcspn($str, $mask, $offset = null, $length = null)
    {
        $this->load(__FUNCTION__);
        
        return utf8_strcspn($this, $str, $mask, $offset, $length);
    }

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
    public function strPad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $this->load(__FUNCTION__);
        
        return utf8_str_pad($this, $str, $final_str_length, $pad_str, $pad_type);
    }

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
    public function strSplit($str, $split_length = 1)
    {
        $this->load(__FUNCTION__);
        
        return utf8_str_split($this, $str, $split_length);
    }

    /**
     * Reverses a UTF-8 string. This is a UTF8-aware version of [strrev](http://php.net/strrev).
     *
     *     $str = $utf8->strrev($str);
     *
     * @author  Harry Fuecks <hfuecks@gmail.com>
     * @param   string  $str string to be reversed
     * @return  string
     */
    public function strrev($str)
    {
        if ($this->isAscii($str)) {
            return strrev($str);
        }

        preg_match_all('/./us', $str, $matches);
        return implode('', array_reverse($matches[0]));
    }

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
    public function trim($str, $charlist = null)
    {
        if ($charlist === null) {
            return trim($str);
        }

        return $this->ltrim($this->rtrim($str, $charlist), $charlist);
    }

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
    public function ltrim($str, $charlist = null)
    {
        if ($charlist === null) {
            return ltrim($str);
        }

        if ($this->isAscii($charlist)) {
            return ltrim($str, $charlist);
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/^[' . $charlist . ']+/u', '', $str);
    }

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
    public function rtrim($str, $charlist = null)
    {
        if ($charlist === null) {
            return rtrim($str);
        }

        if ($this->isAscii($charlist)) {
            return rtrim($str, $charlist);
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/[' . $charlist . ']++$/uD', '', $str);
    }

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
    public function ord($chr)
    {
        $this->load(__FUNCTION__);
        
        return utf8_ord($chr);
    }

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
    public function toUnicode($str)
    {
        $this->load(__FUNCTION__);
        
        return utf8_to_unicode($str);
    }

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
    public function fromUnicode($arr)
    {
        $this->load(__FUNCTION__);
        
        return utf8_from_unicode($arr);
    }

    /**
     * Calls a utf8 function from an external file
     * 
     * @param string $function
     */
    protected function load($function)
    {
        if (!isset(UTF8::$called[$function])) {
            require $this->filesystem->findFile('utf8', $function);

            // Function has been called
            UTF8::$called[$function] = true;
        }
    }

}
