<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /**
     * Document types
     */
    const DOCUMENT_TYPE_IDENTITY = 0;
    const DOCUMENT_TYPE_ADDRESS = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'document_type', 'did','file_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
