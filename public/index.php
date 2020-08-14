<?php
/**
 * Created by Teocci.
 *
 * Author: teocci@yandex.com on 2020-Jun-28
 *
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use Core\Error;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

define('EOL', "\n");

define('APP_PROTOCOL', stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://');
define('APP_URL', APP_PROTOCOL . $_SERVER['HTTP_HOST'] . str_replace('public', '', dirname($_SERVER['SCRIPT_NAME'])));

define('HTML_ENTITIES_FLAGS', ENT_QUOTES);
define('HTML_ENTITIES_ENCODING', 'UTF-8');
define('HTML_ENTITIES_DOUBLE_ENCODE', false);

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('api/{controller}/{action}');
$router->add('api/{controller}/update/{action}');
$router->add('{controller}/{action}');

try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
    try {
        Error::exceptionHandler($e);
    } catch (LoaderError $e) {
    } catch (RuntimeError $e) {
    } catch (SyntaxError $e) {
        echo '<b>Exception:</b> ', $e->getMessage();
    }
}
