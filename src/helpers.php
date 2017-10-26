<?php

if (!function_exists('value')) {
	/**
	 * Return the default value of the given value.
	 *
	 * @param  mixed $value
	 *
	 * @return mixed
	 */
	function value($value)
	{
		return is_callable($value) ? $value() : $value;
	}
}


if (!function_exists('tap')) {
	/**
	 * Tap the given value.
	 *
	 * @param  mixed $value
	 * @param  callable $callback
	 * @return mixed
	 */
	function tap($value, callable $callback)
	{
		$callback($value);

		return $value;
	}
}