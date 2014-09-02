<?php

namespace KORD\Crypt;

/**
 * Hash interface
 * 
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface HashInterface
{

    /**
     * Get supported algorithms
     *
     * @return array
     */
    public function getSupportedAlgorithms();

    /**
     * Is the hash algorithm supported?
     *
     * @param  string $algorithm
     * @return bool
     */
    public function isAlgorithmSupported($algorithm);

    /**
     * Returns a generated hash value
     *
     *     $data = $hash->compute($data);
     *
     * @param   string  $data   data to be hashed
     * @return  string
     */
    public function compute($data);
    
    /**
     * Get the output size
     *
     * @return int
     */
    public function getOutputSize();

}
