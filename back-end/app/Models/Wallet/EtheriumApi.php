<?php

namespace App\Models\Wallet;


class EtheriumApi
{

    const API_URL = 'https://test.ethereum.node.zantepay.com';
    const ACCOUNT_ID = 0;
    const ACCOUNT_PASSWORD = 'pa$$w0rd';
    const ACCOUNT_PORT = 443;


    /**
     * Create Etherium address
     *
     * @param string $userID
     *
     * @return mixed
     * @throws \Exception
     */
    public static function createAddress($userID)
    {
        // Get Operation ID
        $apiResponse = self::sendPostRequest(
            '/proxy',
            [
                'userId' => $userID,
            ],
            [
                'Content-Type: application/json',
                'X-ZantePay-Admin-AccountId: ' . self::ACCOUNT_ID,
                'X-ZantePay-Admin-Password: ' . self::ACCOUNT_PASSWORD,
            ]
        );

        $operationID = $apiResponse['data']->operationId;

        if (!isset($apiResponse['data']->operationId)) {
            throw new \Exception('Error getting operative ID');
        }

        // Get Address
        $address = '';
        $requestsCount = 0;

        while ($operationID == '' || $requestsCount < 20) {
            $apiResponse = self::sendGetRequest(
                '/proxy',
                'operationId=' . $operationID
            );

            if ($apiResponse['status'] == 400) {
                break;
            }

            if ($apiResponse['status'] == 201 && isset($apiResponse['data']->address)) {
                $address = $apiResponse['data']->address;
                break;
            }

            $requestsCount++;

            sleep(10);
        }

        if ($address == '') {
            throw new \Exception('Error getting address');
        }

        return $address;
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
        $apiResponse = self::sendGetRequest(
            '/contributions',
            'start=' . $token . '&count=' . $count
        );

        return isset($apiResponse->contributions) ? $apiResponse->contributions : null;
    }

    /**
     * Send GET request
     *
     * @param string $endpoint
     * @param string $params
     *
     * @return array|mixed
     */
    protected static function sendGetRequest($endpoint, $params)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::API_URL . $endpoint . '?' . $params);
        curl_setopt($ch, CURLOPT_PORT, self::ACCOUNT_PORT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $apiResponse = curl_exec($ch);

        $responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseData = json_decode($apiResponse);

        curl_close($ch);

        if (!$responseData) {
            return [
                'status' => 400
            ];
        }

        return [
            'status' => (int)$responseStatus,
            'data' => $responseData
        ];
    }

    /**
     * Send POST request
     *
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     *
     * @return mixed
     */
    protected static function sendPostRequest($endpoint, $params, $headers = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $endpoint);
        curl_setopt($ch, CURLOPT_PORT, self::ACCOUNT_PORT);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $apiResponse = curl_exec($ch);

        $responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $responseData = json_decode($apiResponse);

        curl_close($ch);

        if (!$responseData) {
            return [
                'status' => 400
            ];
        }

        return [
            'status' => (int)$responseStatus,
            'data' => $responseData
        ];
    }

}

?>