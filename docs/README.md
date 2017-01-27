# API Documentation

## Table of Contents

* [Chain](#chain)
    * [__construct](#__construct)
    * [get](#get)
    * [getKeys](#getkeys)
    * [first](#first)
    * [last](#last)
    * [from](#from)
    * [map](#map)
    * [filter](#filter)
    * [reduce](#reduce)
    * [flattenByKey](#flattenbykey)
    * [intersect](#intersect)
    * [diff](#diff)
    * [flip](#flip)

## Chain

Class Chain

A thin abstraction over PHP arrays to help you easily chain array transformation functions.

* Full name: \Formigone\Chain


### __construct



```php
Chain::__construct( array $arr )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$arr` | **array** |  |




---

### get

Returns the internal array structure as is (keys might be all weird due to filtering), or as a properly indexed array (numerical indices)

```php
Chain::get( boolean|false $valuesOnly = false ): array
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$valuesOnly` | **boolean&#124;false** |  |




---

### getKeys

Returns only the keys accumulated on the internal list. This is useful when you want to dedupe items by reducing the collection into a hash keyed by the repeated value.

```php
Chain::getKeys(  ): array
```







---

### first

Returns the first element of the internal array. If the array is empty, returns null. If the array has string keys (or lacks a key of "zero"), it returns the value matching the first key returned from PHP's array_keys method.

```php
Chain::first(  ): mixed
```







---

### last

Returns the last element of the internal array. If the array is empty, returns null. Else, it returns the value matching the last key returned from PHP's array_keys method.

```php
Chain::last(  ): mixed
```







---

### from

A static factory method that sets the internal array based on the array supplied to it.

```php
Chain::from( array $arr ): \Formigone\Chain
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$arr` | **array** |  |




---

### map

Creates a new array of the same length as the current internal array. Each element in the new array is whatever is returned by the predicate function. The keys

```php
Chain::map( callable $predicate ): $this
```

Example:

```
$array = [1, 2, 3, 4, 5];
$squares = Chain::from($array)
   ->map(function($value) {
      return $value * $value
   })
   ->get();
// $squares = [1, 4, 9, 16, 25];
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$predicate` | **callable** | Following the way JavaScript treats Array::map, the three arguments supplied to the predicate function are: the current value and key/index, and the current internal array. The internal array is only mutated once at the end of the entire iteration cycle, so you cannot modify the internal array mid-iteration. |




---

### filter

Creates a copy of the current internal array, with only values that return truthy from the predicate at a given iteration.

```php
Chain::filter( callable $predicate ): $this
```

Example:

```
$array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$oddValues = Chain::from($array)
   ->filter(function($value) {
      return $value % 2; // 1 % 2 => 1, 2 % 2 => 0, 3 % 2 => 1, 4 % 2 => 0
   })
   ->get();
// $oddValues = [1, 3, 5, 7, 9];
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$predicate` | **callable** | Following the way JavaScript treats Array::filter, the three arguments supplied to the predicate function are: the current value and key/index, and the current internal array. The internal array is only mutated once at the end of the entire iteration cycle, so you cannot modify the internal array mid-iteration. |




---

### reduce



```php
Chain::reduce( callable $predicate, null $init = null ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$predicate` | **callable** |  |
| `$init` | **null** |  |




---

### flattenByKey

Returns a flattened array of values from a nested list of maps having a given $key

```php
Chain::flattenByKey(  $key ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **** |  |




---

### intersect



```php
Chain::intersect( callable $predicate, array $otherArray ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$predicate` | **callable** |  |
| `$otherArray` | **array** |  |




---

### diff



```php
Chain::diff( callable $predicate, array $otherArray ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$predicate` | **callable** |  |
| `$otherArray` | **array** |  |




---

### flip



```php
Chain::flip(  ): array
```







---



--------
> This document was automatically generated from source code comments on 
Warning: date_default_timezone_get(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. in /Users/rsilveira/dev/php-chain/vendor/twig/twig/lib/Twig/Extension/Core.php on line 93
2017-01-27 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
