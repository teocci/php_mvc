<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-26
 */

namespace App\Helpers;


class Cookie
{
    /**
     * Delete:
     * @access public
     * @param string $key
     * @return void
     */
    public static function delete($key)
    {
        self::put($key, '', time() - 1);
    }

    /**
     * Exists:
     * @access public
     * @param string $key
     * @return boolean
     */
    public static function exists($key)
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * Get: Returns the value of a specific key of the COOKIE super-global
     * @access public
     * @param string $key
     * @return string
     */
    public static function get($key)
    {
        return $_COOKIE[$key];
    }

    /**
     * Put:
     * @access public
     * @param string $key
     * @param string $value
     * @param integer $expiry
     * @return boolean
     */
    public static function put($key, $value, $expiry)
    {
        return setcookie($key, $value, time() + $expiry, '/');
    }
}