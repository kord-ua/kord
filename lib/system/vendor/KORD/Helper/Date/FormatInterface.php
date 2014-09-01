<?php

namespace KORD\Helper\Date;

/**
 * Date Format interface
 */
interface FormatInterface
{

    /**
     * Formats time
     * 
     * @param  string  $format
     */
    public function format($format = null);

}
