<?php

namespace entero\helpers;


use function array_keys;
use function rand;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     *
     * @param $array
     * @param $keys
     *
     * @return array
     */
    public static function getByKeys($array, $keys)
    {
        return array_intersect_key($array, array_flip($keys));
    }

    public static function valueExist($array, $value, $column = false)
    {
        $flag = false;
        $array = $column ? static::getColumn($array, $column) : $array;
        foreach ($array as $item) {
            if ($item == $value) {
                $flag = true;
            }
        }

        return $flag;
    }

    public static function range($array, $start, $end)
    {
        $out = [];
        $insert = false;
        foreach ($array as $key => $value) {
            if ($key == $start) {
                $insert = true;
            }
            if ($insert) {
                $out[$key] = $value;
            }
            if ($key == $end) {
                $insert = false;
            }
        }

        return $out;
    }

    public static function getRow($array, $keyValue, $key, $default = false)
    {
        foreach ($array as $row) {
            if ($row[$key] == $keyValue) {
                return $row;
            }
        }

        return $default;
    }

    public static function recursiveFind($array, $key)
    {
        $items = [];

        if (isset($array[$key])) {

            foreach ($array[$key] AS $item) {
                $items[] = $item;
            }
        } else {
            if (is_array($array)) {
                foreach ($array as $item) {
                    $items = ArrayHelper::merge($items, static::recursiveFind($item, $key));
                }
            }

        }

        return $items;
    }

    public static function isEmpty($array)
    {
        $array = array_filter($array);

        return empty($array);
    }

    public static function convertToObject($array)
    {
        $object = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = static::convertToObject($value);
            }
            $object->$key = $value;
        }

        return $object;
    }

    public static function randomValue($array)
    {
        return $array[mt_rand(0, count($array) - 1)];
    }

    public static function randomKey($array)
    {
        $keys = array_keys($array);

        return static::randomValue($keys);

    }

    public static function ensure(&$array)
    {
        if (!is_array($array)) {
            $array = [$array];
        }
    }
}