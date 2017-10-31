<?php

namespace Omnipay\Paymill\Support;

use ArrayAccess;

class Arr
{
	/**
	 * Return the first element in an array passing a given truth test.
	 *
	 * @param  array         $array
	 * @param  callable|null $callback
	 * @param  mixed         $default
	 *
	 * @return mixed
	 */
	public static function first($array, callable $callback = null, $default = null)
	{
		if (is_null($callback)) {
			if (empty($array)) {
				return value($default);
			}

			foreach ($array as $item) {
				return $item;
			}
		}

		foreach ($array as $key => $value) {
			if (call_user_func($callback, $value, $key)) {
				return $value;
			}
		}

		return value($default);
	}

	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  \ArrayAccess|array $array
	 * @param  string $key
	 * @param  mixed $default
	 * @return mixed
	 */
	public static function get($array, $key, $default = null)
	{
		if (!static::accessible($array)) {
			return value($default);
		}

		if (is_null($key)) {
			return $array;
		}

		if (static::exists($array, $key)) {
			return $array[$key];
		}

		if (strpos($key, '.') === false) {
			return $array[$key] ?? value($default);
		}

		foreach (explode('.', $key) as $segment) {
			if (static::accessible($array) && static::exists($array, $segment)) {
				$array = $array[$segment];
			} else {
				return value($default);
			}
		}

		return $array;
	}

	/**
	 * Determine whether the given value is array accessible.
	 *
	 * @param  mixed $value
	 * @return bool
	 */
	public static function accessible($value)
	{
		return is_array($value) || $value instanceof ArrayAccess;
	}

	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param  \ArrayAccess|array $array
	 * @param  string|int $key
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
	 * If the given value is not an array and not null, wrap it in one.
	 *
	 * @param  mixed $value
	 * @return array
	 */
	public static function wrap($value)
	{
		if (is_null($value)) {
			return [];
		}

		return !is_array($value) ? [$value] : $value;
	}
}
