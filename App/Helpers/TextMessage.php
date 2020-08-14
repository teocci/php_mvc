<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-27
 */

namespace App\Helpers;


class TextMessage
{
    const TEXT_MESSAGES = [
        //
        // Login Model Texts
        // =====================================================================
        'LOGIN_INVALID_PASSWORD' => 'The Admin Id / Pwd combination you have entered is incorrect',
        'LOGIN_USER_NOT_FOUND' => 'The Admin Id you have entered has not been found!',
        'LOGIN_INSTITUTION_NOT_FOUND' => 'The Institution Id you have entered has not been found!',
        '' => '',
        //
        // Register Model Texts
        // =====================================================================
        'REGISTER_USER_CREATED' => 'Your account has been successfully created!',
        '' => '',
        //
        // User Model Texts
        // =====================================================================
        'USER_CREATE_EXCEPTION' => 'There was a problem creating this account!',
        'USER_UPDATE_EXCEPTION' => 'There was a problem updating this account!',
        '' => '',
        //
        // Input Utility Texts
        // =====================================================================
        'INPUT_INCORRECT_CSRF_TOKEN' => 'Cross-site request forgery verification failed!',
        '' => '',
        //
        // Validate Utility Texts
        // =====================================================================
        'VALIDATE_FILTER_RULE' => '%ITEM% is not a valid %RULE_VALUE%!',
        'VALIDATE_MISSING_INPUT' => 'Unable to validate %ITEM%!',
        'VALIDATE_MISSING_METHOD' => 'Unable to validate %ITEM%!',
        'VALIDATE_MATCHES_RULE' => '%RULE_VALUE% must match %ITEM%.',
        'VALIDATE_MAX_CHARACTERS_RULE' => '%ITEM% can only be a maximum of %RULE_VALUE% characters.',
        'VALIDATE_MIN_CHARACTERS_RULE' => '%ITEM% must be a minimum of %RULE_VALUE% characters.',
        'VALIDATE_REQUIRED_RULE' => '%ITEM% is required!',
        'VALIDATE_UNIQUE_RULE' => '%ITEM% already exists.',
        '' => '',
        //
        // Texts
        // =====================================================================
        '' => '',
    ];

    /**
     * Get: Returns the value of a specific key from the texts array in the
     * application configuration file if it exists, otherwise an empty string is
     * returned.
     * @access public
     * @param string $key
     * @param array $data [optional]
     * @return string
     */
    public static function get($key, array $data = [])
    {
        if (array_key_exists($key, self::TEXT_MESSAGES)) {
            $text = self::TEXT_MESSAGES[$key];
            foreach ($data as $search => $replace) {
                $text = str_replace($search, $replace, $text);
            }
            return $text;
        }
        return null;
    }
}