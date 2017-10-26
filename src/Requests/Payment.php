<?php

namespace Omnipay\Paymill\Requests;


class Payment extends Request
{
	/**
	 * The endpoint resource
	 *
	 * @return string
	 */
	public function resource(): string
	{
		return 'payments';
	}

	/**
	 * Parameters required to complete the request.
	 *
	 * @return array
	 */
	public function requiredParameters(): array
	{
		return [
			'token',
		];
	}
}