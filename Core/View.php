<?php
/**
 * Created by Teocci.
 *
 * Author: teocci@yandex.com on 2020-Jun-28
 */

namespace Core;

use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{
    /**
     * Render a view file
     *
     * @param string $view The view file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return void
     * @throws Exception
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new Environment($loader);
            $twig->addFilter(new TwigFilter('cast_to_array', function ($stdClassObject) {
                return (array)$stdClassObject;
            }));
        }

        echo $twig->render($template, $args);
    }


    /**
     * Render a json response
     *
     * @param array $data Associative array of data to be encoded as json.
     *
     * @return void
     */
    public static function renderJson($data)
    {
        if ($data) {
            header('Content-Type: application/json');
            die(json_encode($data));
        }
    }
}
