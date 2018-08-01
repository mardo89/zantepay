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
     * Verification statuses
     */
    const VERIFICATION_PENDING = 0;
    const VERIFICATION_IN_PROGRESS = 1;
    const VERIFICATION_SUCCESS = 2;
    const VERIFICATION_FAILED = 3;

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
    protected $hidden = [];

    /**
     * Get user
     */
    public function user() {
        return $this->belongsTo('App\Models\DB\User', 'user_id', 'id');
    }

}
