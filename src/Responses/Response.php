<?php

namespace Omnipay\Paymill\Responses;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Paymill\Support\Arr;
use Omnipay\Paymill\Support\ResponseCode;

class Response extends AbstractResponse
{
	/**
	 * Is the response successful?
	 *
	 * @return boolean
	 */
	public function isSuccessful(): bool
	{
		return ResponseCode::isSuccessful($this->getCode());
	}

	/**
	 * Response code
	 *
	 * @return null|string A response code from the payment gateway
	 */
	public function getCode():?string
	{
		return Arr::first(
			Arr::pluck($this->data, 'response_code')
		);
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
			: Arr::get($this->data, 'error', []);

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
		$messages = Arr::wrap($messages);

		if (array_key_exists('messages', $messages)) {
			return sprintf(
				'%s: %s',
				Arr::get($messages, 'field', ''),
				implode(', ', Arr::get($messages, 'messages'))
			);
		}

		return implode(', ', $messages);
	}
}
