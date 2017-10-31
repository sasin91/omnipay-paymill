<?php

namespace Omnipay\Paymill\Responses;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Paymill\Support\Arr;
use function implode;
use function is_array;

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
	 * Response message
	 *
	 * @return null|string A response message from the payment gateway
	 */
	public function getMessage():?string
	{
		$messages = ($this->isSuccessful())
			? Arr::get($this->data, 'messages', [])
			: Arr::get($this->data, 'error.messages', []);

		return $this->parseMessages($messages);
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

	/**
	 * Parse gateway messages into a string.
	 *
	 * @param array|string $messages
	 *
	 * @return string
	 */
	private function parseMessages($messages): string
	{
		return implode(', ', is_array($messages) ? $messages : [$messages]);
	}
}
