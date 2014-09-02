<?php

namespace KORD\Helper;

/**
 * Random helper interface
 * 
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface RandomInterface
{

    /**
     * Generate random bytes using OpenSSL or Mcrypt
     *
     * @param  int $length
     * @return string
     * @throws RuntimeException
     */
    public function bytes($length);
    
    /**
     * Generate random boolean
     *
     * @return bool
     */
    public function boolean();
    
    /**
     * Generate a random integer between $min and $max
     *
     * @param  int $min
     * @param  int $max
     * @return int
     * @throws InvalidArgumentException
     */
    public function integer($min, $max);

    /**
     * Generate random float (0..1)
     * This function generates floats with platform-dependent precision
     *
     * PHP uses double precision floating-point format (64-bit) which has
     * 52-bits of significand precision. We gather 7 bytes of random data,
     * and we fix the exponent to the bias (1023). In this way we generate
     * a float of 1.mantissa.
     *
     * @return float
     */
    public function float();

    
    /**
     * Generate a random string of specified length.
     *
     * Uses supplied character list for generating the new string.
     *
     * @param  int $length
     * @param  string|null $charlist
     * @return string
     */
    public function text($length, $charlist = null);

}
