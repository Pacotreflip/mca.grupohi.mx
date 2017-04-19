<?php
/**
 * Created by PhpStorm.
 * User: JFEsquivel
 * Date: 19/04/2017
 * Time: 12:11 PM
 */

namespace App\Models\Traits;


use Jenssegers\Date\Date;

trait DatesTranslator
{
    public function getTimestampAttribute($timestamp) {
        return new Date($timestamp);
    }
}