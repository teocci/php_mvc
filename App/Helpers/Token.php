<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-26
 */

namespace App\Helpers;


use App\Config;

class Token
{
    /**
     * Generate: Returns a CSRF token and generate a new one if expired.
     * @access public
     * @return string
     */
    public static function generate()
    {
        $tokenSession = Config::SESSION_TOKEN;
        $tokenTimeSession = Config::SESSION_TOKEN_TIME;
        $maxTime = 60 * 60 * 24;

        $token = Session::get($tokenSession);
        $tokenTime = Session::get($tokenTimeSession);
        if ($maxTime + $tokenTime <= time() or empty($token)) {
            Session::put($tokenSession, md5(uniqid(rand(), true)));
            Session::put($tokenTimeSession, time());
        }
        return Session::get($tokenSession);
    }

    /**
     * Check: Checks if the CSRF token stored in the session is same as in the
     * form submitted.
     * @access public
     * @param string $token
     * @return boolean
     */
    public static function check($token)
    {
        return $token === Session::get(Config::SESSION_TOKEN) and !empty($token);
    }
}