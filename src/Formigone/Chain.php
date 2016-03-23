<?php

namespace Formigone;

/**
 * Class Chain
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
    * Returns the first element of the internal array. If the array is empty, returns null. If the array has string keys (or lacks a key of "zero"), it returns the value matching the first key returned from PHP's array_keys method.
    * @return mixed
    */
   public function first()
   {
      if (empty($this->arr)) {
         return null;
      }

      if (array_key_exists(0, $this->arr)) {
         return $this->arr[0];
      }

      $keys = array_keys($this->arr);
      return $this->arr[$keys[0]];
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

   static public function from(array $arr)
   {
      return new Chain($arr);
   }

   /**
    * @param callable $predicate
    * @return $this
    */
   public function map(callable $predicate)
   {
      $this->arr = array_map($predicate, $this->arr);
      return $this;
   }

   /**
    * @param callable $predicate
    * @return $this
    */
   public function filter(callable $predicate)
   {
      $this->arr = array_values(array_filter($this->arr, $predicate));
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
      array_walk_recursive($this->arr, function($value, $currKey) use (&$out, $key) {
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
      $this->arr = array_filter($this->arr, function($ours) use ($otherArray, $predicate) {
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
      $this->arr = array_filter($this->arr, function($ours) use ($otherArray, $predicate) {
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
}
