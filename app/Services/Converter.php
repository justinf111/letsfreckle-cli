<?php
/**
 * Created by PhpStorm.
 * User: Justin Favaloro
 * Date: 14/07/2015
 * Time: 1:26 PM
 */

namespace App\Services;


class Converter {
    public function toMinutes($time) {
        $parsedTime = date_parse($time);
        $minutes = $parsedTime['hour'] * 60 + $parsedTime['minute'];
        return $minutes;
    }

    public function toHoursAndMinutes($minutes, $format = 'H:i') {
        return date($format, mktime(0, $minutes));
    }
}