<?php

namespace Omnipay\Paymill\Requests;


class Transaction extends Request
{
	/**
	 * The endpoint resource
	 *
	 * @return string
	 */
	public function resource(): string
	{
		return 'transactions';
	}

	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 * gateway, but will usually be either an associative array, or a SimpleXMLElement.
	 *
	 * @return mixed
	 */
	public function getData()
	{
		return tap(parent::getData(), function ($data) {
			$data['currency'] = $this->getCurrency();
		});
	}

	/**
	 * Parameters required to complete the request.
	 *
	 * @return array
	 */
	public function requiredParameters(): array
	{
		return [
			'amount',
			'currency',
//			'token',
//			'payment',
//			'preauthorization',
		];
	}
}