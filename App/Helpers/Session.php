<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-26
 */

namespace App\Helpers;


class Session
{

    /**
     * Init: Starts the session.
     * @access public
     * @return void
     */
    public static function init()
    {
        // If no session exist, start the session.
        if (session_id() == '') {
            session_start();
        }
    }

    /**
     * Delete: Deletes the value of a specific key of the session.
     * @access public
     * @param string $key
     * @return boolean
     */
    public static function delete($key)
    {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    }

    /**
     * Destroy: Deletes the session.
     * @access public
     * @return void
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * Get: Returns the value of a specific key of the session if it exists.
     * @access public
     * @param string $key
     * @return string|null
     */
    public static function get($key)
    {
        if (self::exists($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    /**
     * Put: Sets a specific value to a specific key of the session.
     * @access public
     * @param string $key
     * @param string $value
     * @return string
     */
    public static function put($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Put: Sets a specific value to a specific key of the session.
     * @access public
     * @param string $key
     * @param array $value
     * @return array
     */
    public static function putArray($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Exists: Checks if a specific key of a session exists.
     * @access public
     * @param string $key
     * @return boolean
     */
    public static function exists($key)
    {
        return isset($_SESSION[$key]);
    }
}