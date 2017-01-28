<?php

namespace App\Support;

use ArrayAccess;

class Arr
{
    /**
     * Check if iterateable
     *
     * @param array|ArrayAccess $value
     *
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Check if key exists
     *
     * @param array|ArrayAccess $array
     * @param string            $key
     *
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Get array item
     *
     * @param array|ArrayAccess $array
     * @param string            $key
     * @param null $default
     *
     * @return null|string|int
     */
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

    /**
     * Get first array item
     *
     * @param array|ArrayAccess $array
     * @param callable|null     $callback
     * @param null              $default
     *
     * @return mixed|null
     */
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

    /**
     * Get last array item
     *
     * @param array|ArrayAccess $array
     * @param callable|null     $callback
     * @param null              $default
     *
     * @return mixed|null
     */
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

    /**
     * Check if array has key or keys
     *
     * @param array|ArrayAccess $array
     * @param string|array      $key
     *
     * @return bool
     */
    public static function has($array, $key)
    {
        if (null === $key) {
            return false;
        }

        $keys = (array)$key;

        if (empty($keys)) {
            return false;
        }

        foreach ($keys as $key) {
            $subKey = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKey) && static::exists($subKey, $segment)) {
                    $subKey = $subKey[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Filters elements of an array using a callback function
     *
     * @param array|ArrayAccess $array
     * @param callable|null     $callback
     *
     * @return array
     */
    public static function where($array, callable $callback = null)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Return array based on specific keys
     *
     * @param array|ArrayAccess $array
     * @param string|array      $key
     *
     * @return array
     */
    public static function only($array, $key)
    {
        return array_intersect_key($array, array_flip((array)$key));
    }

    /**
     * Remove array items based on specific keys
     * 
     * @param $array
     * @param $keys
     */
    public static function forget(&$array, $keys)
    {
        $origin = &$array;

        $keys = (array)$keys;

        foreach ($keys as $key) {
            if (static::exists($array, $key)) {
                unset($array[$key]);
                continue;
            }

            $parts = explode('.', $key);

            $array = &$origin;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (static::accessible($array) && static::exists($array, $part)) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }
}
