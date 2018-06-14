<?php

namespace App\Models\Services;


class AffiliatesService
{
    const TRACK_URL = 'http://runcpa.com/callbacks/event/s2s-partner/PM0QpH3Iu9NmIFX7kqsUoxmGDXSIJhCI/cpl194166/';

    /**
     * Register affiliate user on RunCPA
     *
     * @param string $trackId
     */
    public static function registerAffiliate($trackId)
    {

    	self::sendGetRequest($trackId);

    }

    /**
     * Send GET request
     *
     * @param string $trackId
     *
     * @return void
     */
    protected static function sendGetRequest($trackId)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::TRACK_URL . $trackId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_exec($ch);
        curl_close($ch);

    }

}

?>