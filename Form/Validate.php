<?php

namespace Form;

abstract class Validate
{
    const COLOR_HEX = 1;
    const COLOR_RGB = 2;

    /**
     * @param string $str
     * 
     * @return bool
     */
    public static function isStringNotEmpty(string $str): bool
    {
        return !(
            trim((string) $str) === '' ||
            (string) $str === null
        );
    }

    /**
     * @param string $str
     * @param int    $min
     * @param int    $max
     * 
     * @return bool
     */
    public static function textLengthRange(string $str, int $min, int $max): bool
    {
        return \strlen($str) >= $min && \strlen($str) <= $max;
    }

    /**
     * @param mixed $number
     * 
     * @return bool
     */
    public static function isNumber($number): bool
    {
        return is_numeric(trim($number));
    }

    /**
     * @param mixed $number
     * 
     * @return bool
     */
    public static function isNumberPos($number): bool
    {
        return is_numeric(trim($number)) && floatval($number) >= 0;
    }

    /**
     * @param mixed $number
     * 
     * @return bool
     */
    public static function isNumberNeg($number): bool
    {
        return is_numeric(trim($number)) && floatval($number) <= 0;
    }

    /**
     * @param mixed $number
     * 
     * @return bool
     */
    public static function isNumberNz($number): bool
    {
        return is_numeric(trim($number)) && floatval($number) != 0;
    }

    /**
     * @param string $url
     * 
     * @return bool
     */
    public static function isUrl($url): bool
    {
        $rUrl = '/^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b([-a-zA-Z0-9@:%_\+.~#?&\/=]*)$/';
        return preg_match($rUrl, $url) !== false && preg_match($rUrl, $url) !== 0 && !empty($url);
    }

    /**
     * @param string $boolean
     * 
     * @return bool
     */
    public static function isBool($boolean): bool
    {
        $boolean = trim($boolean);

        return (
            $boolean === 'true' || $boolean === 'false' ||
            $boolean === true || $boolean === false ||
            (int) $boolean === 0 || (int) $boolean === 1
        );
    }

    /**
     * @param string $date
     * @param string $pattern
     * 
     * @return bool
     */
    public static function isDate($date, string $pattern='Y-m-d'): bool
    {
        $date = trim($date);
        $d = \DateTime::createFromFormat($pattern, trim($date));
        return $d && $d->format($pattern) === $date;
    }

    /**
     * @param array $arr
     * 
     * @return bool
     */
    public static function isArray($arr): bool
    {
        return is_array($arr);
    }

    /**
     * @param array $arr
     * 
     * @return bool
     */
    public static function isArrayEmpty(array $arr): bool
    {
        return count($arr) === 0;
    }

    /**
     * @param string $color
     * @param int    $type
     * 
     * @return bool
     */
    public static function isColor(string $color, int $type=self::COLOR_HEX): bool
    {
        $color = trim($color);

        switch ($type) {
            case 1:
                $pattern = '/^rgb\(([0-9]{1,3}),\s?([0-9]{1,3}),\s?([0-9]{1,3})\)$/';
                break;
            default:
                $pattern = '/^#(([A-Fa-f0-9]{6})|([A-Fa-f0-9]{3}))$/';
        }

        return preg_match($pattern, $color) === 1;
    }

    /**
     * @param string $email
     * 
     * @return bool
     */
    public static function isEmail(sring $email): bool
    {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false && !empty($email);
    }

    /**
     * @param string $pattern
     * @param string $subject
     * 
     * @return bool
     */
    public static function regex(string $pattern, string $subject): bool
    {
        return preg_match($pattern, $subject) === 1;
    }
}
