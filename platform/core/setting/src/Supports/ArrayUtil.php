<?php

namespace Botble\Setting\Supports;

use UnexpectedValueException;

class ArrayUtil
{

    /**
     * Get an element from an array.
     *
     * @param  array $data
     * @param  string $key Specify a nested element by separating keys with full stops.
     * @param  mixed $default If the element is not found, return this.
     *
     * @return mixed
     * @author Andreas Lutro
     */
    public static function get(array $data, $key, $default = null)
    {
        if ($key === null) {
            return $data;
        }

        if (is_array($key)) {
            return static::getArray($data, $key, $default);
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($data)) {
                return $default;
            }

            if (!array_key_exists($segment, $data)) {
                return $default;
            }

            $data = $data[$segment];
        }

        return $data;
    }

    /**
     * @param array $input
     * @param $keys
     * @param null $default
     * @return array
     * @author Andreas Lutro
     */
    protected static function getArray(array $input, $keys, $default = null)
    {
        $output = [];

        foreach ($keys as $key) {
            static::set($output, $key, static::get($input, $key, $default));
        }

        return $output;
    }

    /**
     * Determine if an array has a given key.
     *
     * @param  array $data
     * @param  string $key
     *
     * @return boolean
     * @author Andreas Lutro
     */
    public static function has(array $data, $key)
    {
        foreach (explode('.', $key) as $segment) {
            if (!is_array($data)) {
                return false;
            }

            if (!array_key_exists($segment, $data)) {
                return false;
            }

            $data = $data[$segment];
        }

        return true;
    }

    /**
     * Set an element of an array.
     *
     * @param array $data
     * @param string $key Specify a nested element by separating keys with full stops.
     * @param mixed $value
     * @author Andreas Lutro
     */
    public static function set(array &$data, $key, $value)
    {
        $segments = explode('.', $key);

        $key = array_pop($segments);

        // iterate through all of $segments except the last one
        foreach ($segments as $segment) {
            if (!array_key_exists($segment, $data)) {
                $data[$segment] = [];
            } elseif (!is_array($data[$segment])) {
                throw new UnexpectedValueException('Non-array segment encountered');
            }

            $data =& $data[$segment];
        }

        $data[$key] = $value;
    }

    /**
     * Unset an element from an array.
     *
     * @param  array &$data
     * @param  string $key Specify a nested element by separating keys with full stops.
     * @author Andreas Lutro
     */
    public static function forget(array &$data, $key)
    {
        $segments = explode('.', $key);

        $key = array_pop($segments);

        // iterate through all of $segments except the last one
        foreach ($segments as $segment) {
            if (!array_key_exists($segment, $data)) {
                return;
            } elseif (!is_array($data[$segment])) {
                throw new UnexpectedValueException('Non-array segment encountered');
            }

            $data =& $data[$segment];
        }

        unset($data[$key]);
    }
}
