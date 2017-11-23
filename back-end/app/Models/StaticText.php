<?php
namespace App\Models;

class StaticText {

    /**
     * Return Invitation status name
     *
     * @param int $status
     *
     * @return string
     */
    public static function getInvitationStatus($status) {
        switch ($status) {
            case INVITATION_STATUS_PENDING:
                return 'Invitation pending';

            case INVITATION_STATUS_VERIFYING:
                return 'Verification not finished';

            case INVITATION_STATUS_COMPLETE:
                return 'Signed up!';

            default:
                return 'Invitation pending';
        }
    }

    /**
     * Return Invitation status name
     *
     * @param int $currency
     *
     * @return string
     */
    public static function getCurrencyType($currency) {
        switch ($currency) {
            case CURRENCY_TYPE_BTC:
                return 'BTC';

            case CURRENCY_TYPE_ETH:
                return 'ETH';
        }
    }

}
?>