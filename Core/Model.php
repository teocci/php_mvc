<?php
/**
 * Created by Teocci.
 *
 * Author: teocci@yandex.com on 2020-Jun-28
 */

namespace Core;

use PDO;
use App\Config;
use PDOStatement;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{
    /**
     * Get the PDO database connection
     *
     * @return PDO
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $name = Config::DB_NAME;
            $host = Config::DB_HOST;
            $username = Config::DB_USERNAME;
            $password = Config::DB_PASSWORD;

            $dsn = 'pgsql:dbname=' . $name . ';host=' . $host;
            $db = new PDO($dsn, $username, $password);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

    /**
     * Get the PDO database connection
     *
     * @param $query
     * @param null $params
     * @return PDOStatement
     */
    protected static function execQuery($query, $params = null)
    {
        if ($query) {
            $db = static::getDB();
            $stmt = $db->prepare($query);
            $stmt->execute($params);

            return $stmt;
        }

        return null;
    }
}
