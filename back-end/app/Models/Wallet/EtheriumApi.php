<?php

namespace App\Models\Wallet;


class EtheriumApi
{
    const API_URL = 'https://test.ethereum.node.zantepay.com';
    const API_PORT = 443;

    const USER_ACCOUNT_ID = 0;
    const USER_ACCOUNT_PASSWORD = 'pa$$w0rd';

    const ADMIN_ACCOUNT_ID = 1;
    const ADMIN_ACCOUNT_PASSWORD = 'C0inPa$$word';

    /**
     * Create Etherium Operation ID to generate Address
     *
     * @param string $userID
     *
     * @return string
     * @throws \Exception
     */
    public static function getAddressOID($userID)
    {
        $apiResponse = self::sendPostRequest(
            '/proxy',
            [
                'userId' => $userID,
            ],
            [
                'Content-Type: application/json',
                'X-ZantePay-Admin-AccountId: ' . self::USER_ACCOUNT_ID,
                'X-ZantePay-Admin-Password: ' . self::USER_ACCOUNT_PASSWORD,
            ]
        );

        if (!isset($apiResponse['data']) || !isset($apiResponse['data']->operationId)) {
            throw new \Exception('Error getting operation ID');
        }

        return $apiResponse['data']->operationId;
    }

    /**
     * Create Etherium address
     *
     * @param string $operationID
     *
     * @return string
     * @throws \Exception
     */
    public static function createAddress($operationID)
    {
        $address = null;
        $requestsCount = 0;

        while ($operationID == '' || $requestsCount < 30) {
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

        if (is_null($address)) {
            throw new \Exception('Error getting address');
        }

        return $address;
    }

    /**
     * Get Contributions
     *
     * @param string $continuationStartToken
     * @param string $continuationEndToken
     *
     * @return array
     * @throws \Exception
     */
    public static function getContributions($continuationStartToken = null, $continuationEndToken = null)
    {
        $params = [];

        $params[] = is_null($continuationStartToken) ? '' : 'start=' . $continuationStartToken;
        $params []= is_null($continuationEndToken) ? '' : 'end=' . $continuationEndToken;

        $apiResponse = self::sendGetRequest(
            '/contributions',
            implode('&', $params)
        );

        if (!isset($apiResponse['data']) || !isset($apiResponse['data']->contributions) || !isset($apiResponse['data']->continuation_token)) {
            throw new \Exception('Error receiving contributions');
        }

        return [
            'contributions' => $apiResponse['data']->contributions,
            'continuation_token' => $apiResponse['data']->continuation_token
        ];
    }


    /**
     * Grand ICO coins
     *
     * @param int $amount
     *
     * @return string
     * @throws \Exception
     */
    public static function grantICOCoins($amount) {
        $operationID = self::getCoinsOID('ico', $amount);

        return self::checkCoinsStatus($operationID);
    }

    /**
     * Marketing coins
     *
     * @param int $amount
     *
     * @return string
     * @throws \Exception
     */
    public static function marketingCoins($amount) {
        $operationID = self::getCoinsOID('marketing', $amount);

        return self::checkCoinsStatus($operationID);
    }

    /**
     * Company coins
     *
     * @param int $amount
     *
     * @return string
     * @throws \Exception
     */
    public static function companyCoins($amount) {
        $operationID = self::getCoinsOID('company', $amount);

        return self::checkCoinsStatus($operationID);
    }

    /**
     * Create Etherium Operation ID to Grand ICO/Marketing/Company
     *
     * @param string $grantType
     * @param int $amount
     *
     * @return string
     * @throws \Exception
     */
    protected static function getCoinsOID($grantType, $amount)
    {
        $apiResponse = self::sendPostRequest(
            '/coins',
            [
                'grantType' => $grantType,
                'amount' => $amount
            ],
            [
                'Content-Type: application/json',
                'X-ZantePay-Admin-AccountId: ' . self::ADMIN_ACCOUNT_ID,
                'X-ZantePay-Admin-Password: ' . self::ADMIN_ACCOUNT_PASSWORD,
            ]
        );

        if (!isset($apiResponse['data']) || !isset($apiResponse['data']->operationId)) {
            throw new \Exception('Error getting operation ID');
        }

        return $apiResponse['data']->operationId;
    }

    /**
     * Create Etherium address
     *
     * @param string $operationID
     *
     * @return string
     * @throws \Exception
     */
    protected static function checkCoinsStatus($operationID)
    {
        $status = null;
        $requestsCount = 0;

        while ($operationID == '' || $requestsCount < 30) {
            $apiResponse = self::sendGetRequest(
                '/operation/' . $operationID
            );

            if ($apiResponse['status'] == 400) {
                break;
            }

            if ($apiResponse['status'] == 200 && isset($apiResponse['data']->status)) {
                $status = $apiResponse['data']->status;
                break;
            }

            $requestsCount++;

            sleep(10);
        }

        if (is_null($status)) {
            throw new \Exception('Error sending coins');
        }

        return $status;
    }


    /**
     * Send GET request
     *
     * @param string $endpoint
     * @param string $params
     *
     * @return array|mixed
     */
    protected static function sendGetRequest($endpoint, $params = '')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::API_URL . $endpoint . '?' . $params);
        curl_setopt($ch, CURLOPT_PORT, self::API_PORT);
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
        curl_setopt($ch, CURLOPT_PORT, self::API_PORT);
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