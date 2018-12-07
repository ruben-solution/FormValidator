<?php

namespace FormValidator;

/**
 * @author Ruben Allenspach <ruben.allenspach@solution.ch>
 */
abstract class Validate
{
    const COLOR_HEX  = 1;
    const COLOR_RGB  = 2;
    const COLOR_RGBA = 3;
    const COLOR_HSL  = 4;
    const COLOR_HSLA = 5;

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
        $pattern = '/^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b([-a-zA-Z0-9@:%_\+.~#?&\/=]*)$/';
        return preg_match($pattern, $url) !== false && preg_match($pattern, $url) !== 0 && !empty($url);
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
        $dateTime = \DateTime::createFromFormat($pattern, trim($date));
        return $dateTime && $dateTime->format($pattern) === $date;
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
            case self::COLOR_HEX:
                $pattern = '/^#?(([A-Fa-f0-9]{6})|([A-Fa-f0-9]{3}))$/';
                break;
            case self::COLOR_RGB:
                $pattern = '/^rgb\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\)$|^(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)$/';
                break;
            case self::COLOR_RGBA:
                $pattern = '/^rgba\((0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0?\.\d|1(\.0)?)\)$|^(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0|255|25[0-4]|2[0-4]\d|1\d\d|0?\d?\d)\s*,\s*(0?\.\d|1(\.0)?)$/';
                break;
            case self::COLOR_HSL:
                $pattern = '/^hsl\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d)\s*,\s*(0|100|\d{1,2})%\s*,\s*(0|100|\d{1,2})%\)$|^(0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d)\s*,\s*(0|100|\d{1,2})%\s*,\s*(0|100|\d{1,2})%$/';
                break;
            case self::COLOR_HSLA:
                $pattern = '/^hsla\((0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d)\s*,\s*(0|100|\d{1,2})%\s*,\s*(0|100|\d{1,2})%\s*,\s*(0?\.\d|1(\.0)?)\)$|^(0|360|35\d|3[0-4]\d|[12]\d\d|0?\d?\d)\s*,\s*(0|100|\d{1,2})%\s*,\s*(0|100|\d{1,2})%\s*,\s*(0?\.\d|1(\.0)?)$/';
                break;
            default:
                return false;
        }

        return preg_match($pattern, $color) === 1;
    }

    /**
     * @param string $email
     * 
     * @return bool
     */
    public static function isEmail(string $email): bool
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
