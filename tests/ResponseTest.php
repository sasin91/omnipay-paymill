<?php

namespace Omnipay\Paymill\Tests;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paymill\Responses\Response;
use Omnipay\Paymill\Support\ResponseCode;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
	/**
	 * @return \Mockery\MockInterface | RequestInterface
	 */
	private function getMockRequest()
	{
		return \Mockery::mock(RequestInterface::class);
	}

	/** @test */
	public function it_extracts_the_response_code()
	{
		$response = new Response(
			$this->getMockRequest(),
			['response_code' => 20000]
		);

		$this->assertSame('20000', $response->getCode());
	}

	/** @test */
	public function it_extracts_a_nested_response_code()
	{
		$response = new Response(
			$this->getMockRequest(),
			['response' => ['response_code' => 20000]]
		);

		$this->assertSame('20000', $response->getCode());
	}

	/** @test */
	public function it_parses_error_messages()
	{
		$response = new Response(
			$this->getMockRequest(),
			[
				'response_code' => null,

				'error' => [
					'field' => 'amount',
					'messages' => ['parameter is required']
				]
			]
		);

		$this->assertSame('amount: parameter is required', $response->getMessage());
	}

	/** @test */
	public function it_parses_provider_messages()
	{
		$response = new Response(
			$this->getMockRequest(),
			[
				'response_code' => ResponseCode::OK,

				'messages' => [
					'everything is fine, move along.'
				]
			]
		);

		$this->assertSame('everything is fine, move along.', $response->getMessage());
	}

	/** @test */
	function it_extracts_nested_data()
	{
		$response = new Response(
			$this->getMockRequest(),
			[
				'response_code' => ResponseCode::OK,

				'data' => [
					'amount' => 4200,
				]
			]
		);

		$this->assertEquals(['amount' => 4200], $response->getData());
	}
}
