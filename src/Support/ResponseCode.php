<?php

namespace Omnipay\Paymill\Support;

/**
 * Class ResponseCode
 *
 * @package \Omnipay\Paymill\Support
 */
class ResponseCode
{
	const OK = "20000";
	const WITHHELD_DUE_NEW_MERCHANT = "20100";
	const WITHHELD = "20101";

	public static function isSuccessful($code): bool
	{
		return $code === static::OK
			|| $code === static::WITHHELD
			|| $code === static::WITHHELD_DUE_NEW_MERCHANT;
	}
}
