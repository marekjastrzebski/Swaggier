<?php
declare(strict_types=1);

namespace Swaggier\Services;

use Swaggier\Components\BaseSchema;

class Converter
{
	private array $schemas = [];

	public function __construct(array $jsonContent, private string $schemaName)
	{
		$schema = new BaseSchema($schemaName);
		$this->schemas += $this->buildSchemas($jsonContent, $schema);
	}

	private function buildSchemas(array      $schema,
								  BaseSchema $schemaInstance)
	{
		foreach ($schema as $attributeName => $element) {
			$propertyName = is_int($attributeName) ? "swaggierTemporaryArrayKey$attributeName" : $attributeName;

			if (is_array($element)) {
				$schemaInstance->addRequired($propertyName)
					->addProperty($propertyName)
					->setType('object')
					->setExample($element)
					->getProperty();
				continue;
			}
			$this->addAttributeToCurrentSchema($attributeName, $element, $schemaInstance);
		}

		return $schemaInstance->getSchema();
	}

	private function addAttributeToCurrentSchema(string|int $attributeName,
												 mixed      $attributeValue,
												 BaseSchema $schemaInstance): BaseSchema
	{
		$schemaInstance->addRequired($attributeName)
			->addProperty($attributeName)
			->setType(TypeManager::openApiType($attributeValue))
			->setExample($attributeValue)
			->getProperty();

		return $schemaInstance;
	}

	final public function getSchemas(): array
	{
		return $this->schemas;
	}

	private function addNewSchema(string|int $attributeName,
								  array      $schema,
								  BaseSchema $schemaInstance)
	{
		$schemaInstance->setType("object")
			->addProperty($attributeName)
			->setType("object")
			->setRef("#/components/schemas/$attributeName")
			->getProperty();
		$newSchema = new BaseSchema($attributeName);

		$this->schemas += $this->buildSchemas($schema, $newSchema);
	}
}