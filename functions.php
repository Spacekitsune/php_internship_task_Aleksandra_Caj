<?php

function create_booking_id($array)
{
    if (empty($array)) {
        $id = 0;
    } else {
        $id = $array[count($array) - 1]['booking_id'] + 1;
    }
    return $id;
}

function is_date_ok($string)
{
    $date = strtotime($string);
    $string = date('Y-m-d H:i', $date);

    if (strlen($string) == 16) {
        $today = date('Y-m-d H:i');
        if (new DateTime($today) < new DateTime($string)) {

            $diff = strtotime($today) - strtotime($string);
            $diff = ceil(abs($diff / 86400));

            if ($diff <= 30) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}


