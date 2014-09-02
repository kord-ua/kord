<?php

namespace KORD\Crypt\PasswordHash;

use KORD\Crypt\HashInterface;

/**
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Hash
{
    
    /**
     * @var \KORD\Crypt\HashInterface 
     */
    protected $hash;

    /**
     * Create new instance
     * 
     * @param \KORD\Crypt\HashInterface $hash
     */
    public function __construct(HashInterface $hash)
    {
        $this->hash = $hash;
    }

    /**
     * Create a password hash for a given password
     *
     * @param  string $password The password to hash
     * @return string
     */
    public function create($password)
    {
        return $this->hash->compute($password);
    }

    /**
     * Verify a password hash against a given password
     *
     * @param  string $password The password to hash
     * @param  string $hash     The supplied hash to validate
     * @return bool
     */
    public function validate($password, $hash)
    {
        $result = $this->hash->compute($password);
        return ($result === $hash);
    }

}
