<?php

namespace App\Models\Services;

use App\Models\DB\ExternalRedirect;
use App\Models\DB\IcoRegistration;
use App\Models\DB\Investor;
use App\Models\DB\NewsLetter;
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

        RedirectsService::trackRedirect(
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

        RedirectsService::trackRedirect(
            $email,
            ExternalRedirect::ACTION_TYPE_REGISTRATION_INVESTOR
        );
    }

    /**
     * Join to news letters
     *
     * @param string $email
     */
    public static function joinToNewsLetter($email)
    {
        $accountExists = NewsLetter::where('email', $email)->count() > 0;

        if ($accountExists) {
            return;
        }

        NewsLetter::create(
            [
                'email' => $email,
            ]
        );

        ExternalRedirect::addLink(
            Session::get('externalLink'),
            $email,
            ExternalRedirect::ACTION_TYPE_REGISTRATION_NEWSLETTER
        );
    }

}
