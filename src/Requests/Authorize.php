<?php

namespace Omnipay\Paymill\Requests;


class Authorize extends Request
{
	public function resource(): string
	{
		return 'Preauthorizations';
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
			'token',
		];
	}
}