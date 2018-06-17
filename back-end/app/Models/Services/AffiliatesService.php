<?php

namespace App\Models\Services;


class AffiliatesService
{
    const TRACK_CPL_URL = 'http://runcpa.com/callbacks/event/s2s-partner/PM0QpH3Iu9NmIFX7kqsUoxmGDXSIJhCI/cpl194166/';
	const TRACK_CPA_URL = 'http://runcpa.com/callbacks/events/revenue-partner/PM0QpH3Iu9NmIFX7kqsUoxmGDXSIJhCI/rs126844/';

    /**
     * Track CPL action on RunCPA
     *
     * @param string $trackId
     */
    public static function trackCPL($trackId)
    {

    	self::sendGetRequest(self::TRACK_CPL_URL . $trackId);

    }

	/**
	 * Track CPA action on RunCPA
	 *
	 * @param string $trackId
	 */
	public static function trackCPA($trackId, $sum)
	{

		self::sendGetRequest(self::TRACK_CPA_URL . $trackId . '/' . $sum . '/?currency=eth');

	}

    /**
     * Send GET request
     *
     * @param string $trackId
     *
     * @return void
     */
    protected static function sendGetRequest($url)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_exec($ch);
        curl_close($ch);

    }

}

?>