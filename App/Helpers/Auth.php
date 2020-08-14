<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-26
 */

namespace App\Helpers;


use App\Config;

class Auth
{
    /**
     * Check Authenticated: Checks to see if the user is authenticated,
     * destroying the session and redirecting to a specific location if the user
     * session doesn't exist.
     * @access public
     * @param string $redirect
     */
    public static function checkAuthenticated($redirect = 'login/index')
    {
        Session::init();
        if (!Session::exists(Config::SESSION_USER)) {
            Session::destroy();
            Redirect::to(APP_URL . $redirect);
        }
    }

    /**
     * Check Unauthenticated: Checks to see if the user is unauthenticated,
     * redirecting to a specific location if the user session exist.
     * @access public
     * @param string $redirect
     */
    public static function checkUnauthenticated($redirect = '')
    {
        Session::init();
        if (Session::exists(Config::SESSION_USER)) {
            Redirect::to(APP_URL . $redirect);
        }
    }
}