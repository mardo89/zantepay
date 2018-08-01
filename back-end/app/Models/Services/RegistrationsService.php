<?php

namespace App\Models\Services;

use App\Models\DB\ExternalRedirect;
use App\Models\DB\IcoRegistration;
use App\Models\DB\Investor;
use App\Models\DB\NewsLetter;
use App\Models\Wallet\Currency;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
     * Join to newsletter
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

    /**
     * Get newsletter emails
     *
     * @return array
     */
    public static function getNewsLetterInfo()
    {
        $newsLetterInfo = [];

        $newsletters = NewsLetter::orderBy('id', 'desc')->get();

        foreach ($newsletters as $newsletter) {
            $newsLetterInfo[] = [
                'email' => $newsletter->email,
                'joined' => $newsletter->created_at->format('m/d/Y')
            ];
        }

        return $newsLetterInfo;
    }

    /**
     * Export newsletter emails to Excel
     *
     * @return array
     */
    public static function exportNewsLetterInfo()
    {
	    $fileName = 'imports/newsletters_' . AccountsService::getActiveUser()->id . '.csv';

	    Storage::put($fileName, '');

	    $filePath = Storage::path($fileName);

        $newsLetterInfo = self::getNewsLetterInfo();

        $file = fopen($filePath, 'w');

        fputcsv($file, array_keys(reset($newsLetterInfo)));

        foreach($newsLetterInfo as $row)     {
            fputcsv($file, $row);
        }

        fclose($file);

	    $headers = [
		    "Content-type" => "text/csv; application/download",
		    "Content-Disposition" => "attachment;filename=newsletters.csv",
		    "Pragma" => "no-cache",
		    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
		    "Expires" => "0"
	    ];

	    return [
		    'file' => $fileName,
		    'headers' => $headers
	    ];
    }

}
