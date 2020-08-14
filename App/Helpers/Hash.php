<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-26
 */

namespace App\Helpers;


class Hash
{
    /**
     * Generate: Returns a generate a hash value, as a string.
     * @access public
     * @param string $string
     * @param string $salt [optional]
     * @return string
     */
    public static function generate($string, $salt = '')
    {
        return (hash('sha256', $string . $salt));
    }

    /**
     * Generate Salt: Returns a cryptographic salt of a specified length, as a
     * string.
     * @access public
     * @param integer $length
     * @return string
     */
    public static function generateSalt($length)
    {
        $salt = '';
        $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'\";:?.>,<!@#$%^&*()-_=+|';
        for ($i = 0; $i < $length; $i++) {
            $salt .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
        return $salt;
    }

    /**
     * Generate Unique: Returns a unique identifier, as a string.
     * @access public
     * @return string
     */
    public static function generateUnique()
    {
        return (self::generate(uniqid()));
    }
}