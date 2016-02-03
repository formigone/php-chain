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

   public function get($valuesOnly = false)
   {
      return $valuesOnly ? array_values($this->arr) : $this->arr;
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
