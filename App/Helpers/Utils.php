<?php
/**
 * Created by Teocci.
 *
 * Author: teocci@yandex.com on 2020-Aug-06
 */

namespace App\Helpers;


class Utils
{
    /**
     * @param $array
     */
    public static function  prettyPrint($array) {
        echo "<pre>";
        var_dump($array);
        echo "</pre>";
    }
}