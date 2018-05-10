<?php

namespace App\Models\Services;

use App\Models\DB\ExternalRedirect;
use Illuminate\Support\Facades\Session;

class RedirectsService
{
    /**
     * Remove user's Invites
     *
     * @param string $email
     * @param int $action
     */
    public static function trackRedirect($email, $action)
    {
        $externalLink = Session::get('externalLink');

        if ($externalLink) {
            return;
        }

        ExternalRedirect::create(
            [
                'external_link' => $externalLink,
                'email' => $email,
                'action' => $action
            ]
        );

    }

}
