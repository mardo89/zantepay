<?php

namespace App\Models\Wallet;


class EtheriumApi
{

    const API_URL = 'http://172.26.14.50';
    const ACCOUNT_ID = 0;
    const ACCOUNT_PASSWORD = 'pa$$w0rd';
    const ACCOUNT_PORT = 443;


    /**
     * Create Etherium address
     *
     * @param string $userID
     *
     * @return mixed
     */
    public static function createAddress($userID)
    {
        $apiResponse = self::sendPostRequest(
            [
                'userId' => $userID,
                'adminAccount' => [
                    'accountId' => self::ACCOUNT_ID,
                    'password' => self::ACCOUNT_PASSWORD
                ]
            ]
        );

        return isset($apiResponse->operationId) ? $apiResponse->operationId : null;
    }

    /**
     * Get Etherium address
     *
     * @param string $operationID
     *
     * @return mixed
     */
    public static function getAddress($operationID)
    {
        $apiResponse = self::sendGetRequest('/proxy?operationId=' . $operationID);

        return isset($apiResponse->address) ? $apiResponse->address : null;
    }

    /**
     * Get Contributions
     *
     * @param string $token
     * @param int $count
     *
     * @return mixed
     */
    public static function getContributions($token, $count)
    {
        $apiResponse = self::sendGetRequest('contributions?start=' . $token . '&count=' . $count);

        return isset($apiResponse->contributions) ? $apiResponse->contributions : null;
    }

    /**
     * Send GET request
     *
     * @param $params
     *
     * @return array|mixed
     */
    protected static function sendGetRequest($params)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::API_URL . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $apiResponse = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($apiResponse);

        if (!$result) {
            return [];
        }

        return $result;
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

        curl_setopt($ch, CURLOPT_URL, self::API_URL . '/proxy');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $apiResponse = curl_exec($ch);

        curl_close($ch);

        exit(var_dump($apiResponse));
        $result = json_decode($apiResponse);

        if (!$result) {
            return [];
        }

        return $result;
    }

}

?>