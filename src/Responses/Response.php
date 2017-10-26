<?php

namespace Omnipay\Paymill\Responses;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Paymill\Support\Arr;

class Response extends AbstractResponse
{
	/**
	 * Is the response successful?
	 *
	 * @return boolean
	 */
	public function isSuccessful(): bool
	{
		return $this->getCode() === "20000";
	}

	/**
	 * Response code
	 *
	 * @return null|string A response code from the payment gateway
	 */
	public function getCode():?string
	{
		return Arr::get($this->data, 'response_code', null);
	}

	/**
	 * Gateway Reference
	 *
	 * @return null|string A reference provided by the gateway to represent this transaction
	 */
	public function getTransactionReference():?string
	{
		return Arr::get($this->data, 'id', null);
	}
}