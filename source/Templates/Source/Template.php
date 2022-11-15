<?php
declare(strict_types=1);

namespace Swaggier\Templates\Source;

use JsonException;

class Template
{
	private array $templateAsArray;
	private object $templateAsObject;

	/**
	 * @throws JsonException
	 */
	public function __construct(string $templatePath)
	{
		$file = file_get_contents(__DIR__ . "/../Matrix/$templatePath");
		$this->templateAsArray = json_decode($file, true, 512, JSON_THROW_ON_ERROR);
		$this->templateAsObject = json_decode($file, false, 512, JSON_THROW_ON_ERROR);
	}

	final public function asArray(): array
	{
		return $this->templateAsArray;
	}

	final public function asObject(): object
	{
		return $this->templateAsObject;
	}
}