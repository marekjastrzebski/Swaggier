<?php
declare(strict_types=1);

namespace Swaggier\Components;

class BaseSchema
{
	private array $schema = [];
	private array $property = [];
	private string $name;

	public function __construct(string $schemaName)
	{
		$this->name = $schemaName;
	}

	final public function addRequired(string|int $attribute): self
	{
		$this->schema[$this->name]["required"][] = $attribute;

		return $this;
	}

	final public function setType(string $type): self
	{
		$this->schema[$this->name]["type"] = $type;

		return $this;
	}

	final public function addProperty(string $name): BaseProperty
	{
		$this->property[$name] = new BaseProperty($name);

		return $this->property[$name];
	}

	final public function getSchema(): array
	{
		$this->schema[$this->name]["properties"] = $this->buildProperties() ?: (object)[];

		return $this->schema;
	}

	private function buildProperties(): array
	{
		$propertyList = [];
		foreach ($this->property as $property) {
			$propertyList += $property->getProperty();
		}
		
		return $propertyList;
	}
}

