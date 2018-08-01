<?php

namespace App\Models\Services;


use App\Exceptions\CaptchaException;

class CaptchaService
{
    /**
     * URL to check captcha
     */
    const API_URL = "https://www.google.com/recaptcha/api/siteverify";

    /**
     * Check captcha
     *
     * @param string $response
     * @param string $ip
     *
     * @throws
     */
    public static function checkCaptcha($response)
    {

        $apiResponse = self::sendPostRequest(
            [
                'secret' => env('CAPTCHA_SECRET'),
                'response' => $response
            ]
        );

        if (!isset($apiResponse->success) || $apiResponse->success === false) {
            throw new CaptchaException();
        }
    }


    /**
     * Send POST request
     *
     * @param array $params
     *
     * @return mixed
     */
    protected static function sendPostRequest($params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);

        $apiResponse = curl_exec($ch);

        $responseData = json_decode($apiResponse);

        curl_close($ch);

        return $responseData;
    }

}
