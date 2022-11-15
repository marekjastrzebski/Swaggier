<?php
declare(strict_types=1);

namespace Swaggier\Communication;

use CurlHandle;
use Swaggier\Communication\Foundation\HttpMethods;
use Swaggier\messages\Message;

class SendRequest
{
	private array $headers = [];
	private HttpMethods $method;
	private CurlHandle $handle;
	private bool $ssl = true;
	private array $responseInfo;

	public function __construct(private readonly string $endPoint, private readonly array $request = [])
	{
		$this->payload = json_encode($this->request);
	}

	/**
	 * @return array
	 */
	final public function getResponseInfo(): array
	{
		return $this->responseInfo;
	}

	final public function setHeaders(array $headers): self
	{
		$this->headers = $headers;

		return $this;
	}

	final public function setMethod(HttpMethods $method): self
	{
		$this->method = $method;

		return $this;
	}

	final public function setSSL(bool $ssl): self
	{
		$this->ssl = $ssl;

		return $this;
	}

	final public function getResponse(): string
	{
		$this->handle = curl_init();
		curl_setopt($this->handle, CURLOPT_URL, $this->endPoint);
		$this->switchMetod();
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handle, CURLINFO_HEADER_OUT, true);
		curl_setopt($this->handle, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, $this->ssl);
		$results = curl_exec($this->handle);
		$this->responseInfo = curl_getinfo($this->handle);
		curl_close($this->handle);

		if (!$results) {
			die(Message::criticalError('Rest Api connection failure'));
		}

		return $results;
	}

	private function switchMetod(): bool
	{
		return match ($this->method) {
			HttpMethods::GET => curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "GET"),
			HttpMethods::POST => curl_setopt($this->handle, CURLOPT_POST, 1)
				&& curl_setopt($this->handle, CURLOPT_POSTFIELDS, $this->payload),
			HttpMethods::PUT => curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "PUT")
				&& curl_setopt($this->handle, CURLOPT_POSTFIELDS, $this->payload),
			HttpMethods::PATCH => curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "PATCH")
				&& curl_setopt($this->handle, CURLOPT_POSTFIELDS, $this->payload),
			HttpMethods::DELETE => curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "DELETE")
		};
	}
}