<?php

namespace KORD\Crypt;

/**
 * Encrypt interface
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
interface EncryptInterface
{

    /**
     * Encrypts a string and returns an encrypted string that can be decoded.
     *
     *     $data = $encrypt->encode($data);
     *
     * The encrypted binary data is encoded using [base64](http://php.net/base64_encode)
     * to convert it to a string. This string can be stored in a database,
     * displayed, and passed using most other means without corruption.
     *
     * @param   string  $data   data to be encrypted
     * @return  string
     */
    public function encode($data);

    /**
     * Decrypts an encoded string back to its original value.
     *
     *     $data = $encrypt->decode($data);
     *
     * @param   string  $data   encoded string to be decrypted
     * @return  false   if decryption fails
     * @return  string
     */
    public function decode($data);

}
