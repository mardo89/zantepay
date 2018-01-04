<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class DebitCard extends Model
{
    /**
     * Not selected
     */
    const DESIGN_NOT_SELECTED = 0;

    /**
     * White Design
     */
    const DESIGN_WHITE = 1;

    /**
     * Red Design
     */
    const DESIGN_RED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'design'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Return card design name
     *
     * @param int $design
     *
     * @return string
     */
    public static function getDesign($design) {
        switch ($design) {
            case self::DESIGN_WHITE:
                return 'White';

            case self::DESIGN_RED:
                return 'Red';

            case self::DESIGN_NOT_SELECTED:
                return 'Not selected';

            default:
                return '';
        }
    }

    /**
     * Return card design list
     *
     * @return array
     */
    public static function getCardsList() {
        return [
            [
                'id' => self::DESIGN_NOT_SELECTED,
                'name' => self::getDesign(self::DESIGN_NOT_SELECTED)
            ],
            [
                'id' => self::DESIGN_WHITE,
                'name' => self::getDesign(self::DESIGN_WHITE)
            ],
            [
                'id' => self::DESIGN_RED,
                'name' => self::getDesign(self::DESIGN_RED)
            ]
        ];
    }

}
