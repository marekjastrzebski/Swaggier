<?php
declare(strict_types=1);

namespace Swaggier\Builders;

use Swaggier\Builders\Source\BaseBuilder;
use Swaggier\Communication\Foundation\HttpCodes;
use Swaggier\Services\Converter;
use Swaggier\Templates\ResponseContentTemplate;

class Response extends BaseBuilder
{
	private string $content = "";
	private int $code;
	private array $responseSchema;

	public function __construct(private readonly string $responseName)
	{
		parent::__construct(new ResponseContentTemplate());
	}

	final public function setCode(int $httpCode): self
	{
		$this->code = $httpCode;

		return $this;
	}


	final public function setContent(array $response): self
	{
		$refName = $this->responseName . $this->code;
		$this->responseSchema = (new Converter($response, $refName))->getSchemas();
		$this->template["content"]["application/json"]["schema"]['$ref'] = "#/components/schemas/$refName";

		return $this;
	}

	final public function getResponse(): array
	{
		$response[$this->code] = ["description" => HttpCodes::Code[$this->code]];
		$response[$this->code] += $this->template;

		return $response;
	}

	final public function getSchema(): array
	{
		return $this->responseSchema;
	}
}