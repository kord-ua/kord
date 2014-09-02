<?php

namespace KORD\Crypt;

/**
 * The Hash library provides one-way encryption of text and binary strings
 * using one of the supported algorithms
 * 
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Hash implements HashInterface
{
    
    /**
     * @var array  Supported hash algorithms 
     */
    protected static $supported = [];

    /**
     * Creates a new mcrypt wrapper.
     *
     * @param   string  $algo   Hash algorithm
     * @param   string  $key    HMAC key
     * @param   string  $output_binary When set to true, outputs raw binary data. false outputs lowercase hexits.
     */
    public function __construct($algo = 'sha256', $key = null, $output_binary = false)
    {
        // Store the key, mode, and cipher
        $this->algo = $algo;
        $this->key = $key;
        $this->output = $output_binary;
    }
    
    /**
     * Get supported algorithms
     *
     * @return array
     */
    public function getSupportedAlgorithms()
    {
        if (!empty(Hash::$supported)) {
            return Hash::$supported;
        }
        
        return Hash::$supported = hash_algos();
    }

    /**
     * Is the hash algorithm supported?
     *
     * @param  string $algorithm
     * @return bool
     */
    public function isAlgorithmSupported($algorithm)
    {
        return in_array(strtolower($algorithm), Hash::getSupportedAlgorithms(), true);
    }

    /**
     * Returns a generated hash value
     *
     *     $data = $hash->compute($data);
     *
     * @param   string  $data   data to be hashed
     * @return  string
     */
    public function compute($data)
    {
        if ($this->key) {
            return hash_hmac($this->algo, $data, $this->key, $this->output);
        } else {
            return hash($this->algo, $data, $this->output);
        }
    }
    
    /**
     * Get the output size
     *
     * @return int
     */
    public function getOutputSize()
    {
        return strlen($this->compute('data'));
    }

}
