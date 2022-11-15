<?php
declare(strict_types=1);

namespace Swaggier\Builders;

use Swaggier\Builders\Source\BaseBuilder;
use Swaggier\Communication\Foundation\HttpMethods;
use Swaggier\Services\Converter;
use Swaggier\Services\TypeManager;
use Swaggier\Templates\RequestBodyTemplate;

class RequestBody extends BaseBuilder
{
	private string $method;
	private array $requestSchema = [];
	private array $responses = [];
	private string $name;
	private array $requestParam;

	public function __construct(private readonly string $endpoint)
	{
		parent::__construct(new RequestBodyTemplate());
	}

	final public function setMethod(HttpMethods $method): self
	{
		$this->method = $method->name;

		return $this;
	}

	final public function addResponse(): Response
	{
		$response = new Response($this->getNamePrefix());
		$this->responses[] = $response;

		return $response;
	}

	private function getNamePrefix(): string
	{
		$endpoint = str_replace(['/', '{', '}'], '.', $this->endpoint);
		return $this->method . '_' . $endpoint . '_';
	}

	final public function setRequestParam(array $param): self
	{
		$this->requestParam = $param;

		return $this;
	}

	final public function setRequest(array $request): self
	{
		$this->name = $this->getNamePrefix() . "request";
		$this->requestSchema = (new Converter($request, $this->name))->getSchemas();

		return $this;
	}

	final public function getSchemas(): array
	{
		return $this->buildSchemas();
	}

	private function buildSchemas(): array
	{
		$responseSchemas = [];
		foreach ($this->responses as $schema) {
			$responseSchemas += $schema->getSchema();
		}

		return array_merge($responseSchemas, $this->requestSchema);
	}

	final public function getRequestBodyWithResponses(): array
	{
		$template = [];
		if (!empty($this->requestParam)) {
			$this->template = $this->buildGetRequest();
			$template = $this->template;
		}
		if (!empty($this->requestSchema[$this->name]['required'])) {
			$this->template['requestBody']['content']['application/json']['schema']['$ref'] = '#/components/schemas/' . $this->name;
			$template = $this->template;
		}
		$template['responses'] = $this->buildResponses();

		return [strtolower($this->method) => $template];
	}

	private function buildGetRequest(): array
	{
		$parameters = [];
		for ($index = 0, $max = count($this->requestParam); $index < $max; $index++) {
			$name = array_keys($this->requestParam)[$index];
			$parameter = (new Parameter())
				->setName($name)
				->setType(TypeManager::openApiType($this->requestParam[$name]))
				->getParameter();

			$parameters[] = $parameter;
		}

		return !$parameters ? [] : ['parameters' => $parameters];
	}

	private function buildResponses(): array
	{
		$responses = [];
		foreach ($this->responses as $response) {
			$responses += $response->getResponse();
		}

		return $responses;
	}


}