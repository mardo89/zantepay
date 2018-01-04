<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    /**
     * Document statuses
     */
    const DOCUMENTS_NOT_UPLOADED = 0;
    const DOCUMENTS_UPLOADED = 1;
    const DOCUMENTS_APPROVED = 2;
    const DOCUMENTS_DECLINED = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Return status name
     *
     * @param int $status
     *
     * @return string
     */
    public static function getStatus($status) {
        switch ($status) {
            case self::DOCUMENTS_NOT_UPLOADED:
                return 'Documents not uploaded';

            case self::DOCUMENTS_UPLOADED:
                return 'Pending approval';

            case self::DOCUMENTS_APPROVED:
                return 'Documents approved';

            case self::DOCUMENTS_DECLINED:
                return 'Documents declined';

            default:
                return '';
        }
    }

}
