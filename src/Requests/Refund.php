<?php

namespace Omnipay\Paymill\Requests;


class Refund extends Request
{
	/**
	 * The request url
	 *
	 * @return string
	 */
	public function url(): string
	{
		return "{$this->endpoint}/{$this->resource()}/{$this->getParameter('transactionId')}";
	}

	/**
	 * The endpoint resource
	 *
	 * @return string
	 */
	public function resource(): string
	{
		return 'refunds';
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
			'transactionId'
		];
	}
}