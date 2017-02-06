<?php

namespace Formigone;

/**
 * Class Chain
 *
 * A thin abstraction over PHP arrays to help you easily chain array transformation functions.
 *
 * @package Formigone
 */
class Chain
{
    protected $arr;

    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }

    /**
     * Returns the internal array structure as is (keys might be all weird due to filtering), or as a properly indexed array (numerical indices)
     *
     * @param bool|false $valuesOnly
     *
     * @return array
     */
    public function get($valuesOnly = false)
    {
        return $valuesOnly ? array_values($this->arr) : $this->arr;
    }

    /**
     * Returns only the keys accumulated on the internal list. This is useful when you want to dedupe items by reducing the collection into a hash keyed by the repeated value.
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->arr);
    }

    /**
     * Returns the first N elements of the internal array. If the array is empty, returns null. If the array has string keys (or lacks a key of "zero"), it returns the value matching the first key returned from PHP's array_keys method.
     * @param int $amount
     * @return array
     */
    public function first($amount = 1)
    {
        if (empty($this->arr) || $amount < 1) {
            return null;
        }

        $arr = [];
        foreach ($this->arr as $item) {
            $arr[] = $item;
            if (count($arr) === $amount) {
                break;
            }
        }

        if ($amount === 1) {
            return $arr[0];
        }

        return $arr;
    }

    /**
     * Returns the last element of the internal array. If the array is empty, returns null. Else, it returns the value matching the last key returned from PHP's array_keys method.
     * @return mixed
     */
    public function last()
    {
        if (empty($this->arr)) {
            return null;
        }

        $keys = array_keys($this->arr);
        return $this->arr[$keys[count($keys) - 1]];
    }

    /**
     * A static factory method that sets the internal array based on the array supplied to it.
     * @param array $arr
     * @return Chain
     */
    static public function from(array $arr)
    {
        return new Chain($arr);
    }

    /**
     * Creates a new array of the same length as the current internal array. Each element in the new array is whatever is returned by the predicate function. The keys
     *
     * Example:
     *
     * ```php
     * $array = [1, 2, 3, 4, 5];
     * $squares = Chain::from($array)
     *    ->map(function($value) {
     *       return $value * $value
     *    })
     *    ->get();
     * // $squares = [1, 4, 9, 16, 25];
     * ```
     *
     * @param callable $predicate Following the way JavaScript treats Array::map, the three arguments supplied to the predicate function are: the current value and key/index, and the current internal array. The internal array is only mutated once at the end of the entire iteration cycle, so you cannot modify the internal array mid-iteration.
     * @return $this
     */
    public function map(callable $predicate)
    {
        $arr = [];
        foreach ($this->arr as $key => $value) {
            $arr[$key] = $predicate($value, $key, $this->arr);
        }

        $this->arr = $arr;
        return $this;
    }

    /**
     * Creates a copy of the current internal array, with only values that return truthy from the predicate at a given iteration.
     *
     * Example:
     *
     * ```php
     * $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
     * $oddValues = Chain::from($array)
     *    ->filter(function($value) {
     *       return $value % 2; // 1 % 2 => 1, 2 % 2 => 0, 3 % 2 => 1, 4 % 2 => 0
     *    })
     *    ->get();
     * // $oddValues = [1, 3, 5, 7, 9];
     * ```
     *
     * @param callable $predicate Following the way JavaScript treats Array::filter, the three arguments supplied to the predicate function are: the current value and key/index, and the current internal array. The internal array is only mutated once at the end of the entire iteration cycle, so you cannot modify the internal array mid-iteration.
     * @return $this
     */
    public function filter(callable $predicate)
    {
        $arr = [];
        foreach ($this->arr as $key => $value) {
            $valid = $predicate($value, $key, $this->arr);
            if ($valid) {
                $arr[$key] = $value;
            }
        }

        $this->arr = $arr;
        return $this;
    }

    /**
     * @param callable $predicate
     * @param null $init
     * @return $this
     */
    public function reduce(callable $predicate, $init = null)
    {
        $this->arr = array_reduce($this->arr, $predicate, $init);
        return $this;
    }

    /**
     * Returns a flattened array of values from a nested list of maps having a given $key
     * @param $key
     * @return $this
     */
    public function flattenByKey($key)
    {
        $out = array();
        array_walk_recursive($this->arr, function ($value, $currKey) use (&$out, $key) {
            if ($currKey === $key) {
                array_push($out, $value);
            }
        });

        $this->arr = $out;
        return $this;
    }

    /**
     * @param callable $predicate
     * @param array $otherArray
     * @return $this
     */
    public function intersect(callable $predicate, array $otherArray)
    {
        $this->arr = array_filter($this->arr, function ($ours) use ($otherArray, $predicate) {
            foreach ($otherArray as $theirs) {
                if ($predicate($ours, $theirs)) {
                    return true;
                }
            }

            return false;
        });
        return $this;
    }

    /**
     * @param callable $predicate
     * @param array $otherArray
     * @return $this
     */
    public function diff(callable $predicate, array $otherArray)
    {
        $this->arr = array_filter($this->arr, function ($ours) use ($otherArray, $predicate) {
            foreach ($otherArray as $theirs) {
                if ($predicate($ours, $theirs)) {
                    return false;
                }
            }

            return true;
        });
        return $this;
    }

    /**
     * @return array
     */
    public function flip()
    {
        return array_flip($this->arr);
    }

    public function shuffle()
    {
        shuffle($this->arr);
        return $this;
    }
}
