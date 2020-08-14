<?php
/**
 * Created by Teocci.
 *
 * Author: teocci@yandex.com on 2020-Aug-05
 */

namespace App\Helpers;


class HTTPRequester
{
    /**
     * @description Make HTTP-GET call
     * @param string $url
     * @param array $params
     * @return bool|string HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPGet($url, array $params)
    {
        $query = http_build_query($params);
        $ch = curl_init($url . '?' . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * @description Make HTTP-POST call
     * @param string $url
     * @param array $params
     * @return bool|string HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPPost($url, array $params)
    {
        $query = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * @description Make HTTP-PUT call
     * @param string $url
     * @param array $params
     * @return bool|string HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPPut($url, array $params)
    {
        $query = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * @param $url
     * @param array $params
     * @return bool|string HTTP-Response body or an empty string if the request fails or is empty
     * @category Make HTTP-DELETE call
     */
    public static function HTTPDelete($url, array $params)
    {
        $query = http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}