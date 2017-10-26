<?php

namespace Omnipay\Paymill\Requests;


use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paymill\Responses\Response;

abstract class Request extends AbstractRequest
{
	/**
	 * @var string
	 */
	protected $endpoint = 'https://api.paymill.com/v2.1';

	/**
	 * @param string $value
	 * @return AbstractRequest
	 */
	public function setApiKey(string $value)
	{
		return $this->setParameter('apiKey', $value);
	}

	/**
	 * Pull a single parameter.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function pullParameter(string $key)
	{
		return tap($this->getParameter($key), function () use ($key) {
			$this->parameters->remove($key);
		});
	}

	/**
	 * @return string
	 */
	public function getEndpoint(): string
	{
		return $this->endpoint;
	}

	/**
	 * @param string $endpoint
	 * @return Request
	 */
	public function setEndpoint(string $endpoint)
	{
		$this->endpoint = $endpoint;
		return $this;
	}

	/**
	 * Get the raw data array for this message. The format of this varies from gateway to
	 * gateway, but will usually be either an associative array, or a SimpleXMLElement.
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$this->validate(
			...$this->requiredParameters()
		);

		return $this->parameters->all();
	}

	/**
	 * Parameters required to complete the request.
	 *
	 * @return array
	 */
	public abstract function requiredParameters(): array;

	/**
	 * Send the request with specified data
	 *
	 * @param  mixed $data The data to send
	 * @return ResponseInterface
	 */
	public function sendData($data): ResponseInterface
	{
		$response = $this->httpClient->send(
			$this->httpMethod(),
			$this->url(),
			['Authorization' => 'Basic ' . base64_encode($this->getApiKey() . ':')],
			\GuzzleHttp\json_encode($data)
		);

		$contents = $response->getBody()->getContents();
		$json = empty($contents) ? "{}" : $contents;

		return new Response(
			$this,
			\GuzzleHttp\json_decode($json, true)
		);
	}

	/**
	 * The client HTTP Method.
	 *
	 * @return string
	 */
	public function httpMethod(): string
	{
		return 'POST';
	}

	/**
	 * The request url
	 *
	 * @return string
	 */
	public function url(): string
	{
		return "{$this->endpoint}/{$this->resource()}";
	}

	/**
	 * The endpoint resource
	 *
	 * @return string
	 */
	public abstract function resource(): string;

	/**
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->getParameter('apiKey');
	}
}