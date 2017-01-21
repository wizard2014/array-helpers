<?php

namespace App\Support;

use ArrayAccess;

class Arr
{
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return $default;
        }

        if (null === $key) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function first($array, callable $callback = null, $default = null)
    {
        if (null === $callback) {
            if (empty($array)) {
                return $default;
            }

            return reset($array);
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    public static function last($array, callable $callback = null, $default = null)
    {
        if (null === $callback) {
            if (empty($array)) {
                return $default;
            }

            return end($array);
        }

        return static::first(array_reverse($array, true), $callback, $default);
    }
}
