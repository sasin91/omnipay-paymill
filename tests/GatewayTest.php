<?php

namespace Omnipay\Paymill\Tests;

use GuzzleHttp\Psr7\Response;
use Omnipay\Paymill\Gateway as PaymillGateway;
use Omnipay\Paymill\Requests\Authorize;
use Omnipay\Paymill\Requests\Payment;
use Omnipay\Paymill\Requests\Refund;
use Omnipay\Paymill\Requests\Request;
use Omnipay\Paymill\Requests\Transaction;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
	/**
	 * @var PaymillGateway
	 */
	protected $gateway;

	/** @test */
	public function testAuthorize()
	{
		/** @var Request $request */
		$request = $this->gateway->authorize($data = [
			'amount' => 10.00,
			'currency' => 'EUR',
			'token' => 'fake-payment-token',
		]);

		$this->getMockClient()->addResponse(
			new Response(
				200,
				[],
				'{"id":"preauth_e396d56e773f745dfbd3","amount":"1000","currency":"EUR","description":null,"status":"closed","livemode":false,"created_at":1350324120,"updated_at":1350324120,"app_id":null,"payment":"<Obejct>","client":"<Obejct>","transaction":"<Obejct>"}')
		);

		/** @var \Omnipay\Paymill\Responses\Response $response */
		$response = $request->send();
		$this->assertInstanceOf(Authorize::class, $request);
		$this->assertFalse($response->isRedirect());
		$this->assertEquals('preauth_e396d56e773f745dfbd3', $response->getTransactionReference());
	}

	public function testCapture()
	{
		$this->getMockClient()->addResponse(
			new Response(
				200,
				[],
				'{"id":"pay_3af44644dd6d25c820a8","type":"creditcard","client":null,"card_type":"visa","country":null,"expire_month":"10","expire_year":"2013","card_holder":"","last4":"1111","created_at":1349942085,"updated_at":1349942085,"app_id":null,"is_recurring":true,"is_usable_for_preauthorization":true}'
			)
		);

		$request = $this->gateway->capture(['client' => null, 'token' => 'fake-payment-token']);
		$response = $request->send();

		$this->assertInstanceOf(Payment::class, $request);
		$this->assertSame('pay_3af44644dd6d25c820a8', $response->getTransactionReference());
	}

	/** @test */
	public function testPurchase()
	{
		$this->getMockClient()->addResponse(
			new Response(
				200,
				[],
				'{"id":"tran_1f42e10cf14301067332","amount":"1000","origin_amount":1000,"status":"closed","description":null,"livemode":false,"refunds":null,"currency":"USD","created_at":1349946151,"updated_at":1349946151,"response_code":20000,"short_id":"0000.1212.3434","is_fraud":false,"invoices":[],"payment":"<Object>","client":"<Object>","preauthorization":null,"fees":[],"app_id":null}'
			)
		);

		$request = $this->gateway->purchase([
			'amount' => '10.00',
			'currency' => 'USD',
			'token' => 'fake-card-token'
		]);

		$response = $request->send();

		$this->assertInstanceOf(Transaction::class, $request);
		$this->assertTrue($response->isSuccessful());
		$this->assertFalse($response->isRedirect());
		$this->assertSame('tran_1f42e10cf14301067332', $response->getTransactionReference());
	}

	/** @test */
	public function testRefund()
	{
		$this->getMockClient()->addResponse(
			new Response(
				200,
				[],
				'{"id":"refund_70392dc6a734a8233130","amount":"4200","status":"refunded","description":null,"livemode":false,"created_at":1365154751,"updated_at":1365154751,"response_code":20000,"transaction":"<Object>","reason":"requested_by_customer","app_id":null}'
			)
		);

		$request = $this->gateway->refund([
			'transactionId' => 'tran_023d3b5769321c649435',
			'amount' => '42.00',
			'reason' => 'requested_by_customer',
		]);

		$response = $request->send();

		$this->assertInstanceOf(Refund::class, $request);
		$this->assertTrue($response->isSuccessful());
		$this->assertFalse($response->isRedirect());
		$this->assertSame('refund_70392dc6a734a8233130', $response->getTransactionReference());
		$this->assertSame('4200', $response->getData()['amount']);
		$this->assertSame('refunded', $response->getData()['status']);
		$this->assertSame('requested_by_customer', $response->getData()['reason']);
	}

	protected function setUp()
	{
		parent::setUp();

		$this->gateway = new PaymillGateway($this->getHttpClient(), $this->getHttpRequest());
		$this->gateway->setApiKey('fake-api-key');
	}
}
