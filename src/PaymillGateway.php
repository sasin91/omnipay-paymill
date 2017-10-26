<?php

namespace Omnipay\Paymill;


use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Paymill\Requests\Authorize;
use Omnipay\Paymill\Requests\Payment;
use Omnipay\Paymill\Requests\Refund;
use Omnipay\Paymill\Requests\Transaction;

class PaymillGateway extends AbstractGateway
{
	/**
	 * @var string
	 */
	protected $token;

	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token;
	}

	/**
	 * @param string $token
	 * @return PaymillGateway
	 */
	public function setToken(string $token): PaymillGateway
	{
		$this->token = $token;
		return $this;
	}

	/**
	 * Get gateway display name
	 *
	 * This can be used by carts to get the display name for each gateway.
	 */
	public function getName(): string
	{
		return 'paymill';
	}

	/**
	 * Get gateway short name
	 *
	 * This name can be used with GatewayFactory as an alias of the gateway class,
	 * to create new instances of this gateway.
	 */
	public function getShortName(): string
	{
		return 'pm';
	}

	/**
	 * Define gateway parameters, in the following format:
	 *
	 * array(
	 *     'username' => '', // string variable
	 *     'testMode' => false, // boolean variable
	 *     'landingPage' => array('billing', 'login'), // enum variable, first item is default
	 * );
	 */
	public function getDefaultParameters()
	{
		return [
			'apiKey' => '',
		];
	}

	/**
	 * Get the gateway secret api key
	 *
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->getParameter('apiKey');
	}

	/**
	 * Set the gateway secret api key
	 *
	 * @param $value
	 * @return $this
	 */
	public function setApiKey($value)
	{
		return $this->setParameter('apiKey', $value);
	}

	/**
	 * Authorize an amount on the customer's card.
	 *
	 * @param array $parameters
	 * @return RequestInterface
	 */
	public function authorize(array $parameters = []): RequestInterface
	{
		return $this->createRequest(Authorize::class, $parameters);
	}

	/**
	 * Authorize and immediately capture an amount on the customers card.
	 *
	 * @param array $parameters
	 * @return RequestInterface
	 */
	public function purchase(array $parameters = []): RequestInterface
	{
		$parameters = $this->hasToken()
			? array_merge($parameters, ['token' => $this->token])
			: array_merge($parameters, ['payment' => $this->capture($parameters)]);

		return $this->createRequest(Transaction::class, $parameters);
	}

	/**
	 * Determine if we have a token from bridge.
	 *
	 * @return bool
	 */
	public function hasToken(): bool
	{
		return !is_null($this->token);
	}

	/**
	 * Capture an amount you have previously authorized.
	 *
	 * @param array $parameters
	 * @return RequestInterface
	 */
	public function capture(array $parameters = []): RequestInterface
	{
		return $this->createRequest(Payment::class, $parameters);
	}

	/**
	 * Refund an already processed transaction.
	 *
	 * @param array $parameters
	 * @return RequestInterface
	 */
	public function refund(array $parameters = []): RequestInterface
	{
		return $this->createRequest(Refund::class, $parameters);
	}
}