<?php

use Carbon\Carbon;

/**
 * Remove dangerous characters from DB search
 *
 * @param $str
 *
 * @return string
 */
function escapeLike($str): string
{
    return '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str) . '%';
}

/**
 * Return true date from 3 ints
 *
 * @param int|null $year
 * @param int|null $month
 * @param int|null $day
 *
 * @return array
 * @throws \App\Exceptions\Http\BadRequestException
 */
function makeTrueDate(?int $year, ?int $month, ?int $day): array
{
    $date = Carbon::createFromDate($year, $month, $day);

    if (isset($day) && is_null($month)) {
        throw new \App\Exceptions\Http\BadRequestException('day cant be without month');
    }

    if (isset($month) && is_null($year)) {
        throw new \App\Exceptions\Http\BadRequestException('month cant be without year');
    }

    return [
        'year'  => is_null($year) ? null : $date->get('year'),
        'month' => is_null($month) ? null : $date->get('month'),
        'day'   => is_null($day) ? null : $date->get('day'),
    ];
}

/**
 * Check if array has integer values
 *
 * @param array $array
 *
 * @return bool
 */
function isArrayInt(array $array): bool
{
    foreach ($array as $arr) {
        if (is_numeric($arr)) {
            if (! is_int((int) $arr)) {
                return false;
            }
        } else {
            return false;
        };
    }

    return true;
}

/**
 * Filter elasticsearch query
 *
 * @param string $string
 *
 * @return string|array|null
 */
function escapeElasticReservedChars(string $string): string|array|null
{
    $regex = "/[\\+\\-\\=\\&\\|\\!\\(\\)\\{\\}\\[\\]\\^\\\"\\~\\*\\<\\>\\?\\:\\\\\\/]/";

    return preg_replace($regex, addslashes('\\$0'), $string);
}

/**
 * Check if keys exists in array
 *
 * @param array $keys
 * @param array $arr
 *
 * @return bool
 */
function array_keys_exists(array $keys, array $arr): bool
{
    return !array_diff_key(array_flip($keys), $arr);
}

/**
 * Delete keys from array
 *
 * @param $array - array
 * @param $keys - keys to be removed
 *
 * @return mixed
 */
function array_except($array, $keys): mixed
{
    foreach ($keys as $key) {
        unset($array[$key]);
    }
    return $array;
}
