<?php

namespace KORD\Helper;

/**
 * Date helper interface
 * 
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface DateInterface
{

    /**
     * Returns the offset (in seconds) between two time zones. Use this to
     * display dates to users in different time zones.
     *
     *     $seconds = $date->offset('America/Chicago', 'GMT');
     *
     * [!!] A list of time zones that PHP supports can be found at
     * <http://php.net/timezones>.
     *
     * @param   string  $remote timezone that to find the offset of
     * @param   string  $local  timezone used as the baseline
     * @param   mixed   $now    UNIX timestamp or date string
     * @return  integer
     */
    public function offset($remote, $local = null, $now = null);

    /**
     * Number of seconds in a minute, incrementing by a step. Typically used as
     * a shortcut for generating a list that can used in a form.
     *
     *     $seconds = $date->seconds(); // 01, 02, 03, ..., 58, 59, 60
     *
     * @param   integer $step   amount to increment each step by, 1 to 30
     * @param   integer $start  start value
     * @param   integer $end    end value
     * @return  array   A mirrored (foo => foo) array from 1-60.
     */
    public function seconds($step = 1, $start = 0, $end = 60);

    /**
     * Number of minutes in an hour, incrementing by a step. Typically used as
     * a shortcut for generating a list that can be used in a form.
     *
     *     $minutes = $date->minutes(); // 05, 10, 15, ..., 50, 55, 60
     *
     * @param   integer $step   amount to increment each step by, 1 to 30
     * @return  array   A mirrored (foo => foo) array from 1-60.
     */
    public function minutes($step = 5);

    /**
     * Number of hours in a day. Typically used as a shortcut for generating a
     * list that can be used in a form.
     *
     *     $hours = $date->hours(); // 01, 02, 03, ..., 10, 11, 12
     *
     * @param   integer $step   amount to increment each step by
     * @param   boolean $long   use 24-hour time
     * @param   integer $start  the hour to start at
     * @return  array   A mirrored (foo => foo) array from start-12 or start-23.
     */
    public function hours($step = 1, $long = false, $start = null);

    /**
     * Returns AM or PM, based on a given hour (in 24 hour format).
     *
     *     $type = $date->ampm(12); // PM
     *     $type = $date->ampm(1);  // AM
     *
     * @param   integer $hour   number of the hour
     * @return  string
     */
    public function ampm($hour);

    /**
     * Adjusts a non-24-hour number into a 24-hour number.
     *
     *     $hour = $date->adjust(3, 'pm'); // 15
     *
     * @param   integer $hour   hour to adjust
     * @param   string  $ampm   AM or PM
     * @return  string
     */
    public function adjust($hour, $ampm);

    /**
     * Number of days in a given month and year. Typically used as a shortcut
     * for generating a list that can be used in a form.
     *
     *     $date->days(4, 2010); // 1, 2, 3, ..., 28, 29, 30
     *
     * @param   integer $month  number of month
     * @param   integer $year   number of year to check month, defaults to the current year
     * @return  array   A mirrored (foo => foo) array of the days.
     */
    public function days($month, $year = false);

    /**
     * Number of months in a year. Typically used as a shortcut for generating
     * a list that can be used in a form.
     *
     * By default a mirrored array of $month_number => $month_number is returned
     *
     *     $date->months();
     *     // aray(1 => 1, 2 => 2, 3 => 3, ..., 12 => 12)
     *
     * But you can customise this by passing in either Date::MONTHS_LONG
     *
     *     $date->months(Date::MONTHS_LONG);
     *     // array(1 => 'January', 2 => 'February', ..., 12 => 'December')
     *
     * Or Date::MONTHS_SHORT
     *
     *     $date->months(Date::MONTHS_SHORT);
     *     // array(1 => 'Jan', 2 => 'Feb', ..., 12 => 'Dec')
     *
     * @param   string  $format The format to use for months
     * @return  array   An array of months based on the specified format
     */
    public function months($format = null);

    /**
     * Returns an array of years between a starting and ending year. By default,
     * the the current year - 5 and current year + 5 will be used. Typically used
     * as a shortcut for generating a list that can be used in a form.
     *
     *     $years = $date->years(2000, 2010); // 2000, 2001, ..., 2009, 2010
     *
     * @param   integer $start  starting year (default is current year - 5)
     * @param   integer $end    ending year (default is current year + 5)
     * @return  array
     */
    public function years($start = false, $end = false);

    /**
     * Returns time difference between two timestamps, in human readable format.
     * If the second timestamp is not given, the current time will be used.
     * Also consider using [Date::fuzzySpan] when displaying a span.
     *
     *     $span = $date->span(60, 182, 'minutes,seconds'); // array('minutes' => 2, 'seconds' => 2)
     *     $span = $date->span(60, 182, 'minutes'); // 2
     *
     * @param   integer $remote timestamp to find the span of
     * @param   integer $local  timestamp to use as the baseline
     * @param   string  $output formatting string
     * @return  string   when only a single output is requested
     * @return  array    associative list of all outputs requested
     */
    public function span($remote, $local = null, $output = 'years,months,weeks,days,hours,minutes,seconds');

    /**
     * Converts a UNIX timestamp to DOS format. There are very few cases where
     * this is needed, but some binary formats use it (eg: zip files.)
     * Converting the other direction is done using {@link Date::dos2unix}.
     *
     *     $dos = $date->unix2dos($unix);
     *
     * @param   integer $timestamp  UNIX timestamp
     * @return  integer
     */
    public function unix2dos($timestamp = false);

    /**
     * Converts a DOS timestamp to UNIX format.There are very few cases where
     * this is needed, but some binary formats use it (eg: zip files.)
     * Converting the other direction is done using {@link Date::unix2dos}.
     *
     *     $unix = $date->dos2unix($dos);
     *
     * @param   integer $timestamp  DOS timestamp
     * @return  integer
     */
    public function dos2unix($timestamp = false);

    /**
     * Returns a date/time string with the specified timestamp format
     *
     *     $time = $date->formattedTime('5 minutes ago');
     *
     * @link    http://www.php.net/manual/datetime.construct
     * @param   string  $datetime_str       datetime string
     * @param   string  $timestamp_format   timestamp format
     * @param   string  $timezone           timezone identifier
     * @return  string
     */
    public function formattedTime($datetime_str = 'now', $timestamp_format = null, $timezone = null);
    
    /**
     * Returns the difference between a time and now in a "fuzzy" way.
     * Displaying a fuzzy time instead of a date is usually faster to read and understand.
     *
     *     $span = $date->fuzzySpan(time() - 10); //less than a minute ago
     * 
     *     $span = $date->fuzzySpan(time(), time() + 86400); //1 day ago 
     *
     * A second parameter is available to manually set the "local" timestamp,
     * however this parameter shouldn't be needed in normal usage and is only
     * included for unit tests
     *
     * @param   integer  $from  UNIX timestamp
     * @param   integer  $to  UNIX timestamp, current timestamp is used when null
     * @return  string
     */
    public function fuzzySpan($from, $to = null);

    /**
     * Returns verbose time interval based on time difference
     * 
     * @staticvar  array    $units
     * @param      integer  $delta  time difference in seconds
     * @return     string
     */
    public function getTimePhrase($delta);

    /**
     * Formats date and time.
     * 
     * @param   mixed   $timestamp  integer, string date representation or NULL
     * @param   string  $format  format string or shorthand, '%x %X' if NULL
     * @return  string
     */
    public function format($timestamp = null, $format = null);

}
