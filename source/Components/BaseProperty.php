<?php
declare(strict_types=1);

namespace Swaggier\Components;

class BaseProperty
{
	private array $property = [];
	private string|int $name;

	public function __construct(string|int $name)
	{
		$this->name = $name;
	}

	final public function setType(mixed $type): self
	{
		$this->property[$this->name]["type"] = $type;

		return $this;
	}

	final public function setRef(string $ref)
	{
		$this->property[$this->name]["items"]['$ref'] = $ref;

		return $this;
	}

	final public function setArrayExample(array $example): self
	{
		$this->property[$this->name]["items"]["example"] = $example;

		return $this;
	}

	final public function setExample(mixed $example): self
	{
		$this->property[$this->name]["example"] = $example;

		return $this;
	}

	final public function setRequired(bool $required): self
	{
		$this->property[$this->name]["required"] = $required;

		return $this;
	}


	final public function setContent(array $array)
	{
		$this->property[$this->name]["content"] = implode(",", $array);

		return $this;
	}

	final public function getProperty(): array
	{
		return $this->property;
	}
}
