<?php
/**
 * Created by Teocci.
 *
 * Author: teocci@yandex.com on 2020-Aug-04
 */

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{
    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'your-database-host';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'your-database-name';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'your-database-user';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'your-database-password';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    const SESSION_ERRORS = 'errors';
    const SESSION_FLASH_DANGER = 'danger';
    const SESSION_FLASH_INFO = 'info';
    const SESSION_FLASH_SUCCESS = 'success';
    const SESSION_FLASH_WARNING = 'warning';
    const SESSION_TOKEN = 'token';
    const SESSION_TOKEN_TIME = 'token_time';
    const SESSION_USER = 'user';
}
