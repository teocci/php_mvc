<?php
/**
 * Created by teocci.
 *
 * Author: teocci@yandex.com on 2018-Dec-24
 */

namespace App\Helpers;


use App\Config;

class WebCommon
{
    /**
     * Extracts a json request from a POST method
     *
     * @return array or NULL
     */
    public static function getJsonData()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
            if (isset($_POST['data']) && !empty($_POST['data'])) {
                return json_decode($_POST['data'], true);
            }
        }

        $post = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() == JSON_ERROR_NONE && isset($post['data']) && !empty($post['data'])) return $post['data'];

        return null;
    }

    /**
     * Extracts a json request from a POST method
     *
     * @return array or NULL
     */
    public static function getMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
            if (isset($_POST['method']) && !empty($_POST['method'])) {
                return $_POST['method'];
            }
        }

        return null;
    }

    /**
     * @param $data
     * @return array
     */
    public static function packageAsArray($data)
    {
        $json = [
            'hasError' => false,
        ];

        if ($data) {
            $json_data = json_decode($data, true);
            if ($json_data && isset($json_data['errors'])) {
                $json['errors'] = $json_data['errors'];
                $json['hasError'] = true;
            } else {
                $json['data'] = $json_data;
            }
        } else {
            $json['errors'] = [
                'code' => '1001',
                'message' => 'Failed to get contents...',
            ];
            $json['hasError'] = true;
        }

        return $json;
    }

    /**
     * @param $url
     * @param array $params
     * @return string
     */
    public static function getRequest($url, array $params)
    {
        $query_content = http_build_query($params);
        $url .= '?' . $query_content;
        // use key 'http' even if you send the request to https://...
        $options = [
            'http' => [
                'header' => [ // header array does not need '\r\n'
                    'Content-type: application/x-www-form-urlencoded',
                ],
                'method' => 'GET',
                'ignore_errors' => true,
            ]
        ];
        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) return null;

        return $result;
    }

    /**
     * @param $url
     * @param array $params
     * @return string
     */
    public static function postRequest($url, array $params)
    {
        $query_content = http_build_query($params);
        // use key 'http' even if you send the request to https://...
        $options = [
            'http' => [
                'header' => [ // header array does not need '\r\n'
                    'Content-type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($query_content)
                ],
                'method' => 'POST',
                'ignore_errors' => true,
                'content' => $query_content
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) return null;

        return $result;
    }

    /**
     * Escape HTML: Converts all applicable characters to HTML entities.
     *
     * @param string $string
     * @return string
     */
    public static function escapeHTML($string)
    {
        return (htmlentities($string, HTML_ENTITIES_FLAGS, HTML_ENTITIES_ENCODING, HTML_ENTITIES_DOUBLE_ENCODE));
    }

    /**
     * Construct an array with errors information
     *
     * @return array
     */
    public static function initErrorArray()
    {
        $danger = self::escapeHTML(Flash::danger());
        $info = self::escapeHTML(Flash::info());
        $success = self::escapeHTML(Flash::success());
        $warning = self::escapeHTML(Flash::warning());
        $errors = [];

        if ($session_errors = Flash::session(Config::SESSION_ERRORS))
            foreach ($session_errors as $key => $values)
                foreach ($values as $value)
                    $errors[] = self::escapeHTML($value);

        return [
            'danger' => $danger,
            'info' => $info,
            'success' => $success,
            'warning' => $warning,
            'errors' => $errors,
        ];
    }
}