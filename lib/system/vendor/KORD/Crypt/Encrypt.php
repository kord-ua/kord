<?php

namespace KORD\Crypt;

/**
 * The Encrypt library provides two-way encryption of text and binary strings
 * using the [Mcrypt](http://php.net/mcrypt) extension, which consists of three
 * parts: the key, the cipher, and the mode.
 *
 * The Key
 * :  A secret passphrase that is used for encoding and decoding
 *
 * The Cipher
 * :  A [cipher](http://php.net/mcrypt.ciphers) determines how the encryption
 *    is mathematically calculated. By default, the "rijndael-128" cipher
 *    is used. This is commonly known as "AES-128" and is an industry standard.
 *
 * The Mode
 * :  The [mode](http://php.net/mcrypt.constants) determines how the encrypted
 *    data is written in binary form. By default, the "nofb" mode is used,
 *    which produces short output with high entropy.
 *
 * @copyright  (c) 2007–2014 Kohana Team
 * @copyright  (c) 2014 Andriy Strepetov
 */
class Encrypt implements EncryptInterface
{

    /**
     * @var  string  OS-dependent RAND type to use
     */
    protected static $rand;

    /**
     * Creates a new mcrypt wrapper.
     *
     * @param   string  $key    encryption key
     * @param   string  $mode   mcrypt mode
     * @param   string  $cipher mcrypt cipher
     */
    public function __construct($key, $mode = MCRYPT_MODE_NOFB, $cipher = MCRYPT_RIJNDAEL_128)
    {
        // Find the max length of the key, based on cipher and mode
        $size = mcrypt_get_key_size($cipher, $mode);

        if (isset($key[$size])) {
            // Shorten the key to the maximum size
            $key = substr($key, 0, $size);
        }

        // Store the key, mode, and cipher
        $this->key = $key;
        $this->mode = $mode;
        $this->cipher = $cipher;

        // Store the IV size
        $this->iv_size = mcrypt_get_iv_size($this->cipher, $this->mode);
    }

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
    public function encode($data)
    {
        // Set the rand type if it has not already been set
        if (Encrypt::$rand === null) {
            if (DIRECTORY_SEPARATOR === '\\') {
                // Windows only supports the system random number generator
                Encrypt::$rand = MCRYPT_RAND;
            } else {
                if (defined('MCRYPT_DEV_URANDOM')) {
                    // Use /dev/urandom
                    Encrypt::$rand = MCRYPT_DEV_URANDOM;
                } elseif (defined('MCRYPT_DEV_RANDOM')) {
                    // Use /dev/random
                    Encrypt::$rand = MCRYPT_DEV_RANDOM;
                } else {
                    // Use the system random number generator
                    Encrypt::$rand = MCRYPT_RAND;
                }
            }
        }

        if (Encrypt::$rand === MCRYPT_RAND) {
            // The system random number generator must always be seeded each
            // time it is used, or it will not produce true random results
            mt_srand();
        }

        // Create a random initialization vector of the proper size for the current cipher
        $iv = mcrypt_create_iv($this->iv_size, Encrypt::$rand);

        // Encrypt the data using the configured options and generated iv
        $data = mcrypt_encrypt($this->cipher, $this->key, $data, $this->mode, $iv);

        // Use base64 encoding to convert to a string
        return base64_encode($iv . $data);
    }

    /**
     * Decrypts an encoded string back to its original value.
     *
     *     $data = $encrypt->decode($data);
     *
     * @param   string  $data   encoded string to be decrypted
     * @return  false   if decryption fails
     * @return  string
     */
    public function decode($data)
    {
        // Convert the data back to binary
        $data = base64_decode($data, true);

        if (!$data) {
            // Invalid base64 data
            return false;
        }

        // Extract the initialization vector from the data
        $iv = substr($data, 0, $this->iv_size);

        if ($this->iv_size !== strlen($iv)) {
            // The iv is not the expected size
            return false;
        }

        // Remove the iv from the data
        $data = substr($data, $this->iv_size);

        // Return the decrypted data, trimming the \0 padding bytes from the end of the data
        return rtrim(mcrypt_decrypt($this->cipher, $this->key, $data, $this->mode, $iv), "\0");
    }

}
