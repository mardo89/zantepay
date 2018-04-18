<?php

namespace App\Models\Services;

use App\Models\DB\ExternalRedirect;
use App\Models\DB\IcoRegistration;
use App\Models\DB\Investor;
use App\Models\Wallet\Currency;
use Illuminate\Support\Facades\Session;

class RegistrationsService
{
    /**
     * Register for Pre-ICO
     *
     * @param string $email
     * @param int $amount
     */
    public static function registerForPreIco($email, $amount)
    {
        $currency = WalletsService::getCurrency(Currency::CURRENCY_TYPE_ETH);

        IcoRegistration::create(
            [
                'email' => $email,
                'currency' => $currency,
                'amount' => $amount,
            ]
        );

        ExternalRedirect::addLink(
            Session::get('externalLink'),
            $email,
            ExternalRedirect::ACTION_TYPE_REGISTRATION_ICO
        );

        MailService::sendIcoRegistrationEmail($email);

        MailService::sendIcoRegistrationAdminEmail($email, $currency, $amount);
    }

    /**
     * Become Investor
     *
     * @param string $email
     * @param string $skype
     * @param string $firstName
     * @param string $lastName
     */
    public static function becomeInvestor($email, $skype, $firstName, $lastName)
    {
        Investor::create(
            [
                'email' => $email,
                'skype_id' => $skype,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ]
        );

        ExternalRedirect::addLink(
            Session::get('externalLink'),
            $email,
            ExternalRedirect::ACTION_TYPE_REGISTRATION_INVESTOR
        );
    }

}
